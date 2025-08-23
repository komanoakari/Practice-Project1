<?php

namespace App\Http\Controllers;
use App\Models\Product;
use App\Models\Category;
use App\Models\ProductCategory;
use App\Models\Comment;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ProductController extends Controller
{
    public function getProducts(Request $request)
    {
        $uid = Auth::id();
        $tab = $request->query('tab', 'recommended');

        if ($tab === 'mylist') {
            if (!$uid) {
                $products = collect();
            } else {
                $products = Auth::user()
                ->mylistProducts()
                ->latest()
                ->get();
            }
        } else {
            $query = Product::query();
            if ($uid) {
                $query->where(function ($q) use ($uid) {
                    $q->whereNull('user_id')->orWhere('user_id', '!=', $uid);
                });
            }
            $products = $query->latest()->get();
        }
        return view('list', compact('products', 'tab'));
    }

    public function getDetail(Product $product)
    {
        $product->load('categories')
                ->loadCount('comments', 'wishlistBy as likes_count');

        $liked = Auth()->check()
            ? $product->wishlistBy()->where('user_id', auth()->id())->exists()
            : false;

        return view('detail', compact('product', 'liked'));
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
