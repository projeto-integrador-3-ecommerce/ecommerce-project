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
            <input type="text" name="name" placeholder="Enter your name">
        </div>
        <div>
            <label>Email</label>
            <input type="email" name="email" placeholder="Enter your email">
        </div>
        <div>
            <label>Password</label>
            <input type="password" name="password" placeholder="Enter your password">
        </div>
        </div>
        <div>
            <label>Confirm Password</label>
            <input type="password" name="password_confirmation" placeholder="Confirm your password">
        </div>


        <button type="submit">Register</button>
        <!-- button que vai pra tela de logar -->
        <button><a href="/auth/login">Do you have an account? Login</a></button>
    </form>

    <!-- exibe se tiver algum erro -->
    @if($errors->any())
        <div>
            {{ $errors->first() }}
        </div>
   @endif
   
@endsection
