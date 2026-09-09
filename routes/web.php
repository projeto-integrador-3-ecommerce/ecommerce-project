<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;

<<<<<<< Updated upstream
Route::get('/', function () {
    return view('welcome');
});
=======

// rota teste = raíz
Route::get('/', function () {
    return response()->json([
        'message' => 'Ok. Funcionando.',
    ]);
});

// rota de login que exibe formulário de login
Route::get('/login', function (){
    return view('login');
});

// rota login e logout
Route::post('/login', [AuthController::class, 'login'])
// ->name = significa que estamos dando o nome pra rota de 'login'
->name('login.store');

Route::post('/logout', [AuthController::class, 'logout'])
// middleware = só algúem que já está autenticado pode usar essa rota
// middleware pergunta = está logado? se sim deixa seguir pra essa rota
->middleware('auth')
// ->name = significa que estamos dando o nome pra rota de 'logout'
->name('logout');


>>>>>>> Stashed changes
