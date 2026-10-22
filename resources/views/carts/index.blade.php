@extends('layouts.storefront', ['title' => 'Carrinho'])

@section('content')
<section class="page-head"><div class="container"><span class="eyebrow">Sua seleção</span><h1>Carrinho</h1><p>Revise os itens e escolha o que deseja finalizar agora.</p></div></section>

@guest
    <section class="container pb-20">
        <div class="guest-cart">
            <div class="guest-cart__icon" aria-hidden="true">
                <svg viewBox="0 0 24 24" fill="none"><path d="M3 4h2l2.1 10.1a2 2 0 0 0 2 1.6h7.8a2 2 0 0 0 2-1.6L20 8H6" stroke="currentColor" stroke-width="1.6" stroke-linecap="round" stroke-linejoin="round"/><circle cx="10" cy="20" r="1" fill="currentColor"/><circle cx="17" cy="20" r="1" fill="currentColor"/></svg>
            </div>
            <span class="eyebrow">Sua próxima ideia começa aqui</span>
            <h2>Entre para acessar seu carrinho</h2>
            <p>Faça login para ver seus itens, continuar a compra e acompanhar seus pedidos.</p>
            <div class="btn-row guest-cart__actions">
                <a class="btn btn--primary" href="{{ route('login') }}">Entrar na minha conta</a>
                <a class="btn btn--outline" href="{{ route('products.index') }}">Explorar catálogo</a>
            </div>
        </div>
    </section>
@else
    <section class="container cart-layout">
        <div>
            @if ($errors->any())<p class="form-error" role="alert">{{ $errors->first() }}</p>@endif
            @if ($cart->cartItems->isEmpty())
                <div class="empty-state"><div class="empty-state__icon" aria-hidden="true"><svg viewBox="0 0 24 24"><path d="M3 4h2l2.1 10.1a2 2 0 0 0 2 1.6h7.8a2 2 0 0 0 2-1.6L20 8H6M9 20h.01M17 20h.01"/></svg></div><h2>Seu carrinho está vazio</h2><p>Explore o catálogo e encontre algo para dar forma às suas ideias.</p><a class="btn btn--primary" href="{{ route('products.index') }}">Ver catálogo</a></div>
            @else
                <div data-cart-items-container data-catalog-url="{{ route('products.index') }}">
                <div class="cart-selection">
                    <label><input type="checkbox" id="select-all-cart-items" checked> Selecionar todos</label>
                    <span data-selected-count>{{ $cart->cartItems->sum('quantity') }} itens selecionados</span>
                </div>
                @foreach ($cart->cartItems as $item)
                    <article class="cart-item" data-cart-item="{{ $item->id }}">
                        <input type="checkbox" name="cart_item_ids[]" value="{{ $item->id }}" id="cart-item-{{ $item->id }}" form="order-selection" data-cart-select data-item-total="{{ (float) $item->product->price * $item->quantity }}" data-item-quantity="{{ $item->quantity }}" checked aria-label="Selecionar {{ $item->product->name }} para compra">
                        <img src="{{ $item->product->image ? Storage::disk('public')->url($item->product->image) : asset('images/dimensiona/placeholder.svg') }}" alt="">
                        <div><a class="cart-item__name" href="{{ route('products.show', $item->product) }}">{{ $item->product->name }}</a><p class="cart-item__price">R$ {{ number_format((float) $item->product->price, 2, ',', '.') }}</p><p class="text-muted">Quantidade: <span data-cart-quantity>{{ $item->quantity }}</span> · Subtotal: <span data-cart-subtotal>R$ {{ number_format((float) $item->product->price * $item->quantity, 2, ',', '.') }}</span></p></div>
                        <div class="cart-item__actions">
                            <form action="{{ route('cart-items.add', $item) }}" method="POST" data-cart-mutation>@csrf<button class="btn btn--ghost" type="submit">Aumentar quantidade</button></form>
                            <form action="{{ route('cart-items.destroy', $item) }}" method="POST" data-cart-mutation>@csrf @method('DELETE')<button class="link-btn" type="submit">Diminuir quantidade</button></form>
                        </div>
                    </article>
                @endforeach
                <form id="order-selection" action="{{ route('orders.store') }}" method="POST">@csrf
                    <button class="btn btn--primary" type="submit" data-order-submit>Finalizar compra</button>
                </form>
                </div>
            @endif
        </div>
        <aside class="summary" data-cart-summary><h2>Resumo</h2>
            <p class="summary__row"><span>Itens selecionados</span><span data-summary-count>{{ $cart->cartItems->sum('quantity') }}</span></p>
            <p class="summary__row summary__row--total"><span>Subtotal selecionado</span><span data-selected-total>R$ {{ number_format((float) $cart->cartItems->sum(fn ($item) => $item->product->price * $item->quantity), 2, ',', '.') }}</span></p>
            <p class="summary__ship">O frete será calculado ao finalizar o pedido.</p>
            <a class="btn btn--outline btn--block" href="{{ route('products.index') }}">Continuar comprando</a>
        </aside>
    </section>
@endguest
@endsection
