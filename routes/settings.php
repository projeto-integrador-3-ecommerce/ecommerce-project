<?php

use App\Http\Controllers\SettingController;
use Illuminate\Support\Facades\Route;

Route::get('/settings/{setting}/edit', [SettingController::class, 'edit'])->name('setting.edit');
Route::post('/settings', [SettingController::class, 'store'])->name('setting.store');
Route::get('/settings/create', [SettingController::class, 'create'])->name('setting.create');
Route::post('/settings/{setting}/update', [SettingController::class, 'update'])->name('setting.update');