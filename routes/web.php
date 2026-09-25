<?php

require __DIR__.'/auth.php';
require __DIR__.'/products.php';
require __DIR__.'/categories.php';
require __DIR__.'/addresses.php';
require __DIR__.'/cart.php';
require __DIR__.'/orders.php';
require __DIR__.'/payment.php';
require __DIR__.'/users.php';

// rota teste = raíz
Route::get('/', function () {
    return redirect()->route('login');
});
