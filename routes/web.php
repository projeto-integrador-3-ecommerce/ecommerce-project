<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;


// rota teste = raíz
Route::get('/', function () {
    return redirect()->route('login');
});

// rota de login que exibe formulário de login
Route::get('/auth/login', function (){
    return view('auth.login');
})->name('login');

// rota login e logout
Route::post('/auth/login', [AuthController::class, 'login'])
// ->name = significa que estamos dando o nome pra rota de 'login'
->name('login.store');

Route::post('/auth/logout', [AuthController::class, 'logout'])
// middleware = só algúem que já está autenticado pode usar essa rota
// middleware pergunta = está logado? se sim deixa seguir pra essa rota
->middleware('auth')
// ->name = significa que estamos dando o nome pra rota de 'logout'
->name('logout');


Route::get('/auth/register', function () {
    return view('auth.register');
})->name('register');

Route::post('/auth/register', [AuthController::class, 'register'])
->name('register.store');
