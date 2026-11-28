@extends('layouts.storefront', ['title' => 'Criar conta'])

@section('content')
<section>
    <div>
        <span>A experiência Dimensiona</span>
        <h2>Sua próxima ideia começa aqui.</h2>
        <p>Crie sua conta para comprar com mais praticidade e acompanhar seus pedidos do início à entrega.</p>
        <div>
            <div><span>01</span><p><strong>Explore o catálogo</strong>Encontre peças e soluções em impressão 3D.</p></div>
            <div><span>02</span><p><strong>Finalize sua compra</strong>Use seus dados para agilizar o pedido.</p></div>
            <div><span>03</span><p><strong>Acompanhe a entrega</strong>Consulte o andamento na sua conta.</p></div>
        </div>
        <a href="{{ route('storefront.home') }}"><img src="{{ asset('images/dimensiona/logo.svg') }}" alt="" width="24" height="24">Dimensiona <span>· Impressão 3D</span></a>
    </div>
    <div>
        <div><img src="{{ asset('images/dimensiona/logo.svg') }}" alt="" width="34" height="34"><h1>Crie sua conta</h1><p>Seus pedidos e endereços em um só lugar.</p></div>
        <div><a href="{{ route('login') }}">Entrar</a><a href="{{ route('register') }}" aria-current="page">Criar conta</a></div>
        @if ($errors->any())<p role="alert">{{ $errors->first() }}</p>@endif
        <form action="{{ route('register.store') }}" method="POST">@csrf
            <label><span>Nome</span><input type="text" name="name" maxlength="150" value="{{ old('name') }}" autocomplete="name" placeholder="Seu nome completo" required></label>
            <label><span>E-mail</span><input type="email" name="email" maxlength="255" value="{{ old('email') }}" autocomplete="email" placeholder="voce@exemplo.com" required></label>
            <label><span>Senha</span><input type="password" name="password" minlength="8" maxlength="255" autocomplete="new-password" placeholder="Pelo menos 8 caracteres" required></label>
            <label><span>Confirme a senha</span><input type="password" name="password_confirmation" minlength="8" maxlength="255" autocomplete="new-password" placeholder="Digite a senha novamente" required></label>
            <button type="submit">Criar conta</button>
        </form>
        <p>Já tem uma conta? <a href="{{ route('login') }}">Entrar</a></p>
    </div>
</section>
@endsection
