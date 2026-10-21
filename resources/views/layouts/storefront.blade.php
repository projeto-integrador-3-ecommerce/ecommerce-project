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

<aside class="sidebar" id="sidebar" aria-label="Menu principal">
    <div class="sidebar__head">
        <a href="{{ route('storefront.home') }}" class="logo">
            <img src="{{ asset('images/dimensiona/logo.svg') }}" alt="" width="22" height="22">
            <span>Dimensiona</span>
        </a>
        <button class="icon-btn js-sidebar-close" type="button" aria-label="Fechar menu">✕</button>
    </div>
    <nav class="sidebar__nav">
        <p class="sidebar__label">Navegação</p>
        <ul>
            <li><a href="{{ route('storefront.home') }}" class="{{ request()->routeIs('storefront.home') ? 'is-active' : '' }}"><span class="sidebar__text">Início</span><span class="chev">›</span></a></li>
            <li><a href="{{ route('products.index') }}" class="{{ request()->routeIs('products.index') || request()->routeIs('products.show') ? 'is-active' : '' }}"><span class="sidebar__text">Catálogo</span><span class="chev">›</span></a></li>
            <li><a href="{{ route('categories.index') }}" class="{{ request()->routeIs('categories.*') ? 'is-active' : '' }}"><span class="sidebar__text">Categorias</span><span class="chev">›</span></a></li>
            <li><a href="{{ route('storefront.novelties') }}" class="{{ request()->routeIs('storefront.novelties') ? 'is-active' : '' }}"><span class="sidebar__text">Novidades</span><span class="chev">›</span></a></li>
            <li><a href="{{ route('storefront.about') }}" class="{{ request()->routeIs('storefront.about') ? 'is-active' : '' }}"><span class="sidebar__text">Sobre</span><span class="chev">›</span></a></li>
        </ul>
        <p class="sidebar__label">Conta</p>
        <ul>
            <li><a href="{{ route('storefront.search') }}" class="{{ request()->routeIs('storefront.search') ? 'is-active' : '' }}"><span class="sidebar__text">Pesquisar</span><span class="chev">›</span></a></li>
            <li><a href="{{ auth()->check() ? route('cart.index') : route('login') }}"><span class="sidebar__text">Carrinho</span><span class="chev">›</span></a></li>
            <li><a href="{{ auth()->check() ? route('orders.index') : route('login') }}"><span class="sidebar__text">{{ auth()->check() && auth()->user()->is_admin ? 'Pedidos da loja' : 'Meus pedidos' }}</span><span class="chev">›</span></a></li>
            @guest
                <li><a href="{{ route('login') }}"><span class="sidebar__text">Entrar</span><span class="chev">›</span></a></li>
            @else
                @can('admin')
                    <li><a href="{{ route('admin.dashboard') }}" class="{{ request()->routeIs('admin.dashboard') ? 'is-active' : '' }}"><span class="sidebar__text">Painel admin</span><span class="chev">›</span></a></li>
                    <li><a href="{{ route('users.index') }}" class="{{ request()->routeIs('users.*') ? 'is-active' : '' }}"><span class="sidebar__text">Usuários cadastrados</span><span class="chev">›</span></a></li>
                @endcan
                <li><a href="{{ route('profile.edit') }}" class="{{ request()->routeIs('profile.edit') ? 'is-active' : '' }}"><span class="sidebar__text">Minha conta</span><span class="chev">›</span></a></li>
                <li><a href="{{ route('addresses.index') }}"><span class="sidebar__text">Meus endereços</span><span class="chev">›</span></a></li>
                <li>
                    <form action="{{ route('logout') }}" method="POST">@csrf
                        <button class="sidebar__text" type="submit">Sair</button>
                    </form>
                </li>
            @endguest
        </ul>
    </nav>
    <p class="sidebar__foot">© {{ date('Y') }} Dimensiona</p>
</aside>
<button class="sidebar-overlay js-sidebar-close" type="button" aria-label="Fechar menu"></button>

<div class="page">
    <div class="topbar">Frete grátis em pedidos acima de R$ 299,00</div>
    <button class="menu-btn js-sidebar-open" type="button" aria-label="Abrir menu" aria-controls="sidebar" aria-expanded="false"><span></span><span></span><span></span></button>
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
