@extends('layouts.storefront', ['title' => 'Recuperar senha'])
@section('content')

    <section class="container auth"><div class="auth__head"><img src="{{ asset('images/dimensiona/logo.svg') }}" alt="" width="36" height="36"><h1>Recuperar senha</h1><p>Enviaremos instruções para o e-mail da sua conta.</p></div>
        @if ($errors->any())<p class="form-error" role="alert">{{ $errors->first() }}</p>@endif
        @if (session('status'))<p class="status-pill" role="status">{{ session('status') }}</p>@endif
        <form method="POST" action="{{ route('password.email') }}">@csrf
            <label class="field"><span>E-mail</span><input class="input" type="email" name="email" maxlength="255" value="{{ old('email') }}" autocomplete="email" required></label>
            <button class="btn btn--primary btn--block" type="submit">Enviar instruções</button>
        </form>
    </section>


@endsection
