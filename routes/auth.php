<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;

// rota de login que exibe formulário de login
Route::get('/auth/login', function (){
    return view('auth.login');
})->name('login');

// rota de login que faz o post e valida no banco
Route::post('/auth/login', [AuthController::class, 'login'])
// ->name = significa que estamos dando o nome pra rota de 'login'
->name('login.store');

// rota que faz logout
Route::post('/auth/logout', [AuthController::class, 'logout'])
// middleware = só algúem que já está autenticado pode usar essa rota
// middleware pergunta = está logado? se sim deixa seguir pra essa rota
->middleware('auth')
// ->name = significa que estamos dando o nome pra rota de 'logout'
->name('logout');

// rota que exibe a view com o forms de registro de conta
Route::get('/auth/register', function () {
    return view('auth.register');
})->name('register');

// rota que registra a conta no banco
Route::post('/auth/register', [AuthController::class, 'register'])
->name('register.store');

// retorna a view "Esqueci minha senha"
Route::get('/auth/password/reset', function () {
    return view('auth.password.reset');
})->name('password.request');

// recebe o email e envia o link para o email cadastrado
Route::post('/auth/password/reset', [AuthController::class, 'reset'])
    ->name('password.email');

// exibe a view de nova senha
Route::get('/auth/password/reset/{token}', function (string $token) {
    return view('auth.password.reset-password', [
        'token' => $token,
        'email' => request('email'),
    ]);
})->name('password.reset');

// recebe a nova senha e faz o update
Route::post('/auth/password/update', [AuthController::class, 'resetPassword'])->name('password.update');