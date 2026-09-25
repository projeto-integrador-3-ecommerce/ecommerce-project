<?php

use App\Http\Controllers\UserController;
use Illuminate\Support\Facades\Route;

Route::get('/users', [UserController::class, 'index'])->name('users.index');
Route::delete('/users/{user}/delete', [UserController::class, 'destroy'])->name('users.destroy');