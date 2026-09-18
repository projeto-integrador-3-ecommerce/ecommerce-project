<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\CategoryController;

// rota que lista todas as categorias
Route::get('/categories', [CategoryController::class, 'index'])->name('categories.index');

// rota que exibe o forms de criar produto
Route::get('/categories/create', [CategoryController::class, 'create'])->name('categories.create');

// rota que cria o produto e envia pro banco
Route::post('/categories', [CategoryController::class, 'store'])->name('categories.store');

// rota que exibe uma unica categoria atraves do id
Route::get('/categories/{category}', [CategoryController::class, 'show'])->name('categories.show');

// rota que retorna a view com o forms pra editar uma categoria
Route::get('/categories/{category}/edit', [CategoryController::class, 'edit'])->name('categories.edit');

// rota que atualiza a categoria no banco
Route::post('/categories/{category}', [CategoryController::class, 'update'])->name('categories.update');

// rota que exclui uma categoria
Route::post('/categories/{category}', [CategoryController::class, 'destroy'])->name('categories.destroy');