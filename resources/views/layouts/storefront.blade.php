<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ $title ?? 'Dimensiona' }} · Dimensiona</title>
    <meta name="description" content="Dimensiona — impressão 3D FDM, SLA e MSLA com entrega para todo o Brasil.">
    <link rel="icon" href="{{ asset('images/dimensiona/logo.svg') }}" type="image/svg+xml">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body>
<a href="#conteudo">Pular para o conteúdo</a>

<aside id="sidebar" aria-label="Menu principal" aria-hidden="true" inert>
    <div>
        <a href="{{ route('storefront.home') }}"><img src="{{ asset('images/dimensiona/logo.svg') }}" alt="" width="22" height="22"><span>Dimensiona</span></a>
        <button data-sidebar-close type="button" aria-label="Fechar menu">✕</button>
    </div>
    <nav aria-label="Navegação da loja">
        <p>Loja</p>
        <ul>
            <li><a href="{{ route('storefront.home') }}" @if (request()->routeIs('storefront.home')) aria-current="page" @endif><span>Início</span><span>›</span></a></li>
            <li><a href="{{ route('products.index') }}" @if (request()->routeIs('products.*')) aria-current="page" @endif><span>Catálogo</span><span>›</span></a></li>
            <li><a href="{{ route('categories.index') }}" @if (request()->routeIs('categories.*')) aria-current="page" @endif><span>Categorias</span><span>›</span></a></li>
            <li><a href="{{ route('storefront.novelties') }}" @if (request()->routeIs('storefront.novelties')) aria-current="page" @endif><span>Novidades</span><span>›</span></a></li>
            <li><a href="{{ route('storefront.about') }}" @if (request()->routeIs('storefront.about')) aria-current="page" @endif><span>Sobre</span><span>›</span></a></li>
            <li><a href="{{ route('storefront.search') }}" @if (request()->routeIs('storefront.search')) aria-current="page" @endif><span>Pesquisar</span><span>›</span></a></li>
            @unless (auth()->check() && auth()->user()->is_admin)
                <li><a href="{{ route('cart.index') }}" @if (request()->routeIs('cart.*')) aria-current="page" @endif><span>Carrinho</span><span>›</span></a></li>
            @endunless
        </ul>
        <p>Conta</p>
        <ul>
            @guest
                <li><a href="{{ route('login') }}"><span>Entrar</span><span>›</span></a></li>
                <li><a href="{{ route('register') }}"><span>Criar conta</span><span>›</span></a></li>
            @else
                <li><a href="{{ route('profile.edit') }}" @if (request()->routeIs('profile.*')) aria-current="page" @endif><span>Minha conta</span><span>›</span></a></li>
                <li><a href="{{ route('orders.index') }}" @if (request()->routeIs('orders.index')) aria-current="page" @endif><span>{{ auth()->user()->is_admin ? 'Pedidos da loja' : 'Meus pedidos' }}</span><span>›</span></a></li>
                <li><a href="{{ route('addresses.index') }}" @if (request()->routeIs('addresses.*')) aria-current="page" @endif><span>Meus endereços</span><span>›</span></a></li>
                @can('admin')
                    <li><a href="{{ route('admin.dashboard') }}" @if (request()->routeIs('admin.dashboard')) aria-current="page" @endif><span>Painel administrativo</span><span>›</span></a></li>
                    <li><a href="{{ route('products.create') }}" @if (request()->routeIs('products.create')) aria-current="page" @endif><span>Cadastrar produto</span><span>›</span></a></li>
                    <li><a href="{{ route('categories.create') }}" @if (request()->routeIs('categories.create')) aria-current="page" @endif><span>Criar categoria</span><span>›</span></a></li>
                    <li><a href="{{ route('users.index') }}" @if (request()->routeIs('users.*')) aria-current="page" @endif><span>Usuários cadastrados</span><span>›</span></a></li>
                    <li><a href="{{ route('setting.create') }}" @if (request()->routeIs('setting.*')) aria-current="page" @endif><span>Dados da loja</span><span>›</span></a></li>
                @endcan
                <li><form action="{{ route('logout') }}" method="POST">@csrf<button type="submit">Sair</button></form></li>
            @endguest
        </ul>
    </nav>
    <p>© {{ date('Y') }} Dimensiona</p>
</aside>
<button data-sidebar-close type="button" aria-label="Fechar menu"></button>

