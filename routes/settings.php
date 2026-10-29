<?php

use App\Http\Controllers\SettingController;
use Illuminate\Support\Facades\Route;

Route::middleware(['auth', 'can:admin'])->group(function (): void {
    Route::get('/settings/{setting}/edit', [SettingController::class, 'edit'])->name('setting.edit');
    Route::post('/settings', [SettingController::class, 'store'])->name('setting.store');
    Route::get('/settings/create', [SettingController::class, 'create'])->name('setting.create');
    Route::put('/settings/{setting}', [SettingController::class, 'update'])->name('setting.update');
});
