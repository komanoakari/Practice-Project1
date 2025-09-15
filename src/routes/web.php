<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\LikeController;
use App\Http\Controllers\CommentController;
use App\Http\Controllers\PurchaseController;
use App\Http\Controllers\CheckoutController;


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

Route::get('/login', function () {
    return view('auth.login');
})
    ->name('login')
    ->middleware(['guest', 'remember.redirect']);

Route::post('/login', [AuthController::class, 'login'])
    ->name('login.store')
    ->middleware('guest');

Route::get('/item/{product}', [ProductController::class, 'getDetail'])->name('products.show');

Route::get('/item/{product}/likes', [LikeController::class, 'likes'])->name('likes.count');

Route::middleware('auth')->group(function () {
    Route::get('/mypage/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::post('/mypage/profile', [ProfileController::class, 'update'])->name('profile.update');

    Route::get('/mypage', [ProfileController::class, 'show'])->name('profile.show');

    Route::get('/purchase/{product}', [PurchaseController::class, 'create'])->name('purchase.create');
    Route::post('/purchase/{product}', [PurchaseController::class, 'store'])->name('purchase.store');

    Route::get('/purchase/address/{product}', [PurchaseController::class, 'edit'])->name('address.edit');
    Route::put('/purchase/address/{product}', [PurchaseController::class, 'update'])->name('address.update');

    Route::get('/checkout/stripe', [CheckoutController::class, 'stripe'])->name('checkout.stripe');

    Route::post('/item/{product}/mylist', [LikeController::class, 'addMylist'])->name('mylist.store');
    Route::delete('/item/{product}/mylist', [LikeController::class, 'removeMylist'])->name('mylist.destroy');

    Route::post('/item/{product}/comment', [CommentController::class, 'addComment'])->name('comment.store');

    Route::get('/sell', [ProductController::class, 'create'])->name('sell');
    Route::post('/sell', [ProductController::class, 'storeListing'])->name('sell.store');
});

