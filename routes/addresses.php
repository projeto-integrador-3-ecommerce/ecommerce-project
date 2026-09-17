<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AddressController;

Route::get('/addresses', [AddressController::class, 'index'])->name('addresses.index');

Route::get('/addresses/create', [AddressController::class, 'create'])->name('addresses.create');

Route::post('/addresses', [AddressController::class, 'store'])->name('addresses.store');

Route::get('/addresses/{address}/edit', [AddressController::class, 'edit'])->name('addresses.edit');

Route::put('/addresses/{address}', [AddressController::class, 'update'])->name('addresses.update');

Route::delete('/addresses/{address}', [AddressController::class, 'destroy'])->name('addresses.destroy');