<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;
use Illuminate\Foundation\Auth\EmailVerificationRequest;
use Illuminate\Http\Request;

use App\Http\Controllers\AuthController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\LikeController;
use App\Http\Controllers\CommentController;
use App\Http\Controllers\PurchaseController;
use App\Http\Controllers\CheckoutController;

Route::get('/', [ProductController::class, 'index'])->name('products.index');

Route::post('/logout', function () {
    Auth::logout();
    return redirect('/');
})->name('logout');

Route::get('/login', function (Request $request) {
    if ($request->filled('redirect')) {
        $target = urldecode($request->query('redirect'));
        if (strpos($target, '/') === 0) {
            session(['url.intended' => $target]);
        }
    }
    return view('auth.login');
})->name('login')->middleware(['guest']);


Route::post('/login', [AuthController::class, 'login'])
    ->name('login.store')
    ->middleware('guest');

Route::get('/item/{product}', [ProductController::class, 'getDetail'])->name('products.show');
Route::get('/item/{product}/likes', [LikeController::class, 'likes'])->name('likes.count');
Route::get('/search', [ProductController::class, 'search'])->name('search');

Route::get('/email/verify', function() {
    return view('auth.verify-notice');
})->middleware('auth')->name('verification.notice');

Route::get('/email/verify/{id}/{hash}', function(EmailVerificationRequest $request) {
    $request->fulfill();
    return redirect()->route('profile.edit', ['back' => 'products'])->with('back', 'products')->with('status', 'メール認証が完了しました');
})->middleware(['auth', 'signed'])->name('verification.verify');

Route::post('/email/verification-notification', function (Request $request) {
    if ($request->user()->hasVerifiedEmail()) {
        return redirect()->route('products.index');
    }
    $request->user()->sendEmailVerificationNotification();
    return back()->with('status', '認証メールを再送しました');
})->middleware('auth')->name('verification.send');

Route::middleware(['auth', 'verified'])->group(function () {
    Route::get('/mypage/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::post('/mypage/profile', [ProfileController::class, 'update'])->name('profile.update');

    Route::get('/mypage', [ProfileController::class, 'show'])->name('profile.show');

    Route::get('/purchase/{product}', [PurchaseController::class, 'create'])->name('purchase.create');
    Route::post('/purchase/{product}', [PurchaseController::class, 'store'])->name('purchase.store');

    Route::get('/checkout/success/{order}', [PurchaseController::class, 'success'])->name('checkout.success');
    Route::get('/checkout/cancel/{order}', [PurchaseController::class, 'cancel'])->name('checkout.cancel');

    Route::get('/purchase/address/{product}', [PurchaseController::class, 'edit'])->name('address.edit');
    Route::put('/purchase/address/{product}', [PurchaseController::class, 'update'])->name('address.update');

    Route::post('/item/{product}/mylist', [LikeController::class, 'addMylist'])->name('mylist.store');
    Route::delete('/item/{product}/mylist', [LikeController::class, 'removeMylist'])->name('mylist.destroy');

    Route::post('/item/{product}/comment', [CommentController::class, 'addComment'])->name('comment.store');

    Route::get('/sell', [ProductController::class, 'create'])->name('sell');
    Route::post('/sell', [ProductController::class, 'storeListing'])->name('sell.store');
});

