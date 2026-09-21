<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\CategoryController;

// rota que lista todas as categorias
Route::get('/categories', [CategoryController::class, 'index'])->name('categories.index');

// rota que exibe o forms de criar produto
Route::get('/categories/create', [CategoryController::class, 'create'])->name('categories.create');

// rota que cria o produto e envia pro banco
Route::post('/categories', [CategoryController::class, 'store'])->name('categories.store');

// rota que exclui uma categoria
Route::post('/categories/{category}', [CategoryController::class, 'destroy'])->name('categories.destroy');