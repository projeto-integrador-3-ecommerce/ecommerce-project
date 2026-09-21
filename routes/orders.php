<?php

use App\Http\Controllers\AddressController;
use App\Http\Controllers\OrderController;
use Illuminate\Support\Facades\Route;

Route::post('/orders', [OrderController::class, 'store'])->name('orders.store');
Route::get('/orders/{order}', [OrderController::class, 'show'])->name('orders.show');
Route::delete('/orders/{order}', [OrderController::class, 'destroy'])->name('orders.destroy');
Route::get('/orders/{order}/addresses', [AddressController::class, 'index'])->name('orders.addresses');
Route::post('/orders/{order}/addresses', [OrderController::class, 'address'])->name('orders.address');
