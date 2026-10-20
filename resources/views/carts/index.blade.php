@extends('layouts.app')

@section('content')

    <h1>Seu carrinho</h1>

    @if($errors->any())
        <div role="alert">
            <p>{{ $errors->first() }}</p>
        </div>
    @endif

    @if($cart->cartItems->isEmpty())
        <p>Seu carrinho está vazio</p>
    @else
        @foreach($cart->cartItems as $item)

        <div>
            <input
                type="checkbox"
                name="cart_item_ids[]"
                value="{{ $item->id }}"
                id="cart-item-{{ $item->id }}"
                form="order-selection"
            >
            <label for="cart-item-{{ $item->id }}">Selecionar para a compra</label>
            <h2>{{ $item->product->name }}</h2>
            <p>Preço unitário: R$ {{ number_format((float) $item->product->price, 2, ',', '.') }}</p>
            <p>Quantidade: {{ $item->quantity }}</p>
            <p>Subtotal: R$ {{ number_format((float) $item->product->price * $item->quantity, 2, ',', '.') }}</p>
            <form action="{{ route('cart-items.destroy', $item)}}" method="POST">
                @csrf
                @method('DELETE')
                <button type="submit">Remover Item</button>
            </form>
            <form action="{{ route('cart-items.add', $item)}}" method="POST">
                @csrf
                <button type="submit">Adicionar Item</button>
            </form>
        </div>

        <hr>
    @endforeach
        <form id="order-selection" action="{{ route('orders.store') }}" method="POST">
            @csrf
            <button type="submit">Finalizar compra dos selecionados</button>
        </form>
    @endif

    <a href="{{ route('products.index') }}">Continuar comprando</a>
@endsection