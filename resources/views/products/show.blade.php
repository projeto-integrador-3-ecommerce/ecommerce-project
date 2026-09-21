@extends('layouts.app')

@section('content')

    <h1>{{$product->name}}</h1>
    <p>Price: ${{$product->price}}</p>
    <p>Description: {{$product->description}}</p>
    <form action="{{ route('cart-items.store') }}" method="POST">
        @csrf
        <input type="hidden" name="product_id" value="{{ $product->id }}">
        <label>Qtde:</label>
        <input type="number" name="quantity" value="1" min="1">
        <button type="submit">Adicionar ao carrinho</button>
    </form>
    <form action="{{ route('products.destroy', $product) }}" method="POST">
        @csrf
        @method('DELETE')
        <button type="submit">Excluir Produto</button>
    </form>

    <button><a href="{{ route('products.edit', $product)}}">Editar Produto</a></button>

@endsection