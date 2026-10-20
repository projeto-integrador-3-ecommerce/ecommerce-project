<?php

use App\Http\Controllers\MercadoPagoWebhookController;
use App\Http\Controllers\PaymentController;
use Illuminate\Foundation\Http\Middleware\PreventRequestForgery;
use Illuminate\Foundation\Http\Middleware\ValidateCsrfToken;
use Illuminate\Support\Facades\Route;

Route::post('/webhooks/mercado-pago', MercadoPagoWebhookController::class)
    ->withoutMiddleware([PreventRequestForgery::class, ValidateCsrfToken::class])
    ->name('mercadopago.webhook');

Route::middleware('auth')->group(function (): void {
    Route::get('/payment', [PaymentController::class, 'index'])
        ->middleware('can:admin')
        ->name('payment.index');

    Route::get('/payment/create/{order}', [PaymentController::class, 'create'])->name('payment.create');
    Route::post('/payment/{order}', [PaymentController::class, 'store'])->name('payment.store');
    Route::get('/payment/return/{order}', [PaymentController::class, 'returnFromCheckout'])->name('payment.return');

});
