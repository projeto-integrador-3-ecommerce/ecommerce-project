<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ $title ?? 'Dimensiona' }} · Dimensiona</title>
    <meta name="description" content="Dimensiona — impressão 3D para todo o Brasil, com peças feitas sob medida e entrega acompanhada.">
    <link rel="icon" href="{{ asset('images/dimensiona/logo.svg') }}" type="image/svg+xml">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="group/body min-h-screen bg-bg font-sans text-navy antialiased">
@php
    $navCategories = isset($categories) && $categories->isNotEmpty()
        ? $categories->take(6)
        : \App\Models\Category::query()->withCount('products')->orderBy('name')->limit(6)->get();
    $promoValue = config('storefront.promo_value', 'R$ 10');
    $promoMinimum = config('storefront.promo_minimum', 'R$ 199,00');
@endphp
<a href="#conteudo" class="sr-only focus:not-sr-only focus:fixed focus:top-3 focus:left-3 focus:z-[70] focus:min-h-11 focus:rounded-full focus:bg-navy focus:px-5 focus:py-2 focus:text-sm focus:font-semibold focus:text-white focus:outline-none focus-visible:ring-2 focus-visible:ring-blue">Pular para o conteúdo</a>

<header class="sticky top-0 z-30 border-b border-aqua bg-bg/95">
    <div class="mx-auto flex max-w-7xl flex-wrap items-center gap-x-3 gap-y-2 px-4 py-3 sm:px-6 lg:px-8">
        <div class="relative">
            <button data-sidebar-open type="button" aria-label="Abrir menu de categorias" aria-controls="sidebar" aria-expanded="false"
                    class="inline-flex min-h-12 shrink-0 items-center gap-2 rounded-full px-2 transition hover:bg-aqua/40 focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-blue sm:px-3">
                <span class="grid size-8 place-items-center rounded-full bg-navy text-white">
                    <svg class="size-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><rect x="3" y="3" width="7" height="7" rx="1"/><rect x="14" y="3" width="7" height="7" rx="1"/><rect x="14" y="14" width="7" height="7" rx="1"/><rect x="3" y="14" width="7" height="7" rx="1"/></svg>
                </span>
                <span class="hidden text-sm font-semibold sm:inline">Categorias</span>
                <svg class="hidden size-4 transition-transform duration-150 group-data-[menu-open]/body:rotate-180 motion-reduce:transition-none sm:block" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="m6 9 6 6 6-6"/></svg>
            </button>
            <button data-sidebar-close type="button" tabindex="-1" aria-label="Fechar categorias"
                    class="pointer-events-none fixed inset-0 z-40 bg-transparent focus:outline-none group-data-[menu-open]/body:pointer-events-auto"></button>
            <nav id="sidebar" aria-label="Categorias" aria-hidden="true" inert
                 class="invisible pointer-events-none absolute top-full left-0 z-50 mt-2 max-h-[60vh] w-72 max-w-[calc(100vw-2rem)] origin-top-left -translate-y-1 overflow-y-auto rounded-2xl border border-aqua bg-white p-2 text-navy opacity-0 shadow-[0_12px_32px_-8px_rgba(1,15,55,0.25)] transition duration-150 ease-out group-data-[menu-open]/body:visible group-data-[menu-open]/body:pointer-events-auto group-data-[menu-open]/body:translate-y-0 group-data-[menu-open]/body:opacity-100 motion-reduce:transition-none">
                <ul class="space-y-0.5">
                    <li>
                        <a href="{{ route('storefront.novelties') }}" @if (request()->routeIs('storefront.novelties')) aria-current="page" @endif class="group/cat-item flex min-h-11 items-center justify-between rounded-xl px-3 py-2.5 text-sm font-medium text-navy transition hover:bg-bg focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-blue aria-[current=page]:bg-bg">
                            <span>Novidades</span>
                            <svg class="size-4 shrink-0 text-muted transition group-hover/cat-item:text-navy group-focus-visible/cat-item:text-navy" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="m9 18 6-6-6-6"/></svg>
                        </a>
                    </li>
                    @forelse ($navCategories as $navCategory)
                        <li>
                            <a href="{{ route('products.index', ['category' => $navCategory->id]) }}" class="group/cat-item flex min-h-11 items-center justify-between rounded-xl px-3 py-2.5 text-sm font-medium text-navy transition hover:bg-bg focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-blue">
                                <span class="truncate">{{ $navCategory->name }}</span>
                                <svg class="size-4 shrink-0 text-muted transition group-hover/cat-item:text-navy group-focus-visible/cat-item:text-navy" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="m9 18 6-6-6-6"/></svg>
                            </a>
                        </li>
                    @empty
                        <li class="px-3 py-2.5 text-sm text-muted">Nenhuma categoria ainda</li>
                    @endforelse
                </ul>
                <div class="mt-1 border-t border-aqua pt-1">
                    <a href="{{ route('products.index') }}" class="group/cat-item flex min-h-11 items-center justify-between rounded-xl px-3 py-2.5 text-sm font-semibold text-navy transition hover:bg-bg focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-blue">
                        <span>Ver todo o catálogo</span>
                        <svg class="size-4 shrink-0 text-muted transition group-hover/cat-item:text-navy group-focus-visible/cat-item:text-navy" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="m9 18 6-6-6-6"/></svg>
                    </a>
                </div>
                <div class="mt-1 border-t border-aqua pt-2 md:hidden">
                    <p class="px-3 pb-1 text-[11px] font-semibold tracking-[0.14em] text-muted uppercase">Loja</p>
                    <ul class="space-y-0.5">
                        <li>
                            <a href="{{ route('storefront.home') }}" @if (request()->routeIs('storefront.home')) aria-current="page" @endif class="group/cat-item flex min-h-11 items-center justify-between rounded-xl px-3 py-2.5 text-sm font-medium text-navy transition hover:bg-bg focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-blue aria-[current=page]:bg-bg">
                                <span>Início</span>
                                <svg class="size-4 shrink-0 text-muted transition group-hover/cat-item:text-navy group-focus-visible/cat-item:text-navy" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="m9 18 6-6-6-6"/></svg>
                            </a>
                        </li>
                        <li>
                            <a href="{{ route('products.index') }}" @if (request()->routeIs('products.*')) aria-current="page" @endif class="group/cat-item flex min-h-11 items-center justify-between rounded-xl px-3 py-2.5 text-sm font-medium text-navy transition hover:bg-bg focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-blue aria-[current=page]:bg-bg">
                                <span>Catálogo</span>
                                <svg class="size-4 shrink-0 text-muted transition group-hover/cat-item:text-navy group-focus-visible/cat-item:text-navy" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="m9 18 6-6-6-6"/></svg>
                            </a>
                        </li>
                        <li>
                            <a href="{{ route('storefront.about') }}" @if (request()->routeIs('storefront.about')) aria-current="page" @endif class="group/cat-item flex min-h-11 items-center justify-between rounded-xl px-3 py-2.5 text-sm font-medium text-navy transition hover:bg-bg focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-blue aria-[current=page]:bg-bg">
                                <span>Sobre</span>
                                <svg class="size-4 shrink-0 text-muted transition group-hover/cat-item:text-navy group-focus-visible/cat-item:text-navy" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="m9 18 6-6-6-6"/></svg>
                            </a>
                        </li>
                    </ul>
                </div>
            </nav>
        </div>
        <a href="{{ route('storefront.home') }}" aria-label="Dimensiona, página inicial" class="inline-flex min-h-12 shrink-0 items-center gap-2 rounded-full focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-blue">
            <x-ui.logo size="md" />
            <span class="hidden text-base font-semibold lg:inline">Dimensiona</span>
        </a>
        <nav aria-label="Atalhos da loja" class="hidden items-center gap-1 md:flex">
            <a href="{{ route('products.index') }}" @if (request()->routeIs('products.*')) aria-current="page" @endif class="inline-flex min-h-11 items-center rounded-full px-3 text-xs font-semibold text-navy/80 transition hover:bg-aqua/40 hover:text-navy focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-blue aria-[current=page]:bg-blue aria-[current=page]:text-white">Catálogo</a>
            <a href="{{ route('storefront.novelties') }}" @if (request()->routeIs('storefront.novelties')) aria-current="page" @endif class="inline-flex min-h-11 items-center rounded-full px-3 text-xs font-semibold text-navy/80 transition hover:bg-aqua/40 hover:text-navy focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-blue aria-[current=page]:bg-blue aria-[current=page]:text-white">Novidades</a>
            <a href="{{ route('storefront.about') }}" @if (request()->routeIs('storefront.about')) aria-current="page" @endif class="inline-flex min-h-11 items-center rounded-full px-3 text-xs font-semibold text-navy/80 transition hover:bg-aqua/40 hover:text-navy focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-blue aria-[current=page]:bg-blue aria-[current=page]:text-white">Sobre</a>
        </nav>
        <form action="{{ route('storefront.search') }}" method="GET" role="search" data-async-search
              class="order-last basis-full md:order-none md:ml-auto md:basis-80 lg:basis-[28rem]">
            <label for="site-search" class="sr-only">Pesquisar na Dimensiona</label>
            <div class="flex min-h-12 items-center rounded-full border border-navy bg-white py-1 pr-1 pl-4 shadow-[0_6px_18px_rgba(1,15,55,0.08)]">
                <input type="search" id="site-search" name="q" value="{{ request()->routeIs('storefront.search') ? request('q') : '' }}" placeholder="Pesquisar na Dimensiona"
                       class="min-w-0 flex-1 bg-transparent text-xs text-navy placeholder:text-muted focus:outline-none">
                <button type="submit" aria-label="Buscar"
                        class="inline-grid size-10 shrink-0 place-items-center rounded-full bg-navy text-white transition hover:bg-navy/90 focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-blue focus-visible:ring-offset-2">
                    <svg class="size-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><circle cx="11" cy="11" r="6.5"/><path d="m16 16 4 4"/></svg>
                </button>
            </div>
        </form>
        <div class="ml-auto flex shrink-0 items-center gap-0.5 md:ml-1">
            @guest
                <div class="flex items-center">
                    <a href="{{ route('login') }}" class="inline-flex min-h-11 items-center gap-2 rounded-full px-2 text-navy transition hover:bg-aqua/40 focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-blue sm:px-3">
                        <svg class="size-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.7" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><circle cx="12" cy="8" r="3.5"/><path d="M5 20c0-3.6 3.1-6 7-6s7 2.4 7 6"/></svg>
                        <span class="hidden text-left text-[11px] leading-tight sm:block">Login<strong class="block text-xs font-bold">Pedidos e Conta</strong></span>
                    </a>
                    <span aria-hidden="true" class="hidden text-xs text-muted sm:inline">/</span>
                    <a href="{{ route('register') }}" class="hidden min-h-11 items-center rounded-full px-2 text-[11px] text-navy underline decoration-navy/40 underline-offset-2 transition hover:bg-aqua/40 focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-blue sm:inline-flex">Cadastrar-se</a>
                </div>
            @else
                <details data-account-menu class="relative">
                    <summary class="inline-flex min-h-11 cursor-pointer list-none items-center gap-2 rounded-full px-2 focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-blue [&::-webkit-details-marker]:hidden sm:px-3">
                        <span class="grid size-9 place-items-center rounded-full bg-navy text-sm font-bold text-white">{{ strtoupper(mb_substr(auth()->user()->name, 0, 1)) }}</span>
                        <span class="hidden text-left text-xs font-bold sm:block">Pedidos e Conta<span class="block text-[11px] font-normal text-muted">{{ auth()->user()->name }}</span></span>
                        <svg class="hidden size-4 sm:block" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="m6 9 6 6 6-6"/></svg>
                    </summary>
                    <nav aria-label="Menu da conta" class="absolute top-full right-0 z-40 mt-2 w-72 rounded-3xl border border-aqua bg-navy p-3 text-white shadow-2xl">
                        <a href="{{ route('profile.edit') }}" class="flex min-h-11 items-center rounded-full px-4 text-sm transition hover:bg-white/10 focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-blue">Meu perfil</a>
                        <a href="{{ route('orders.index') }}" class="flex min-h-11 items-center rounded-full px-4 text-sm transition hover:bg-white/10 focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-blue">{{ auth()->user()->is_admin ? 'Pedidos da loja' : 'Meus pedidos' }}</a>
                        <a href="{{ route('addresses.index') }}" class="flex min-h-11 items-center rounded-full px-4 text-sm transition hover:bg-white/10 focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-blue">Meus endereços</a>
                        @can('admin')
                            <p class="mt-2 px-4 pt-3 text-[11px] font-semibold tracking-wider text-aqua uppercase">Administração</p>
                            <a href="{{ route('admin.dashboard') }}" class="flex min-h-11 items-center rounded-full px-4 text-sm transition hover:bg-white/10 focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-blue">Painel administrativo</a>
                            <a href="{{ route('products.create') }}" class="flex min-h-11 items-center rounded-full px-4 text-sm transition hover:bg-white/10 focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-blue">Cadastrar produto</a>
                            <a href="{{ route('categories.create') }}" class="flex min-h-11 items-center rounded-full px-4 text-sm transition hover:bg-white/10 focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-blue">Criar categoria</a>
                            <a href="{{ route('users.index') }}" class="flex min-h-11 items-center rounded-full px-4 text-sm transition hover:bg-white/10 focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-blue">Usuários cadastrados</a>
                            <a href="{{ route('setting.create') }}" class="flex min-h-11 items-center rounded-full px-4 text-sm transition hover:bg-white/10 focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-blue">Dados da loja</a>
                        @endcan
                        <form action="{{ route('logout') }}" method="POST" class="mt-2 border-t border-white/15 pt-2">@csrf<button type="submit" class="min-h-11 w-full rounded-full px-4 text-left text-sm font-semibold transition hover:bg-white/10 focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-blue">Sair</button></form>
                    </nav>
                </details>
            @endguest
            @unless (auth()->check() && auth()->user()->is_admin)
                <a href="{{ route('cart.index') }}" aria-label="Abrir carrinho" title="Carrinho"
                   class="relative inline-grid size-11 place-items-center rounded-full text-navy transition hover:bg-aqua/40 focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-blue">
                    <svg class="size-6" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.7" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="M3 4h2l2.1 10.1a2 2 0 0 0 2 1.6h7.8a2 2 0 0 0 2-1.6L20 8H6"/><path d="M12 5v6M9 8h6"/><circle cx="10" cy="20" r="1"/><circle cx="17" cy="20" r="1"/></svg>
                    <span data-cart-count hidden aria-label="Itens no carrinho" class="absolute -top-0.5 -right-0.5 min-w-5 rounded-full bg-red px-1.5 text-center text-[10px] leading-5 font-bold text-white"></span>
                </a>
            @endunless
            <span aria-hidden="true" class="hidden min-h-11 items-center gap-0.5 xl:inline-flex">
                <span class="inline-grid size-10 place-items-center rounded-full text-navy"><svg class="size-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.7" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="M19 14c1.49-1.46 3-3.21 3-5.5A5.5 5.5 0 0 0 16.5 3c-1.76 0-3 .5-4.5 2-1.5-1.5-2.74-2-4.5-2A5.5 5.5 0 0 0 2 8.5c0 2.3 1.5 4.05 3 5.5l7 7Z"/></svg></span>
                <span class="inline-grid size-10 place-items-center rounded-full text-navy"><svg class="size-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.7" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><rect x="3" y="8" width="18" height="4" rx="1"/><path d="M12 8v13"/><path d="M19 12v7a2 2 0 0 1-2 2H7a2 2 0 0 1-2-2v-7"/><path d="M7.5 8a2.5 2.5 0 0 1 0-5C11 3 12 8 12 8s1-5 4.5-5a2.5 2.5 0 0 1 0 5"/></svg></span>
                <span class="inline-grid size-10 place-items-center rounded-full text-navy"><svg class="size-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.7" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="M21 15a2 2 0 0 1-2 2H7l-4 4V5a2 2 0 0 1 2-2h14a2 2 0 0 1 2 2z"/></svg></span>
            </span>
        </div>
    </div>
