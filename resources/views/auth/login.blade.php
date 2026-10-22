@extends('layouts.storefront', ['title' => 'Entrar'])
@section('content')
<section class="container auth-layout">
    <div class="auth-story">
        <span class="eyebrow">A experiência Dimensiona</span>
        <h2 class="auth-story__title">Suas ideias começam aqui.</h2>
        <p class="auth-story__copy">Acesse sua conta para acompanhar cada etapa da sua compra e deixar seus dados sempre à mão.</p>
        <div class="auth-story__list">
            <div><span>01</span><p><strong>Seus pedidos</strong>Veja atualizações e detalhes em um só lugar.</p></div>
            <div><span>02</span><p><strong>Seus endereços</strong>Guarde onde quer receber suas peças.</p></div>
            <div><span>03</span><p><strong>Mais praticidade</strong>Continue de onde parou na próxima visita.</p></div>
        </div>
        <a class="auth-story__brand" href="{{ route('storefront.home') }}"><img src="{{ asset('images/dimensiona/logo.svg') }}" alt="" width="24" height="24">Dimensiona <span>· Impressão 3D</span></a>
    </div>
    <div class="auth-panel">
        <div class="auth__head"><img src="{{ asset('images/dimensiona/logo.svg') }}" alt="" width="34" height="34"><h1>Acesse sua conta</h1><p>Que bom ter você de volta.</p></div>
        <div class="tabs"><a class="btn btn--primary" href="{{ route('login') }}" aria-current="page">Entrar</a><a class="btn btn--ghost" href="{{ route('register') }}">Criar conta</a></div>
        @if ($errors->any())<p class="form-error" role="alert" aria-live="polite">{{ $errors->first() }}</p>@endif
        <form method="POST" action="{{ route('login.store') }}">@csrf
            <label class="field"><span>E-mail</span><input class="input" type="email" name="email" maxlength="255" value="{{ old('email') }}" autocomplete="email" placeholder="voce@exemplo.com" required></label>
            <label class="field"><span>Senha</span><input class="input" type="password" name="password" maxlength="255" autocomplete="current-password" placeholder="Digite sua senha" required></label>
            <a class="forgot" href="{{ route('password.request') }}">Esqueceu a senha?</a>
            <button class="btn btn--primary btn--block" type="submit">Entrar</button>
        </form>
        <p class="auth-panel__foot">Ainda não tem conta? <a href="{{ route('register') }}">Cadastre-se</a></p>
    </div>
</section>
@endsection
