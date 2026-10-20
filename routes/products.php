<?php

use App\Http\Controllers\ProductController;
use Illuminate\Support\Facades\Route;

// rota que lista todos os produtos
Route::get('/products', [ProductController::class, 'index'])->name('products.index');

Route::middleware(['auth', 'can:admin'])->group(function (): void {
    Route::get('/products/create', [ProductController::class, 'create'])->name('products.create');
    Route::post('/products', [ProductController::class, 'store'])->name('products.store');
    Route::get('/products/{product}/edit', [ProductController::class, 'edit'])->name('products.edit');
    Route::put('/products/{product}', [ProductController::class, 'update'])->name('products.update');
    Route::delete('/products/{product}', [ProductController::class, 'destroy'])->name('products.destroy');
});

// rota que lista apenas um produto (através do id)
Route::get('/products/{product}', [ProductController::class, 'show'])->name('products.show');
