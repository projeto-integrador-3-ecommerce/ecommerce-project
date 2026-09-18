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
        </div>

        <hr>
    @endforeach
    @endif
    <button><a href="/products"> < Continuar Comprando</a></button>
@endsection