<div>
    <div><strong>Frete grátis</strong><span>em pedidos acima de R$ 299,00</span></div>
    <header>
        <div>
        <button data-sidebar-open type="button" aria-label="Abrir menu" aria-controls="sidebar" aria-expanded="false"><span></span><span></span><span></span></button>
        <a href="{{ route('storefront.home') }}" aria-label="Dimensiona, página inicial">
            <img src="{{ asset('images/dimensiona/logo.svg') }}" alt="" width="28" height="28"><span>Dimensiona</span>
        </a>
        <nav aria-label="Atalhos da loja">
            <a href="{{ route('products.index') }}" @if (request()->routeIs('products.*')) aria-current="page" @endif>Catálogo</a>
            <a href="{{ route('categories.index') }}" @if (request()->routeIs('categories.*')) aria-current="page" @endif>Categorias</a>
            <a href="{{ route('storefront.novelties') }}" @if (request()->routeIs('storefront.novelties')) aria-current="page" @endif>Novidades</a>
            <a href="{{ route('storefront.about') }}" @if (request()->routeIs('storefront.about')) aria-current="page" @endif>Sobre</a>
        </nav>
        <form action="{{ route('storefront.search') }}" method="GET" role="search" data-async-search>
            <label for="site-search">Pesquisar produtos</label>
            <input type="search" id="site-search" name="q" value="{{ request()->routeIs('storefront.search') ? request('q') : '' }}" placeholder="Buscar produtos...">
            <button type="submit" aria-label="Buscar">
                <svg viewBox="0 0 24 24" aria-hidden="true"><circle cx="11" cy="11" r="6.5"/><path d="m16 16 4 4"/></svg>
            </button>
        </form>
        <div>
            @unless (auth()->check() && auth()->user()->is_admin)
                <a href="{{ route('cart.index') }}" aria-label="Abrir carrinho" title="Carrinho">
                    <svg viewBox="0 0 24 24" aria-hidden="true"><path d="M3 4h2l2.1 10.1a2 2 0 0 0 2 1.6h7.8a2 2 0 0 0 2-1.6L20 8H6"/><circle cx="10" cy="20" r="1"/><circle cx="17" cy="20" r="1"/></svg>
                    <span>Carrinho</span>
                    <span data-cart-count hidden aria-label="Itens no carrinho"></span>
                </a>
            @endunless
            @guest
                <a href="{{ route('login') }}">Entrar</a>
            @else
                <details data-account-menu>
                    <summary>Minha conta <span aria-hidden="true">⌄</span></summary>
                    <nav aria-label="Menu da conta">
                        <a href="{{ route('profile.edit') }}">Meu perfil</a>
                        <a href="{{ route('orders.index') }}">{{ auth()->user()->is_admin ? 'Pedidos da loja' : 'Meus pedidos' }}</a>
                        <a href="{{ route('addresses.index') }}">Meus endereços</a>
                        @can('admin')
                            <span>Administração</span>
                            <a href="{{ route('admin.dashboard') }}">Painel administrativo</a>
                            <a href="{{ route('products.create') }}">Cadastrar produto</a>
                            <a href="{{ route('categories.create') }}">Criar categoria</a>
                            <a href="{{ route('users.index') }}">Usuários cadastrados</a>
                            <a href="{{ route('setting.create') }}">Dados da loja</a>
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
            <div><p role="status">{{ session('success') }}</p></div>
        @endif
        @yield('content')
    </main>
    <footer>
        <div>
            <div><p>Dimensiona</p><p>Peças e soluções em impressão 3D para dar forma às suas ideias.</p></div>
            <div><p>Institucional</p><ul><li><a href="{{ route('storefront.about') }}">Sobre nós</a></li><li><a href="{{ route('storefront.about') }}#contato">Contato</a></li></ul></div>
            <div><p>Ajuda</p><ul><li><a href="{{ auth()->check() ? route('orders.index') : route('login') }}">Acompanhar pedido</a></li><li><a href="{{ route('storefront.search') }}">Pesquisar produtos</a></li></ul></div>
            <div><p>Conta</p><ul><li><a href="{{ auth()->check() ? route('profile.edit') : route('login') }}">Minha conta</a></li><li><a href="{{ route('addresses.index') }}">Meus endereços</a></li><li><a href="{{ route('products.index') }}">Catálogo</a></li></ul></div>
        </div>
        <div><span>© {{ date('Y') }} Dimensiona. Todos os direitos reservados.</span></div>
    </footer>
</div>
<div id="toast" role="status" aria-live="polite"></div>
@stack('scripts')
</body>
</html>
