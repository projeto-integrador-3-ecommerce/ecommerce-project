@extends('layouts.storefront', ['title' => 'Carrinho'])

@php($freeShippingThreshold = config('storefront.free_shipping_threshold'))

@section('content')
<nav aria-label="Trilha de navegação" class="mb-6 flex flex-wrap items-center gap-2 text-xs text-muted">
    <a href="{{ route('storefront.home') }}" class="rounded-full px-2 py-1 transition hover:text-navy focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-blue">Página Inicial</a>
    <span aria-hidden="true">›</span>
    <span class="font-semibold text-navy">Carrinho</span>
</nav>

<section class="rounded-3xl border border-aqua bg-white/80 p-6 shadow-sm sm:p-10">
    <span class="text-xs font-semibold tracking-[0.14em] text-teal uppercase">Sua seleção</span>
    <h1 class="mt-2 text-3xl font-semibold text-navy sm:text-4xl">Carrinho</h1>
    <p class="mt-3 text-sm text-muted">Revise os itens e escolha o que deseja finalizar agora.</p>
</section>

<div class="mt-6 flex flex-wrap items-center gap-x-4 gap-y-2 rounded-full bg-teal px-5 py-3 text-white shadow-sm">
    <span class="inline-grid size-8 shrink-0 place-items-center rounded-full border border-white/60">
        <svg class="size-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="m5 12 4 4L19 6"/></svg>
    </span>
    <span class="hidden h-6 w-px bg-white/50 sm:block" aria-hidden="true"></span>
    @if ($freeShippingThreshold)
        <strong class="text-sm font-bold">Frete Grátis</strong>
        <span class="text-xs text-white/90">em pedidos acima de {{ $freeShippingThreshold }}</span>
    @else
        <strong class="text-sm font-bold">Frete Grátis</strong>
        <span class="text-xs text-white/90">O frete será calculado ao finalizar o pedido</span>
    @endif
</div>

@guest
    <section class="mt-8">
        <div data-empty-cart class="mx-auto max-w-2xl rounded-3xl border border-aqua bg-white px-6 py-14 text-center shadow-sm">
            <span class="mx-auto grid size-16 place-items-center rounded-full bg-mist text-navy">
                <svg class="size-8" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.6" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="M3 4h2l2.1 10.1a2 2 0 0 0 2 1.6h7.8a2 2 0 0 0 2-1.6L20 8H6"/><circle cx="10" cy="20" r="1"/><circle cx="17" cy="20" r="1"/></svg>
            </span>
            <span class="mt-5 block text-xs font-semibold tracking-[0.14em] text-teal uppercase">Sua próxima ideia começa aqui</span>
            <h2 class="mt-2 text-2xl font-semibold text-navy">Entre para acessar seu carrinho</h2>
            <p class="mx-auto mt-3 max-w-md text-sm text-muted">Faça login para ver seus itens, continuar a compra e acompanhar seus pedidos.</p>
            <div class="mt-7 flex flex-wrap justify-center gap-3">
                <x-ui.link href="{{ route('login') }}">Entrar na minha conta</x-ui.link>
                <x-ui.link href="{{ route('products.index') }}" variant="outline">Explorar catálogo</x-ui.link>
            </div>
        </div>
    </section>
