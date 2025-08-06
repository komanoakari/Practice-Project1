<?php

namespace App\Http\Controllers;
use App\Models\Product;
use App\Models\Profile;

use Illuminate\Http\Request;

class ProductController extends Controller
{
    public function index()
    {
        $products = Product::all();
        return view('list', compact('products'));
    }

    public function show()
    {
        $profiles = Profile::all();
        return view('edit', compact('profiles'));
    }
}
