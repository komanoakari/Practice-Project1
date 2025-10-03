<?php

namespace App\Http\Controllers;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use Stripe\Stripe;
use Stripe\Checkout\Session as StripeSession;
use App\Models\Product;
use App\Models\Order;
use App\Http\Requests\PurchaseRequest;
use App\Http\Requests\AddressRequest;

class PurchaseController extends Controller
{
    public function create(Product $product)
    {
        $profile = Auth::user()->profile;

        $profileShipping = [
            'shipping_postal_code' => $profile->postal_code ?? '',
            'shipping_address' => $profile->address ?? '',
            'shipping_building' => $profile->building ?? '',
        ];

        $shipping = session('checkout.shipping', $profileShipping);

        return view('purchase', compact('product', 'shipping'));
    }

    public function store(PurchaseRequest $request, Product $product)
    {
        if ($product->user_id === auth()->id()) {
            return redirect()
                ->route('products.show', $product)
                ->with('status', 'ご自身の商品は購入できません');
        }

        if ($product->is_sold) {
            return redirect()
                ->route('products.show', $product)
                ->with('status', 'この商品はすでに売り切れです');
        }

        $profile = Auth::user()->profile;

        $profileShipping = [
            'shipping_postal_code' => $profile->postal_code ?? '',
            'shipping_address' => $profile->address ?? '',
            'shipping_building' => $profile->building ?? '',
        ];

        $shipping = session('checkout.shipping', $profileShipping);

        $order = Order::create($request->validated() + [
            'user_id' => auth()->id(),
            'product_id' => $product->id,
            'amount' => $product->price,
            'payment_method' => $request->payment_method,
            'status' => 'pending',
            'shipping_postal_code' => $shipping['shipping_postal_code'],
            'shipping_address' => $shipping['shipping_address'],
            'shipping_building' => $shipping['shipping_building'],
        ]);

        if ($request->payment_method === 'コンビニ支払い') {
            $order->update([
                'status' => 'paid',
                'paid_at' => now(),
            ]);

        session()->forget('checkout.shipping');

        return redirect('/')->with('status', 'コンビニ払いで購入を完了しました');
        }

        Stripe::setApiKey(config('services.stripe.secret'));

        $session = StripeSession::create([
            'mode' => 'payment',
            'payment_method_types' => ['card'],
            'line_items' => [[
                'price_data' => [
                    'currency' => 'jpy',
                    'product_data' => ['name' => $product->name],
                    'unit_amount' => (int) $product->price,
                ],
                'quantity' => 1,
            ]],

            'success_url' => route('checkout.success',['order' => $order->id]) . '?session_id={CHECKOUT_SESSION_ID}',
            'cancel_url' => route('checkout.cancel', ['order' => $order->id]),
        ]);

        $order->update(['stripe_session_id' => $session->id]);

        return redirect()->away($session->url);
    }

    public function success(Request $request, Order $order)
    {
        if ($order->payment_method === 'コンビニ支払い' || $order->status === 'paid') {
            return redirect('/')->with('status', '商品を購入しました');
        }

        $sid = $request->query('session_id');

        if (empty($sid)) {
            return redirect('/')->withErrors('決済確認に失敗しました');
        }

        if ($sid !== $order->stripe_session_id) {
            return redirect('/')->withErrors('決済確認に失敗しました');
        }

        Stripe::setApiKey(config('services.stripe.secret'));
        $session = StripeSession::retrieve($sid);

        if ($session->payment_status === 'paid') {
            $order->update([
                'status' => 'paid',
                'paid_at' => now(),
            ]);

            session()->forget('checkout.shipping');
            return redirect('/')->with('status', '商品を購入しました');
        }

        return redirect('/')->with('status','お支払い手続き中です');
    }

    public function cancel(Order $order)
    {
        if ($order->status === 'paid') {
            return redirect('/')->with('status', 'すでに購入済みです');
        }

        $order->update(['status' => 'canceled']);

        session()->forget('checkout.shipping');

        return redirect()
            ->route('purchase.create', ['product' => $order->product_id])
            ->with('status', '決済をキャンセルしました');
    }

    public function edit(Product $product)
    {
        return view('address_edit', compact('product'));
    }

    public function update(AddressRequest $request, Product $product)
    {
        $shipping = $request->only(['shipping_postal_code', 'shipping_address', 'shipping_building']);
        session(['checkout.shipping' => $shipping]);

        return redirect()
            ->route('purchase.create',  ['product' => $product->id])
            ->with('status', '住所を更新しました');
    }
}
