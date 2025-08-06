<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ProductController;


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

Route::get('/', [ProductController::class, 'index']);

Route::post('/logout', function () {
    Auth::logout();
    return redirect('/');
})->name('logout');

Route::middleware('auth')->group(function () {
    Route::get('/mypage/profile', [ProductController::class, 'show']);
    Route::post('/mypage/profile', [ProductController::class, 'edit']);
    Route::get('/mypage', [ProductController::class, 'mypageShow']);
});