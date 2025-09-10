<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\Order;
use App\Http\Requests\PurchaseRequest;
use App\Http\Requests\AddressRequest;
use Illuminate\Support\Facades\Auth;


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
        $order = Order::create($request->validated() + [
            'user_id' => auth()->id(),
            'product_id' => $product->id,
        ]);

        return redirect('/')->with('status','商品を購入しました');
    }

    public function edit(Product $product)
    {
        return view('address_edit', compact('product'));
    }

    public function update(AddressRequest $request, Product $product)
    {
        $shipping = $request->only(['shipping_postal_code', 'shipping_address', 'shipping_building']);
        session(['checkout.shipping'=> $shipping]);

        return redirect()
            ->route('purchase.create',  ['product' => $product->id])
            ->with('success', '住所を更新しました');
    }
}
