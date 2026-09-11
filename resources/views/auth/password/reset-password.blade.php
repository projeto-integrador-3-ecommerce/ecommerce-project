@extends('layouts.app')

@section('content')

    <h1>Reset Password</h1>

    <!-- tela de trocar senha chamando a rota update -->
    <form action="{{ route('password.update') }}" method="POST">
        @csrf

        <!-- token que o laravel valida se é a pessoa mesmo -->
        <input type="hidden" name="token" value="{{ $token }}">

        <div>
            <label>Email</label>
            <input type="text" name="email" placeholder="Enter your email" value="{{ $email ?? old('email') }}" required>
        </div>
        
        <div>
            <label>New Password</label>
            <input type="password" name="password" placeholder="Enter your new password" required>
        </div>

        <div>
            <label>Confirm New Password</label>
            <input type="password" name="password_confirmation" placeholder="Confirm your new password" required>
        </div>

        <button type="submit">Reset Password</button>
    </form>

    @if($errors->any())
        <div>
            {{ $errors->first() }}
        </div>        
    @endif

@endsection