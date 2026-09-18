<?php

require __DIR__.'/auth.php';
require __DIR__.'/products.php';
require __DIR__.'/categories.php';
require __DIR__.'/addresses.php';
require __DIR__.'/cart.php';

Route::get('/', function () {
    return view('welcome');
});
