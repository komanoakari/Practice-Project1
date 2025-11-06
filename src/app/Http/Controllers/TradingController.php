<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Requests\MessageRequest;
use App\Models\Order;
use App\Models\TransactionMessage;
use Illuminate\Support\Facades\Auth;

class TradingController extends Controller
{
    public function show(Order $order)
    {
        $data = $this->getTradeViewData($order);
        return view('trade', $data);
    }

    public function update(Order $order)
    {
        $order->update(['completed_at' => now()]);

        return back()->with('showReviewModal', true);
    }

    public function review(Order $order)
    {
        return redirect()->route('products.index');
    }

    public function send(MessageRequest $request, Order $order)
    {
        $data = [
            'order_id' => $order->id,
            'user_id' => auth()->id(),
            'body' => $request->body,
        ];

        if($request->hasFile('image')) {
            $data['image'] = $request->file('image')->store('images', 'public');
        }
        TransactionMessage::create($data);

        return redirect()->route('trade.show', ['order' => $order->id]);
    }

    public function edit(Order $order, TransactionMessage $message)
    {
        $data = $this->getTradeViewData($order);
        $data['editMessage'] = $message;
        return view('trade', $data);
    }

    public function updateMessage(MessageRequest $request, Order $order, TransactionMessage $message)
    {
        $data = ['body' => $request->body];
        if($request->hasFile('image')) {
            $data['image'] = $request->file('image')->store('images', 'public');
        }
        dd($data);

        $message->update($data);

        return redirect()->route('trade.show', ['order' => $order->id]);
    }

    public function remove(Order $order, TransactionMessage $message)
    {
        $message->delete();

        return redirect()->route('trade.show', ['order' => $order->id]);
    }

    private function getTradeViewData(Order $order)
    {
        $user = auth()->user();

        $order->transactionMessages()
            ->where('user_id', '!=', $user->id)
            ->whereNull('read_at')
            ->update(['read_at' => now()]);

        $partner = $order->partner();

        $messages = $order->transactionMessages()
            ->with('user')
            ->orderBy('created_at', 'asc')
            ->get();

        $buyingOrders = auth()->user()->orders()
            ->where('status', 'paid')
            ->whereNull('completed_at')
            ->where('id', '!=', $order->id)
            ->with('product')
            ->get();

        $sellingOrders = auth()->user()->products()
            ->whereHas('order', function($query) use ($order) {
                $query->where('status', 'paid')
                    ->whereNull('completed_at')
                    ->where('id', '!=', $order->id);
            })
            ->with('order')
            ->get()
            ->map(function($product) {
                return $product->order;
            });

        $otherTradings = $buyingOrders->concat($sellingOrders);

        return compact('user', 'order', 'partner', 'messages', 'otherTradings');
    }
}
