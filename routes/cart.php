<?php

use App\Http\Controllers\CartController;
use App\Http\Controllers\CartItemController;
use Illuminate\Support\Facades\Route;


Route::get('/cart', [CartController::class, 'index'])->name('cart.index');
Route::post('/cart/items', [CartItemController::class, 'store'])->name('cart-items.store');
Route::delete('/cart/items/{cartItem}', [CartItemController::class, 'destroy'])->name('cart-items.destroy');