</header>

<div class="border-b border-aqua/70 bg-bg">
    <div class="mx-auto max-w-7xl px-4 py-2 sm:px-6 lg:px-8">
        <div class="flex flex-wrap items-center justify-center gap-x-3 gap-y-2 rounded-full bg-mist px-4 py-2 text-center shadow-[0_6px_18px_rgba(1,15,55,0.06)]">
            <span class="grid size-8 shrink-0 place-items-center rounded-full bg-navy text-white">
                <svg class="size-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><circle cx="12" cy="12" r="9"/><path d="M15.5 9.5a3.5 3.5 0 0 0-6.4 2c0 2 6 1 6 3a3.5 3.5 0 0 1-6.4 2"/><path d="M12 6.5v11"/></svg>
            </span>
            <p class="text-[11px] text-navy sm:text-xs"><strong>{{ $promoValue }}</strong> para pedidos acima de <strong>{{ $promoMinimum }}</strong></p>
            @guest
                <a href="{{ route('login') }}" class="inline-flex min-h-9 items-center rounded-full bg-navy px-4 text-[11px] font-semibold text-white transition hover:bg-navy/90 focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-blue focus-visible:ring-offset-2 focus-visible:ring-offset-mist">Faça login para receber</a>
            @endguest
        </div>
    </div>
