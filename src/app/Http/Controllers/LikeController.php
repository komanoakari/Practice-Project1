<?php

namespace App\Http\Controllers;

use App\Models\Product;
use Illuminate\Http\Request;

class LikeController extends Controller
{
    public function likes(Product $product)
    {
        return response()->json([
            'likes_count' => $product->wishlistBy()->count(),
            'liked' => auth()->check()
                ? $product->wishlistBy()->wherePivot('user_id', auth()->id())->exists()
                : false
        ]);
    }

    public function addMylist(Request $request, Product $product)
    {
        $product->wishlistBy()->syncWithoutDetaching([$request->user()->id]);

        $liked = true;
        $likesCount = $product->wishlistBy()->count();

        return response()->json([
            'liked' => $liked,
            'likes_count' => $likesCount,
        ]);
    }

    public function removeMylist(Request $request, Product $product)
    {
        $product->wishlistBy()->detach([$request->user()->id]);

        $liked = false;
        $likesCount = $product->wishlistBy()->count();

        return response()->json([
            'liked' => $liked,
            'likes_count' => $likesCount,
        ]);
    }
}
