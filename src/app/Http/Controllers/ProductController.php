<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\DB;
use App\Models\Product;
use App\Models\Category;
use Illuminate\Http\Request;
use App\Http\Requests\ExhibitionRequest;
use Illuminate\Support\Facades\Auth;

class ProductController extends Controller
{
    public function getProducts(Request $request)
    {
        $uid = Auth::id();
        $tab = $request->query('tab', 'recommended');

        if ($tab === 'mylist') {
            $products = $uid
                ? Auth::user()->mylistProducts()->latest()->get()
                : collect();
        } else {
            $products = Product::latest()->get();
        }
        return view('list', compact('products', 'tab'));
    }

    public function getDetail(Product $product)
    {
        $product->load('categories')
                ->loadCount(['comments', 'wishlistBy as likes_count']);

        $liked = auth()->check()
            ? $product->wishlistBy()->where('user_id', auth()->id())->exists()
            : false;

        return view('detail', compact('product', 'liked'));
    }

    public function create()
    {
        $categories = Category::orderBy('id')->get();
        return view('listing', compact('categories'));
    }

    public function storeListing(ExhibitionRequest $request)
    {
        $path = $request->file('image')->store('images', 'public');

        $product = $request->user()->products()->create([
            'name' => $request->name,
            'brand' => $request->brand,
            'price' => $request->price,
            'image' => $path,
            'description' => $request->description,
            'condition' => $request->condition,
        ]);

        $product->categories()->sync($request->input('category', []));

        return redirect()->route('products.index')->with('status', '出品が完了しました');
    }
}