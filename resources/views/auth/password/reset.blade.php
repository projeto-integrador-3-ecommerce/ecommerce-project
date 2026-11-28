@extends('layouts.storefront', ['title' => 'Recuperar senha'])
@section('content')

    <section><div><img src="{{ asset('images/dimensiona/logo.svg') }}" alt="" width="36" height="36"><h1>Recuperar senha</h1><p>Enviaremos instruções para o e-mail da sua conta.</p></div>
        @if ($errors->any())<p role="alert">{{ $errors->first() }}</p>@endif
        @if (session('status'))<p role="status">{{ session('status') }}</p>@endif
        <form method="POST" action="{{ route('password.email') }}">@csrf
            <label><span>E-mail</span><input type="email" name="email" maxlength="255" value="{{ old('email') }}" autocomplete="email" required></label>
            <button type="submit">Enviar instruções</button>
        </form>
    </section>


@endsection
