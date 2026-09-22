<?php

use App\Http\Controllers\PaymentController;
use Illuminate\Support\Facades\Route;

Route::get('/payment', [PaymentController::class, 'index'])->name('payment.index');

Route::get('/payment/create/{order}', [PaymentController::class, 'create'])->name('payment.create');

Route::post('/payment', [PaymentController::class, 'store'])->name('payment.store');
