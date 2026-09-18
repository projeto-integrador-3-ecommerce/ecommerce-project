@extends('layouts.app')

@section('content')

    <h1>Seu carrinho</h1>
    @if($cart->cartItems->isEmpty())
        <p>Seu carrinho está vazio</p>
    @else
        @foreach($cart->cartItems as $item)

        <div>
            <h2>{{$item->product->name}}</h2>
            <p>Preço: ${{$item->product->price}}</p>
            <p>Quantidade: ${{$item->quantity}}</p>
            <p>Subtotal: ${{$item->product->price * $item->quantity}}</p>
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
    @endif
    <form action="{{ route('orders.store') }}" method="POST">
        @csrf
        <button type="submit">Finalizar Compra</button>
    </form>
    <button><a href="/products"> < Continuar Comprando</a></button>
@endsection