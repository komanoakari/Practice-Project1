<?php

namespace App\Http\Controllers;
use App\Models\Product;
use App\Models\Category;
use App\Models\ProductCategory;
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
}
