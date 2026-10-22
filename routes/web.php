<?php

use App\Http\Controllers\AdminController;
use App\Http\Controllers\StorefrontController;
use Illuminate\Support\Facades\Route;

require __DIR__.'/auth.php';
require __DIR__.'/products.php';
require __DIR__.'/categories.php';
require __DIR__.'/addresses.php';
require __DIR__.'/cart.php';
require __DIR__.'/orders.php';
require __DIR__.'/payment.php';
require __DIR__.'/users.php';
require __DIR__.'/settings.php';

Route::get('/admin', [AdminController::class, 'index'])
    ->middleware(['auth', 'can:admin'])
    ->name('admin.dashboard');

Route::get('/', [StorefrontController::class, 'home'])->name('storefront.home');
Route::get('/novelties', [StorefrontController::class, 'novelties'])->name('storefront.novelties');
Route::get('/search', [StorefrontController::class, 'search'])->name('storefront.search');
Route::get('/about', [StorefrontController::class, 'about'])->name('storefront.about');
