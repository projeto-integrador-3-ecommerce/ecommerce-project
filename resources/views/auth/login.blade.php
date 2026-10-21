@extends('layouts.storefront', ['title' => 'Entrar'])
@section('content')

    <section class="container auth">
        <div class="auth__head"><img src="{{ asset('images/dimensiona/logo.svg') }}" alt="" width="36" height="36"><h1>Bem-vinda de volta</h1><p>Acesse sua conta Dimensiona.</p></div>
        <div class="tabs"><a class="btn btn--primary" href="{{ route('login') }}" aria-current="page">Entrar</a><a class="btn btn--ghost" href="{{ route('register') }}">Criar conta</a></div>
        @if ($errors->any())<p class="form-error" role="alert" aria-live="polite">{{ $errors->first() }}</p>@endif
        <form method="POST" action="{{ route('login.store') }}">@csrf
            <label class="field"><span>E-mail</span><input class="input" type="email" name="email" maxlength="255" value="{{ old('email') }}" autocomplete="email" required></label>
            <label class="field"><span>Senha</span><input class="input" type="password" name="password" maxlength="255" autocomplete="current-password" required></label>
            <a class="forgot" href="{{ route('password.request') }}">Esqueceu a senha?</a>
            <button class="btn btn--primary btn--block" type="submit">Entrar</button>
        </form>
    </section>

@endsection
