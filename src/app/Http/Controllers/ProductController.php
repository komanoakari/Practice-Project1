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
    public function index(Request $request)
    {
        $tab = $request->query('tab', 'recommended');

        $products = Product::latest()->paginate(12);

        $mylistedProducts = Auth::check()
            ? Auth::user()->mylistProducts()->latest()->paginate(12)
            : null;

        return view('list', compact('products', 'mylistedProducts', 'tab'));
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

        return redirect()->route('profile.show')->with('status', '出品が完了しました');
    }

    public function search(Request $request)
    {
        $keyword = $request->input('q');

        $tab = $request->query('tab','recommended');

        $productQuery = Product::query();

        if(!empty($keyword)) {
            $productQuery->where('name', 'like', "%{$keyword}%");
        }

        $productQuery->orderBy('created_at', 'desc');

        $products = $productQuery 
            ->paginate(12)
            ->withQueryString();

        if(\Auth::check()) {
            $mylistQuery = \Auth::user()->mylistProducts();

            if(!empty($keyword)) {
                $mylistQuery->where('name', 'like', "%{$keyword}%");
            }

            $mylistQuery->orderBy('created_at', 'desc');

            $mylistedProducts = $mylistQuery
                ->paginate(12)
                ->withQueryString();
        } else {
            $mylistedProducts = null;
        }

        return view('list', compact('products', 'mylistedProducts', 'tab', 'keyword'));
    }
}