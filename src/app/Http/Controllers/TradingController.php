<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Requests\MessageRequest;
use App\Models\Order;
use App\Models\User;
use App\Models\TransactionMessage;
use App\Models\Evaluation;
use App\Notifications\TradeCompleted;

use Illuminate\Support\Facades\Auth;

class TradingController extends Controller
{
    public function show(Order $order)
    {
        $data = $this->getTradeViewData($order);

        $hasEvaluated = Evaluation::where('evaluator_id', auth()->id())
            ->where('order_id', $order->id)
            ->exists();

        if (!$hasEvaluated && $order->completed_at) {
            return view('trade', $data)->with('showReviewModal', true);
        }

        return view('trade', $data);
    }

    public function update(Order $order)
    {
        if($order->completed_at) {
            return redirect()->back();
        }

        $order->update(['completed_at' => now()]);

        $seller = User::find($order->product->user_id);
        $seller->notify(new TradeCompleted($order));

        return back()->with('showReviewModal', true);
    }

    public function review(Request $request, Order $order)
    {
        if($order->user_id === auth()->id()){
            $evaluatedUserId = $order->product->user->id;
        } else {
            $evaluatedUserId = $order->user->id;
        }

        Evaluation::create([
            'evaluator_id' => auth()->id(),
            'evaluated_user_id' => $evaluatedUserId,
            'order_id' => $order->id,
            'rating' => $request->review,
        ]);

        $order->checkAndComplete();

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

        $allTradings = $buyingOrders->concat($sellingOrders)
            ->sortByDesc(function($order) {
                return $order->transactionMessages()->latest()->first()?->created_at;
            })
            ->values();

        $tradings = $allTradings->filter(function ($trading) {
            $hasEvaluated = $trading->evaluations()
                ->exists();
            return !$hasEvaluated;
        });

        return compact('user', 'order', 'partner', 'messages', 'tradings');
    }
}
