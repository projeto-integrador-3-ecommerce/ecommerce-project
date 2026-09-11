@extends('layouts.app')
<!-- herda html e css do layout -->
@section('content')

    <h1>Login</h1>

    <!-- form que envia pra rota de post login -->
    <form method="POST" action="{{ route('login.store') }}">

        @csrf

        <label for="email">Email</label>
        <input type="email" id="email" name="email">

        <label for="password">Password</label>
        <input type="password" id="password" name="password">

        <!-- botao de fazer login -->
        <button type="submit">Login</button>
        <!-- botão que manda pra tela de registrar -->
        <button><a href="/auth/register">Register</a></button>
        <!-- botão que manda pra tela de esqueceu a senha? -->
        <button><a href="/auth/password/reset">Forgot password?</a></button>

    </form>

@endsection