@extends('layouts.app')
<!-- extends = herdando layout (html e css) -->

@section('content')
<!-- conteudo que vai dentro da sessão content-->

    <h1>Create Account</h1>
    <!-- form envia pra rota de registrar conta -->
    <form action="{{ route('register.store') }}" method="POST">
        @csrf

        <div>
            <label>Name</label>
            <input type="text" name="name" maxlength="150" value="{{ old('name') }}" placeholder="Enter your name" required>
        </div>
        <div>
            <label>Email</label>
            <input type="email" name="email" maxlength="255" value="{{ old('email') }}" placeholder="Enter your email" required>
        </div>
        <div>
            <label>Password</label>
            <input type="password" name="password" minlength="8" maxlength="255" placeholder="Enter your password" required>
        </div>
        <div>
            <label>Confirm Password</label>
            <input type="password" name="password_confirmation" minlength="8" maxlength="255" placeholder="Confirm your password" required>
        </div>


        <button type="submit">Register</button>
        <!-- button que vai pra tela de logar -->
        <button><a href="/auth/login">Do you have an account? Login</a></button>
    </form>

    <!-- exibe se tiver algum erro -->
    @if($errors->any())
        <div role="alert">
            {{ $errors->first() }}
        </div>
   @endif
   
@endsection
