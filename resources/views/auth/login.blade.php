@extends('layouts.app')
<!-- herda html e css do layout -->
@section('content')

    <h1>Login</h1>

    @if($errors->any())
        <div role="alert" aria-live="polite">
            <p>{{ $errors->first() }}</p>
        </div>
    @endif

    <!-- form que envia pra rota de post login -->
    <form class="login-form" method="POST" action="{{ route('login.store') }}">

        @csrf

        <label class="login-title" for="email">Email</label>
        <input type="email" id="email" name="email" maxlength="255" value="{{ old('email') }}" required>

        <label class="login-title" for="password">Password</label>
        <input type="password" id="password" name="password" maxlength="255" required>

        <!-- botao de fazer login -->
        <button class="btn-login" type="submit">Login</button>
        <!-- botão que manda pra tela de registrar -->
        <button class="btn-login"><a href="/auth/register">Register</a></button>
        <!-- botão que manda pra tela de esqueceu a senha? -->
        <button class="btn-login"><a href="/auth/password/reset">Forgot password?</a></button>

    </form>

@endsection