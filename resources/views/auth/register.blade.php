@extends('layouts.storefront', ['title' => 'Criar conta'])

@section('content')
<!-- conteudo que vai dentro da sessão content-->

    <section class="container auth">
        <div class="auth__head"><img src="{{ asset('images/dimensiona/logo.svg') }}" alt="" width="36" height="36"><h1>Crie sua conta</h1><p>Cadastre-se para comprar e acompanhar seus pedidos.</p></div>
        <div class="tabs"><a class="btn btn--ghost" href="{{ route('login') }}">Entrar</a><a class="btn btn--primary" href="{{ route('register') }}" aria-current="page">Criar conta</a></div>
        @if ($errors->any())<p class="form-error" role="alert">{{ $errors->first() }}</p>@endif
        <form action="{{ route('register.store') }}" method="POST">@csrf
            <label class="field"><span>Nome</span><input class="input" type="text" name="name" maxlength="150" value="{{ old('name') }}" autocomplete="name" required></label>
            <label class="field"><span>E-mail</span><input class="input" type="email" name="email" maxlength="255" value="{{ old('email') }}" autocomplete="email" required></label>
            <label class="field"><span>Senha</span><input class="input" type="password" name="password" minlength="8" maxlength="255" autocomplete="new-password" required></label>
            <label class="field"><span>Confirme a senha</span><input class="input" type="password" name="password_confirmation" minlength="8" maxlength="255" autocomplete="new-password" required></label>
            <button class="btn btn--primary btn--block" type="submit">Criar conta</button>
        </form>
    </section>
   
@endsection
