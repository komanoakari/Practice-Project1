<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\ProfileController;


/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', [ProductController::class, 'getProducts'])->name('products.index');

Route::post('/logout', function () {
    Auth::logout();
    return redirect('/');
})->name('logout');

Route::get('/item/{product}', [ProductController::class, 'getDetail'])->name('products.show');

Route::middleware('auth')->group(function () {
    Route::get('/mypage/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::post('/mypage/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::get('/mypage', [ProfileController::class, 'show']);
    Route::post('/purchase/{product}', [ProductController::class, 'store'])->name('purchase.store');

    Route::post('/item/{product}/mylist', [ProductController::class, 'addMylist'])->name('mylist.store');
    Route::delete('/item/{product}/mylist', [ProductController::class, 'removeMylist'])->name('mylist.destroy');
});