@else
    <section class="mt-8 grid items-start gap-6 lg:grid-cols-[1fr_22rem]">
        <div>
            @if ($errors->any())
                <p role="alert" class="mb-5 rounded-2xl bg-red/10 px-4 py-3 text-sm font-semibold text-red">{{ $errors->first() }}</p>
            @endif
            @if ($cart->cartItems->isEmpty())
                <div data-empty-cart class="rounded-3xl border border-aqua bg-white px-6 py-14 text-center shadow-sm">
                    <span class="mx-auto grid size-16 place-items-center rounded-full bg-mist text-navy">
                        <svg class="size-8" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.6" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="M3 4h2l2.1 10.1a2 2 0 0 0 2 1.6h7.8a2 2 0 0 0 2-1.6L20 8H6M9 20h.01M17 20h.01"/></svg>
                    </span>
                    <h2 class="mt-5 text-2xl font-semibold text-navy">Seu carrinho está vazio</h2>
                    <p class="mx-auto mt-3 max-w-md text-sm text-muted">Explore o catálogo e encontre algo para dar forma às suas ideias.</p>
                    <x-ui.link href="{{ route('products.index') }}" class="mt-7">Ver catálogo</x-ui.link>
                </div>
            @else
                <div data-cart-items-container data-catalog-url="{{ route('products.index') }}" class="space-y-4">
                    <div class="flex flex-col gap-4 rounded-3xl border border-aqua bg-white p-4 shadow-sm sm:flex-row sm:items-center sm:justify-between">
                        <div class="flex flex-wrap items-center gap-3">
                            <label class="inline-flex min-h-11 cursor-pointer items-center gap-2 rounded-full px-2 text-xs font-semibold text-navy has-[:checked]:bg-aqua/40">
                                <input type="checkbox" id="select-all-cart-items" checked class="size-5 rounded-full border-navy accent-blue focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-blue">
                                Selecionar tudo ({{ $cart->cartItems->count() }})
                            </label>
                            <span data-selected-count class="text-xs text-muted">{{ $cart->cartItems->sum('quantity') }} itens selecionados</span>
                        </div>
                        <div class="flex flex-wrap items-center gap-2">
                            <button type="button" data-cart-filter="all" aria-pressed="true" class="inline-flex min-h-11 items-center rounded-full border border-navy px-4 text-xs font-semibold text-navy transition hover:bg-navy/5 focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-blue aria-[pressed=true]:bg-navy aria-[pressed=true]:text-white">Tudo ({{ $cart->cartItems->count() }})</button>
                            <button type="button" data-cart-filter="selected" aria-pressed="false" class="inline-flex min-h-11 items-center rounded-full border border-navy/30 px-4 text-xs font-semibold text-navy transition hover:bg-navy/5 focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-blue aria-[pressed=true]:bg-navy aria-[pressed=true]:text-white">Selecionado ({{ $cart->cartItems->count() }})</button>
                            <button type="button" data-cart-filter="selected" aria-label="Filtrar itens selecionados" class="inline-grid size-11 place-items-center rounded-full border border-navy/30 text-navy transition hover:bg-navy/5 focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-blue">
                                <svg class="size-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="M4 6h16M7 12h10M10 18h4"/></svg>
                            </button>
                        </div>
                    </div>

                    @foreach ($cart->cartItems as $item)
                        <article data-cart-item="{{ $item->id }}" data-quantity="{{ $item->quantity }}" class="group/item rounded-3xl border border-aqua bg-white p-4 shadow-sm transition hover:border-blue focus-within:border-blue sm:p-5">
                            <div class="flex flex-wrap items-start gap-4">
                                <label class="inline-grid size-11 shrink-0 place-items-center rounded-full border border-navy/30 has-[:checked]:bg-aqua/50">
                                    <input type="checkbox" name="cart_item_ids[]" value="{{ $item->id }}" id="cart-item-{{ $item->id }}" form="order-selection" data-cart-select data-item-total="{{ (float) $item->product->price * $item->quantity }}" data-item-quantity="{{ $item->quantity }}" checked aria-label="Selecionar {{ $item->product->name }} para compra" class="size-5 accent-blue focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-blue">
                                </label>
                                <img src="{{ $item->product->image_url ?: asset('images/dimensiona/placeholder.svg') }}" alt="{{ $item->product->name }}" class="size-24 shrink-0 rounded-2xl bg-mist/40 object-cover">
                                <div class="min-w-0 flex-1">
                                    <a href="{{ route('products.show', $item->product) }}" class="rounded-full text-sm font-semibold text-muted transition hover:text-navy focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-blue">{{ $item->product->name }}</a>
                                    <div class="mt-2">
                                        @if ($freeShippingThreshold)
                                            <x-ui.badge variant="teal">Frete Grátis</x-ui.badge>
                                        @else
                                            <span class="text-[11px] text-muted">Frete a calcular</span>
                                        @endif
                                    </div>
                                    <p class="mt-3 text-base font-bold text-navy">R$ {{ number_format((float) $item->product->price, 2, ',', '.') }}</p>
                                    <p class="mt-1 text-xs text-muted">Subtotal: <span data-cart-subtotal class="font-semibold text-navy">R$ {{ number_format((float) $item->product->price * $item->quantity, 2, ',', '.') }}</span></p>
                                </div>
                                <div class="flex items-center rounded-full border border-navy bg-white">
                                    <form action="{{ route('cart-items.destroy', $item) }}" method="POST" data-cart-mutation>@csrf @method('DELETE')
                                        <button type="submit" aria-label="Diminuir quantidade" class="relative inline-grid size-11 place-items-center rounded-full text-navy transition hover:bg-mist focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-blue disabled:opacity-50">
                                            <svg class="size-5 group-data-[quantity=1]/item:hidden" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" aria-hidden="true"><path d="M5 12h14"/></svg>
                                            <svg class="hidden size-5 text-red group-data-[quantity=1]/item:block" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="M4 7h16M9 7V4h6v3M6 7l1 13h10l1-13"/></svg>
                                        </button>
                                    </form>
                                    <span data-cart-quantity aria-label="Quantidade" class="min-w-8 text-center text-sm font-semibold text-navy">{{ $item->quantity }}</span>
                                    <form action="{{ route('cart-items.add', $item) }}" method="POST" data-cart-mutation>@csrf
                                        <button type="submit" aria-label="Aumentar quantidade" class="inline-grid size-11 place-items-center rounded-full text-navy transition hover:bg-mist focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-blue disabled:opacity-50">
                                            <svg class="size-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" aria-hidden="true"><path d="M12 5v14M5 12h14"/></svg>
                                        </button>
                                    </form>
                                </div>
                            </div>
                        </article>
                    @endforeach

                    <form id="order-selection" action="{{ route('orders.store') }}" method="POST">@csrf</form>
                </div>
            @endif
        </div>

        <aside data-cart-summary @if ($cart->cartItems->isEmpty()) hidden @endif class="self-start lg:sticky lg:top-28">
            <div class="rounded-3xl border border-aqua bg-white p-5 shadow-sm sm:p-6">
                <h2 class="text-base font-semibold text-navy">Resumo do pedido <span data-summary-count class="text-muted">({{ $cart->cartItems->sum('quantity') }})</span></h2>
                <dl class="mt-5 space-y-3 text-sm">
                    <div class="flex items-center justify-between gap-3 border-b border-aqua pb-3">
                        <dt class="text-muted">Total do(s) item(ns)</dt>
                        <dd data-selected-total class="font-semibold text-navy">R$ {{ number_format((float) $cart->cartItems->sum(fn ($item) => $item->product->price * $item->quantity), 2, ',', '.') }}</dd>
                    </div>
                    <div class="flex items-center justify-between gap-3 border-b border-aqua pb-3">
                        <dt class="text-muted">Total do frete</dt>
                        <dd class="font-semibold text-navy">{{ $freeShippingThreshold ? 'Grátis' : 'A calcular' }}</dd>
                    </div>
                    <div class="flex items-center justify-between gap-3">
                        <dt class="font-semibold text-navy">Total estimado</dt>
                        <dd data-summary-estimated class="font-bold text-navy">R$ {{ number_format((float) $cart->cartItems->sum(fn ($item) => $item->product->price * $item->quantity), 2, ',', '.') }}</dd>
                    </div>
                </dl>
                <p class="mt-4 text-xs text-muted">Os valores são atualizados conforme os itens selecionados.</p>
                @if ($cart->cartItems->isNotEmpty())
                    <x-ui.button type="submit" form="order-selection" data-order-submit size="lg" class="mt-6 w-full">Finalizar compra ({{ $cart->cartItems->count() }})</x-ui.button>
                @endif
                <div class="mt-5 flex items-start gap-2 rounded-2xl bg-mist/50 p-3 text-xs text-navy">
                    <svg class="mt-0.5 size-4 shrink-0 text-teal" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="M12 3 5 6v6c0 4.3 3 7.4 7 9 4-1.6 7-4.7 7-9V6Z"/><path d="m9 12 2 2 4-4"/></svg>
                    Seu pedido está coberto pela nossa Garantia de Melhor Preço. Pague agora mesmo!
                </div>
                <a href="{{ route('products.index') }}" class="mt-5 inline-flex min-h-11 items-center rounded-full px-2 text-xs font-semibold text-navy underline decoration-blue/40 underline-offset-2 focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-blue">Continuar comprando</a>
            </div>
        </aside>
    </section>
@endif
@endsection
