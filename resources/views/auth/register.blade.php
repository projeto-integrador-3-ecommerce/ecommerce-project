@extends('layouts.storefront', ['title' => 'Criar conta'])

@section('content')
<section class="container auth-layout">
    <div class="auth-story">
        <span class="eyebrow">A experiência Dimensiona</span>
        <h2 class="auth-story__title">Sua próxima ideia começa aqui.</h2>
        <p class="auth-story__copy">Crie sua conta para comprar com mais praticidade e acompanhar seus pedidos do início à entrega.</p>
        <div class="auth-story__list">
            <div><span>01</span><p><strong>Explore o catálogo</strong>Encontre peças e soluções em impressão 3D.</p></div>
            <div><span>02</span><p><strong>Finalize sua compra</strong>Use seus dados para agilizar o pedido.</p></div>
            <div><span>03</span><p><strong>Acompanhe a entrega</strong>Consulte o andamento na sua conta.</p></div>
        </div>
        <a class="auth-story__brand" href="{{ route('storefront.home') }}"><img src="{{ asset('images/dimensiona/logo.svg') }}" alt="" width="24" height="24">Dimensiona <span>· Impressão 3D</span></a>
    </div>
    <div class="auth-panel">
        <div class="auth__head"><img src="{{ asset('images/dimensiona/logo.svg') }}" alt="" width="34" height="34"><h1>Crie sua conta</h1><p>Seus pedidos e endereços em um só lugar.</p></div>
        <div class="tabs"><a class="btn btn--ghost" href="{{ route('login') }}">Entrar</a><a class="btn btn--primary" href="{{ route('register') }}" aria-current="page">Criar conta</a></div>
        @if ($errors->any())<p class="form-error" role="alert">{{ $errors->first() }}</p>@endif
        <form action="{{ route('register.store') }}" method="POST">@csrf
            <label class="field"><span>Nome</span><input class="input" type="text" name="name" maxlength="150" value="{{ old('name') }}" autocomplete="name" placeholder="Seu nome completo" required></label>
            <label class="field"><span>E-mail</span><input class="input" type="email" name="email" maxlength="255" value="{{ old('email') }}" autocomplete="email" placeholder="voce@exemplo.com" required></label>
            <label class="field"><span>Senha</span><input class="input" type="password" name="password" minlength="8" maxlength="255" autocomplete="new-password" placeholder="Pelo menos 8 caracteres" required></label>
            <label class="field"><span>Confirme a senha</span><input class="input" type="password" name="password_confirmation" minlength="8" maxlength="255" autocomplete="new-password" placeholder="Digite a senha novamente" required></label>
            <button class="btn btn--primary btn--block" type="submit">Criar conta</button>
        </form>
        <p class="auth-panel__foot">Já tem uma conta? <a href="{{ route('login') }}">Entrar</a></p>
    </div>
</section>
@endsection
