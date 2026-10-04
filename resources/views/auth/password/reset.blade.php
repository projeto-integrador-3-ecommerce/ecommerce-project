@extends('layouts.app')
<!-- herdando html do layouts -->
@section('content')

    <h1>Forgot Password?</h1>

    <!-- forma que envia pra rota de post que envia o email pro laravel e laravel envia pra outra rota -->
    <form method="POST" action="{{ route('password.email') }}">
        @csrf

        <div>
            <label>Email</label>
            <input type="email" name="email" maxlength="255" value="{{ old('email') }}" placeholder="Enter your email" required>
        </div>

        <button type="submit">Send password reset link</button>

    </form>

    @if($errors->any())
        <div role="alert">
            {{ $errors->first()}}
        </div>
    @endif

    <!-- exibe status se o email foi encontrado ou nao -->
    @if(session('status'))
        <div>
            {{ session('status') }}
        </div>
    @endif


@endsection