<?php

require __DIR__.'/auth.php';
require __DIR__.'/products.php';
require __DIR__.'/categories.php';

// rota teste = raíz
Route::get('/', function () {
    return redirect()->route('login');
});




