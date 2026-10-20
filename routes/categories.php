<?php

use App\Http\Controllers\CategoryController;
use Illuminate\Support\Facades\Route;

// rota que lista todas as categorias
Route::get('/categories', [CategoryController::class, 'index'])->name('categories.index');

Route::middleware(['auth', 'can:admin'])->group(function (): void {
    Route::get('/categories/create', [CategoryController::class, 'create'])->name('categories.create');
    Route::post('/categories', [CategoryController::class, 'store'])->name('categories.store');
    Route::delete('/categories/{category}', [CategoryController::class, 'destroy'])->name('categories.destroy');
});
