<?php

namespace App\Http\Controllers;

use App\Models\Product;
use Illuminate\Http\Request;

class PurchaseController extends Controller
{
    public function store(Request $request, Product $product)
    {
        return view('purchase');
    }


}
