@extends('layouts.storefront', ['title' => 'Carrinho'])

@section('content')

    <section class="page-head"><div class="container"><span class="eyebrow">Sua seleção</span><h1>Carrinho</h1></div></section>
    <section class="container cart-layout">
        <div>
            @if ($errors->any())<p class="form-error" role="alert">{{ $errors->first() }}</p>@endif
            @if ($cart->cartItems->isEmpty())
                <div class="empty-state"><h2>Seu carrinho está vazio</h2><p>Explore o catálogo para encontrar seu próximo produto.</p><a class="btn btn--primary" href="{{ route('products.index') }}">Ver catálogo</a></div>
            @else
                @foreach ($cart->cartItems as $item)
                    <article class="cart-item">
                            <input type="checkbox" name="cart_item_ids[]" value="{{ $item->id }}" id="cart-item-{{ $item->id }}" form="order-selection" aria-label="Selecionar {{ $item->product->name }} para compra">
                            <img src="{{ $item->product->image ? Storage::disk('public')->url($item->product->image) : asset('images/dimensiona/placeholder.svg') }}" alt="">
                            <div><a class="cart-item__name" href="{{ route('products.show', $item->product) }}">{{ $item->product->name }}</a><p class="cart-item__price">R$ {{ number_format((float) $item->product->price, 2, ',', '.') }}</p><p class="text-muted">Quantidade: {{ $item->quantity }} · Subtotal: R$ {{ number_format((float) $item->product->price * $item->quantity, 2, ',', '.') }}</p></div>
                            <div class="cart-item__actions">
                                <form action="{{ route('cart-items.add', $item) }}" method="POST">@csrf<button class="btn btn--ghost" type="submit">Adicionar item</button></form>
                                <form action="{{ route('cart-items.destroy', $item) }}" method="POST">@csrf @method('DELETE')<button class="link-btn" type="submit">Remover um</button></form>
                            </div>
                    </article>
                @endforeach
                <form id="order-selection" action="{{ route('orders.store') }}" method="POST">@csrf
                    <button class="btn btn--primary" type="submit">Finalizar compra dos selecionados</button>
                </form>
            @endif
        </div>
        <aside class="summary"><h2>Resumo</h2>
            <p class="summary__row"><span>Itens no carrinho</span><span>{{ $cart->cartItems->sum('quantity') }}</span></p>
            <p class="summary__row summary__row--total"><span>Subtotal</span><span>R$ {{ number_format((float) $cart->cartItems->sum(fn ($item) => $item->product->price * $item->quantity), 2, ',', '.') }}</span></p>
            <p class="summary__ship">O frete será calculado ao finalizar o pedido.</p>
            <a class="btn btn--outline btn--block" href="{{ route('products.index') }}">Continuar comprando</a>
        </aside>
    </section>
@endsection
