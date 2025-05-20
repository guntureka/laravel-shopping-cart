<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\DiscountController;
use App\Http\Controllers\CartController;



Route::get('/', function () {
    return view('welcome');
});

Route::resource('products', ProductController::class);

Route::resource('discounts', DiscountController::class);

Route::resource('carts', CartController::class);

Route::post('carts/discount', [CartController::class, 'applyDiscount'])->name('carts.applyDiscount');
Route::post('/cart/discount/remove', [CartController::class, 'removeDiscount'])->name('carts.removeDiscount');
