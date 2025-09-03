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
        $dir = "images";

        $file_name = $request->file('image')->getClientOriginalName();
        $request->file('image')->storeAs('public/'.$dir, $file_name);
        $imagePathView = 'storage/'.$dir.'/'.$file_name;

        DB::transaction(function () use ($request, $imagePathView){
            $product = new Product();
            $product->name = $request->input('name');
            $product->brand = $request->input('brand');
            $product->price = $request->input('price');
            $product->image = $imagePathView;
            $product->description = $request->input('description');
            $product->condition = $request->input('condition');
            $product->save();

            $categoryIds = $request->input('category', []);

            $product->categories()->sync($categoryIds);
        });

        return redirect('/')->with('status', '出品が完了しました');
    }
}