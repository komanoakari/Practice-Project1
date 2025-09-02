<?php

namespace App\Http\Controllers;

use App\Http\Requests\CommentRequest;
use App\Models\Product;
use Illuminate\Http\Request;

class CommentController extends Controller
{
    public function addComment(CommentRequest $request, Product $product)
    {
        $product->comments()->create([
            'user_id' => $request->user()->id,
            'body' => $request->input('body'),
        ]);

        return back()->with('status', 'コメントを投稿しました');
    }
}
