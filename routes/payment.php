<?php

use App\Http\Controllers\PaymentController;
use Illuminate\Support\Facades\Route;

// exibe todos os métodos de pagamentos
Route::get('/payment', [PaymentController::class, 'index'])->name('payment.index');

// exibe a tela de preenchimento de pagamento
Route::get('/payment/create/{order}', [PaymentController::class, 'create'])->name('payment.create');

// cria o pagamento
Route::post('/payment', [PaymentController::class, 'store'])->name('payment.store');
