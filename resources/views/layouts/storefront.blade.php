<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ $title ?? 'Dimensiona' }} · Dimensiona</title>
    <meta name="description" content="Dimensiona — impressão 3D FDM, SLA e MSLA com entrega para todo o Brasil.">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Outfit:wght@300;400;500;600;700&family=Newsreader:opsz,wght@6..72,400;6..72,500&display=swap" rel="stylesheet">
    <link rel="icon" href="{{ asset('images/dimensiona/logo.svg') }}" type="image/svg+xml">
    @vite(['resources/css/storefront.css', 'resources/js/storefront.js'])
</head>
<body>
<a class="skip-link" href="#conteudo">Pular para o conteúdo</a>

<aside class="sidebar" id="sidebar" aria-label="Menu principal" aria-hidden="true" inert>
    <div class="sidebar__head">
        <a href="{{ route('storefront.home') }}" class="logo"><img src="{{ asset('images/dimensiona/logo.svg') }}" alt="" width="22" height="22"><span>Dimensiona</span></a>
        <button class="icon-btn js-sidebar-close" type="button" aria-label="Fechar menu">✕</button>
    </div>
    <nav class="sidebar__nav" aria-label="Navegação da loja">
        <p class="sidebar__label">Loja</p>
        <ul>
            <li><a href="{{ route('storefront.home') }}" class="{{ request()->routeIs('storefront.home') ? 'is-active' : '' }}"><span class="sidebar__text">Início</span><span class="chev">›</span></a></li>
            <li><a href="{{ route('products.index') }}" class="{{ request()->routeIs('products.*') ? 'is-active' : '' }}"><span class="sidebar__text">Catálogo</span><span class="chev">›</span></a></li>
            <li><a href="{{ route('categories.index') }}" class="{{ request()->routeIs('categories.*') ? 'is-active' : '' }}"><span class="sidebar__text">Categorias</span><span class="chev">›</span></a></li>
            <li><a href="{{ route('storefront.novelties') }}" class="{{ request()->routeIs('storefront.novelties') ? 'is-active' : '' }}"><span class="sidebar__text">Novidades</span><span class="chev">›</span></a></li>
            <li><a href="{{ route('storefront.about') }}" class="{{ request()->routeIs('storefront.about') ? 'is-active' : '' }}"><span class="sidebar__text">Sobre</span><span class="chev">›</span></a></li>
            <li><a href="{{ route('storefront.search') }}" class="{{ request()->routeIs('storefront.search') ? 'is-active' : '' }}"><span class="sidebar__text">Pesquisar</span><span class="chev">›</span></a></li>
            <li><a href="{{ route('cart.index') }}" class="{{ request()->routeIs('cart.*') ? 'is-active' : '' }}"><span class="sidebar__text">Carrinho</span><span class="chev">›</span></a></li>
        </ul>
        <p class="sidebar__label">Conta</p>
        <ul>
            @guest
                <li><a href="{{ route('login') }}"><span class="sidebar__text">Entrar</span><span class="chev">›</span></a></li>
                <li><a href="{{ route('register') }}"><span class="sidebar__text">Criar conta</span><span class="chev">›</span></a></li>
            @else
                <li><a href="{{ route('profile.edit') }}" class="{{ request()->routeIs('profile.*') ? 'is-active' : '' }}"><span class="sidebar__text">Minha conta</span><span class="chev">›</span></a></li>
                <li><a href="{{ route('orders.index') }}" class="{{ request()->routeIs('orders.index') ? 'is-active' : '' }}"><span class="sidebar__text">{{ auth()->user()->is_admin ? 'Pedidos da loja' : 'Meus pedidos' }}</span><span class="chev">›</span></a></li>
                <li><a href="{{ route('addresses.index') }}" class="{{ request()->routeIs('addresses.*') ? 'is-active' : '' }}"><span class="sidebar__text">Meus endereços</span><span class="chev">›</span></a></li>
                @can('admin')
                    <li><a href="{{ route('admin.dashboard') }}" class="{{ request()->routeIs('admin.dashboard') ? 'is-active' : '' }}"><span class="sidebar__text">Painel administrativo</span><span class="chev">›</span></a></li>
                    <li><a href="{{ route('users.index') }}" class="{{ request()->routeIs('users.*') ? 'is-active' : '' }}"><span class="sidebar__text">Usuários cadastrados</span><span class="chev">›</span></a></li>
                @endcan
                <li><form action="{{ route('logout') }}" method="POST">@csrf<button class="sidebar__text" type="submit">Sair</button></form></li>
            @endguest
        </ul>
    </nav>
    <p class="sidebar__foot">© {{ date('Y') }} Dimensiona</p>
</aside>
<button class="sidebar-overlay js-sidebar-close" type="button" aria-label="Fechar menu"></button>

