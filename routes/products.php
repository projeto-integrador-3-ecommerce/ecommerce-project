<?php

use App\Http\Controllers\ProductController;
use Illuminate\Support\Facades\Route;

// rota que lista todos os produtos
Route::get('/products', [ProductController::class, 'index'])->name('products.index');

// rota que exibe o forms de criar produtos
Route::get('/products/create', [ProductController::class, 'create'])->name('products.create');

// rota que cria produtos e envia pro banco
Route::post('/products', [ProductController::class, 'store'])->name('products.store');

// rota que lista apenas um produto (através do id)
Route::get('/products/{product}', [ProductController::class, 'show'])->name('products.show');

// rota que exibe o forms de edição de produto
Route::get('products/{product}/edit', [ProductController::class, 'edit'])->name('products.edit');

// rota de atualizar produtos no banco
Route::put('/products/{product}', [ProductController::class, 'update'])->name('products.update');

// rota que exclui um produto
Route::delete('/products/{product}', [ProductController::class, 'destroy'])->name('products.destroy');
