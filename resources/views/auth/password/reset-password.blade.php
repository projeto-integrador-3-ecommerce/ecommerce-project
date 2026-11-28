@extends('layouts.storefront', ['title' => 'Redefinir senha'])

@section('content')

    <section><div><img src="{{ asset('images/dimensiona/logo.svg') }}" alt="" width="36" height="36"><h1>Redefinir senha</h1><p>Escolha uma nova senha para sua conta.</p></div>
        @if ($errors->any())<p role="alert">{{ $errors->first() }}</p>@endif
        <form action="{{ route('password.update') }}" method="POST">@csrf
            <input type="hidden" name="token" value="{{ $token }}">
            <label><span>E-mail</span><input type="email" name="email" maxlength="255" value="{{ $email ?? old('email') }}" autocomplete="email" required></label>
            <label><span>Nova senha</span><input type="password" name="password" minlength="8" maxlength="255" autocomplete="new-password" required></label>
            <label><span>Confirme a nova senha</span><input type="password" name="password_confirmation" minlength="8" maxlength="255" autocomplete="new-password" required></label>
            <button type="submit">Salvar nova senha</button>
        </form>
    </section>

@endsection