<div class="page">
    <div class="topbar"><strong>Frete grátis</strong><span>em pedidos acima de R$ 299,00</span></div>
    <header class="site-header">
        <div class="site-header__inner container">
        <button class="menu-btn js-sidebar-open" type="button" aria-label="Abrir menu" aria-controls="sidebar" aria-expanded="false"><span></span><span></span><span></span></button>
        <a href="{{ route('storefront.home') }}" class="site-header__brand" aria-label="Dimensiona, página inicial">
            <img src="{{ asset('images/dimensiona/logo.svg') }}" alt="" width="28" height="28"><span>Dimensiona</span>
        </a>
        <nav class="site-nav" aria-label="Atalhos da loja">
            <a href="{{ route('products.index') }}" class="{{ request()->routeIs('products.*') ? 'is-active' : '' }}">Catálogo</a>
            <a href="{{ route('categories.index') }}" class="{{ request()->routeIs('categories.*') ? 'is-active' : '' }}">Categorias</a>
            <a href="{{ route('storefront.novelties') }}" class="{{ request()->routeIs('storefront.novelties') ? 'is-active' : '' }}">Novidades</a>
            <a href="{{ route('storefront.about') }}" class="{{ request()->routeIs('storefront.about') ? 'is-active' : '' }}">Sobre</a>
        </nav>
        <form class="site-search" action="{{ route('storefront.search') }}" method="GET" role="search" data-async-search>
            <label class="sr-only" for="site-search">Pesquisar produtos</label>
            <input class="input" type="search" id="site-search" name="q" value="{{ request()->routeIs('storefront.search') ? request('q') : '' }}" placeholder="Buscar produtos...">
            <button class="site-search__button" type="submit" aria-label="Buscar">
                <svg viewBox="0 0 24 24" aria-hidden="true"><circle cx="11" cy="11" r="6.5"/><path d="m16 16 4 4"/></svg>
            </button>
        </form>
        <div class="site-actions">
            <a class="site-actions__cart" href="{{ route('cart.index') }}" aria-label="Abrir carrinho" title="Carrinho">
                <svg viewBox="0 0 24 24" aria-hidden="true"><path d="M3 4h2l2.1 10.1a2 2 0 0 0 2 1.6h7.8a2 2 0 0 0 2-1.6L20 8H6"/><circle cx="10" cy="20" r="1"/><circle cx="17" cy="20" r="1"/></svg>
                <span>Carrinho</span>
                <span class="cart-count" data-cart-count hidden aria-label="Itens no carrinho"></span>
            </a>
            @guest
                <a class="site-actions__account" href="{{ route('login') }}">Entrar</a>
            @else
                <details class="account-menu">
                    <summary class="site-actions__account">Minha conta <span aria-hidden="true">⌄</span></summary>
                    <nav class="account-menu__panel" aria-label="Menu da conta">
                        <a href="{{ route('profile.edit') }}">Meu perfil</a>
                        <a href="{{ route('orders.index') }}">{{ auth()->user()->is_admin ? 'Pedidos da loja' : 'Meus pedidos' }}</a>
                        <a href="{{ route('addresses.index') }}">Meus endereços</a>
                        @can('admin')
                            <span class="account-menu__divider">Administração</span>
                            <a href="{{ route('admin.dashboard') }}">Painel administrativo</a>
                            <a href="{{ route('users.index') }}">Usuários cadastrados</a>
                        @endcan
                        <form action="{{ route('logout') }}" method="POST">@csrf<button type="submit">Sair da conta</button></form>
                    </nav>
                </details>
            @endguest
        </div>
        </div>
    </header>
    <main id="conteudo">
        @if (session('success'))
            <div class="container"><p class="status-pill" role="status">{{ session('success') }}</p></div>
        @endif
        @yield('content')
    </main>
    <footer class="footer">
        <div class="footer__grid">
            <div><p class="footer__brand">Dimensiona</p><p class="footer__text">Peças e soluções em impressão 3D para dar forma às suas ideias.</p></div>
            <div><p class="footer__title">Institucional</p><ul><li><a href="{{ route('storefront.about') }}">Sobre nós</a></li><li><a href="{{ route('storefront.about') }}#contato">Contato</a></li></ul></div>
            <div><p class="footer__title">Ajuda</p><ul><li><a href="{{ auth()->check() ? route('orders.index') : route('login') }}">Acompanhar pedido</a></li><li><a href="{{ route('storefront.search') }}">Pesquisar produtos</a></li></ul></div>
            <div><p class="footer__title">Conta</p><ul><li><a href="{{ auth()->check() ? route('profile.edit') : route('login') }}">Minha conta</a></li><li><a href="{{ route('addresses.index') }}">Meus endereços</a></li><li><a href="{{ route('products.index') }}">Catálogo</a></li></ul></div>
        </div>
        <div class="footer__bottom"><span>© {{ date('Y') }} Dimensiona. Todos os direitos reservados.</span></div>
    </footer>
</div>
<div class="toast" id="toast" role="status" aria-live="polite"></div>
</body>
</html>