</div>

<main id="conteudo" tabindex="-1" class="mx-auto min-h-[60vh] max-w-7xl px-4 py-8 sm:px-6 sm:py-12 focus:outline-none lg:px-8">
    @if (session('success'))
        <div class="mb-8 rounded-3xl border border-teal/30 bg-white px-5 py-4 text-sm text-navy shadow-sm" role="status">
            <p>{{ session('success') }}</p>
        </div>
    @endif
    @yield('content')
</main>

<footer class="mt-12 bg-navy text-white">
    <div class="mx-auto max-w-7xl px-4 py-12 sm:px-6 lg:px-8">
        <div class="grid gap-10 lg:grid-cols-[1fr_22rem]">
            <div>
                <div class="grid gap-8 sm:grid-cols-3">
                    <section aria-labelledby="footer-company">
                        <h2 id="footer-company" class="text-sm font-semibold">Informações da Empresa</h2>
                        <ul class="mt-4 space-y-2 text-xs text-white/75">
                            <li><a class="inline-flex min-h-9 items-center rounded-full transition hover:text-white focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-blue" href="{{ route('storefront.about') }}">Sobre a Dimensiona</a></li>
                            <li><a class="inline-flex min-h-9 items-center rounded-full transition hover:text-white focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-blue" href="{{ route('storefront.about') }}#contato">Fale conosco</a></li>
                            <li><a class="inline-flex min-h-9 items-center rounded-full transition hover:text-white focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-blue" href="{{ route('products.index') }}">Catálogo</a></li>
                        </ul>
                    </section>
                    <section aria-labelledby="footer-service">
                        <h2 id="footer-service" class="text-sm font-semibold">Serviço de atendimento ao cliente</h2>
                        <ul class="mt-4 space-y-2 text-xs text-white/75">
                            <li><a class="inline-flex min-h-9 items-center rounded-full transition hover:text-white focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-blue" href="#">Política de Devolução e Reembolso</a></li>
                            <li><a class="inline-flex min-h-9 items-center rounded-full transition hover:text-white focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-blue" href="#">Política de Propriedade Intelectual</a></li>
                            <li><a class="inline-flex min-h-9 items-center rounded-full transition hover:text-white focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-blue" href="#">Informações de envio</a></li>
                            <li><a class="inline-flex min-h-9 items-center rounded-full transition hover:text-white focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-blue" href="#">Relatar atividades suspeitas</a></li>
                            <li><a class="inline-flex min-h-9 items-center rounded-full transition hover:text-white focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-blue" href="{{ auth()->check() ? route('orders.index') : route('login') }}">Acompanhar pedido</a></li>
                        </ul>
                    </section>
                    <section aria-labelledby="footer-help">
                        <h2 id="footer-help" class="text-sm font-semibold">Ajuda</h2>
                        <ul class="mt-4 space-y-2 text-xs text-white/75">
                            <li><a class="inline-flex min-h-9 items-center rounded-full transition hover:text-white focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-blue" href="#">Atendimento ao cliente e FAQ</a></li>
                            <li><a class="inline-flex min-h-9 items-center rounded-full transition hover:text-white focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-blue" href="#">Centro de segurança</a></li>
                            <li><a class="inline-flex min-h-9 items-center rounded-full transition hover:text-white focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-blue" href="{{ route('storefront.search') }}">Pesquisar produtos</a></li>
                            <li><a class="inline-flex min-h-9 items-center rounded-full transition hover:text-white focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-blue" href="{{ auth()->check() ? route('profile.edit') : route('login') }}">Minha conta</a></li>
                            <li><a class="inline-flex min-h-9 items-center rounded-full transition hover:text-white focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-blue" href="{{ route('addresses.index') }}">Meus endereços</a></li>
                        </ul>
                    </section>
                </div>

                <div class="mt-10 grid gap-8 border-t border-white/15 pt-8 sm:grid-cols-3">
                    <section aria-labelledby="footer-app">
                        <h2 id="footer-app" class="text-sm font-semibold">Baixe o app Dimensiona</h2>
                        <div class="mt-4 flex flex-wrap gap-2">
                            <a href="#" class="inline-flex min-h-11 items-center rounded-full border border-white px-4 text-xs font-semibold transition hover:bg-white/10 focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-blue">App Store</a>
                            <a href="#" class="inline-flex min-h-11 items-center rounded-full border border-white px-4 text-xs font-semibold transition hover:bg-white/10 focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-blue">Play Store</a>
                        </div>
                    </section>
                    <section aria-labelledby="footer-social">
                        <h2 id="footer-social" class="text-sm font-semibold">Encontre-nos</h2>
                        <div class="mt-4 flex flex-wrap gap-2">
                            @foreach ([
                                'Instagram' => 'M12 2.163c3.204 0 3.584.012 4.85.07 3.252.148 4.771 1.691 4.919 4.919.058 1.265.069 1.645.069 4.849 0 3.205-.012 3.584-.069 4.849-.149 3.225-1.664 4.771-4.919 4.919-1.266.058-1.644.07-4.85.07-3.204 0-3.584-.012-4.849-.07-3.26-.149-4.771-1.699-4.919-4.92-.058-1.265-.07-1.644-.07-4.849 0-3.204.013-3.583.07-4.849.149-3.227 1.664-4.771 4.919-4.919 1.266-.057 1.645-.069 4.849-.069zM12 0C8.741 0 8.333.014 7.053.072 2.695.272.273 2.69.073 7.052.014 8.333 0 8.741 0 12c0 3.259.014 3.668.072 4.948.2 4.358 2.618 6.78 6.98 6.98C8.333 23.986 8.741 24 12 24c3.259 0 3.668-.014 4.948-.072 4.354-.2 6.782-2.618 6.979-6.98.059-1.28.073-1.689.073-4.948 0-3.259-.014-3.667-.072-4.947-.196-4.354-2.617-6.78-6.979-6.98C15.668.014 15.259 0 12 0zm0 5.838a6.162 6.162 0 1 0 0 12.324 6.162 6.162 0 0 0 0-12.324zM12 16a4 4 0 1 1 0-8 4 4 0 0 1 0 8zm6.406-11.845a1.44 1.44 0 1 0 0 2.881 1.44 1.44 0 0 0 0-2.881z',
                                'Facebook' => 'M24 12.073c0-6.627-5.373-12-12-12s-12 5.373-12 12c0 5.99 4.388 10.954 10.125 11.854v-8.385H7.078v-3.47h3.047V9.43c0-3.007 1.792-4.669 4.533-4.669 1.312 0 2.686.235 2.686.235v2.953H15.83c-1.491 0-1.956.925-1.956 1.874v2.25h3.328l-.532 3.47h-2.796v8.385C19.612 23.027 24 18.062 24 12.073z',
                                'X' => 'M18.901 1.153h3.68l-8.04 9.19L24 22.846h-7.406l-5.8-7.584-6.638 7.584H.474l8.6-9.83L0 1.154h7.594l5.243 6.932ZM17.61 20.644h2.039L6.486 3.24H4.298Z',
                                'TikTok' => 'M12.525.02c1.31-.02 2.61-.01 3.91-.02.08 1.53.63 3.09 1.75 4.17 1.12 1.11 2.7 1.62 4.24 1.79v4.03c-1.44-.05-2.89-.35-4.2-.97-.57-.26-1.1-.59-1.62-.93-.01 2.92.01 5.84-.02 8.75-.08 1.4-.54 2.79-1.35 3.94-1.31 1.92-3.58 3.17-5.91 3.21-1.43.08-2.86-.31-4.08-1.03-2.02-1.19-3.44-3.37-3.65-5.71-.02-.5-.03-1-.01-1.49.18-1.9 1.12-3.72 2.58-4.96 1.66-1.44 3.98-2.13 6.15-1.72.02 1.48-.04 2.96-.04 4.44-.99-.32-2.15-.23-3.02.37-.63.41-1.11 1.04-1.36 1.75-.21.51-.15 1.07-.14 1.61.24 1.64 1.82 3.02 3.5 2.87 1.12-.01 2.19-.66 2.77-1.61.19-.33.4-.67.41-1.06.1-1.79.06-3.57.07-5.36.01-4.03-.01-8.05.02-12.07z',
                                'YouTube' => 'M23.498 6.186a3.016 3.016 0 0 0-2.122-2.136C19.505 3.545 12 3.545 12 3.545s-7.505 0-9.377.505A3.017 3.017 0 0 0 .502 6.186C0 8.07 0 12 0 12s0 3.93.502 5.814a3.016 3.016 0 0 0 2.122 2.136c1.871.505 9.376.505 9.376.505s7.505 0 9.377-.505a3.015 3.015 0 0 0 2.122-2.136C24 15.93 24 12 24 12s0-3.93-.502-5.814zM9.545 15.568V8.432L15.818 12l-6.273 3.568z',
                                'Pinterest' => 'M12.017 0C5.396 0 .029 5.367.029 11.987c0 5.079 3.158 9.417 7.618 11.162-.105-.949-.199-2.403.041-3.439.219-.937 1.406-5.957 1.406-5.957s-.359-.72-.359-1.781c0-1.663.967-2.911 2.168-2.911 1.024 0 1.518.769 1.518 1.688 0 1.029-.653 2.567-.992 3.992-.285 1.193.6 2.165 1.775 2.165 2.128 0 3.768-2.245 3.768-5.487 0-2.861-2.063-4.869-5.008-4.869-3.41 0-5.409 2.562-5.409 5.199 0 1.033.394 2.143.889 2.741.099.12.112.225.085.345-.09.375-.293 1.199-.334 1.363-.053.225-.172.271-.401.165-1.495-.69-2.433-2.878-2.433-4.646 0-3.776 2.748-7.252 7.92-7.252 4.158 0 7.392 2.967 7.392 6.923 0 4.135-2.607 7.462-6.233 7.462-1.214 0-2.354-.629-2.758-1.379l-.749 2.848c-.269 1.045-1.004 2.352-1.498 3.146 1.123.345 2.306.535 3.55.535 6.607 0 11.985-5.365 11.985-11.987C23.97 5.39 18.592.026 11.985.026L12.017 0z',
                            ] as $social => $path)
                                <a href="#" aria-label="{{ $social }}" class="inline-grid size-11 place-items-center rounded-full border border-white/40 text-white transition hover:bg-white/10 focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-blue">
                                    <svg class="size-5" viewBox="0 0 24 24" fill="currentColor" aria-hidden="true"><path d="{{ $path }}"/></svg>
                                </a>
                            @endforeach
                        </div>
                    </section>
                    <section aria-labelledby="footer-payments">
                        <h2 id="footer-payments" class="text-sm font-semibold">Aceitamos</h2>
                        <div class="mt-4 flex flex-wrap gap-2">
                            @foreach (['Visa', 'Mastercard', 'Elo', 'Amex', 'Pix', 'Boleto'] as $payment)
                                <span class="inline-flex min-h-9 items-center rounded-full bg-white px-3 text-[11px] font-semibold text-navy">{{ $payment }}</span>
                            @endforeach
                        </div>
                    </section>
                </div>
            </div>

            <div class="flex flex-col justify-between rounded-3xl bg-gradient-to-br from-navy via-[#041a48] to-[#010f37] p-6 shadow-2xl ring-1 ring-white/10 sm:p-8">
                <div>
                    <x-ui.logo size="lg" />
                    <p class="mt-6 text-3xl font-semibold">Dimensiona</p>
                    <p class="mt-3 text-sm text-white/70">Peças e soluções em impressão 3D para dar forma às suas ideias.</p>
                </div>
                <p class="mt-10 text-xs text-white/60">Ideias que ganham forma.</p>
            </div>
        </div>
        <p class="mt-10 border-t border-white/15 pt-6 text-center text-xs text-white/70">© {{ now()->year }} Dimensiona. Todos os direitos reservados.</p>
    </div>
</footer>

<div id="toast" role="status" aria-live="polite"
     class="pointer-events-none fixed bottom-6 left-1/2 z-[60] w-[min(24rem,calc(100vw-2rem))] -translate-x-1/2 translate-y-3 rounded-full bg-navy px-5 py-3 text-center text-sm font-semibold text-white opacity-0 shadow-2xl transition duration-300 data-[visible]:translate-y-0 data-[visible]:opacity-100"></div>
@stack('scripts')
</body>
</html>
