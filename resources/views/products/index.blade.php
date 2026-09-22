@extends('layouts.app')
<!-- herdando html e css do layout -->
@section('content')

    <h1>Products</h1>

    <ul>
        @foreach($products as $product)
            <li>
                {{ $product->name }} - ${{ $product->price }}

                <a href="{{ route('products.show', $product) }}">Visualizar Produto</a>

                <form action="{{ route('cart-items.store') }}" method="POST">
                    @csrf
                    <input type="hidden" name="product_id" value="{{ $product->id }}">
                    <label>Quantidade:</label>
                    <input type="number" name="quantity" value="1" min="1">
                    <button type="submit">Adicionar ao carrinho</button>
                </form>
            </li>
        @endforeach
    </ul>

    <button><a href="/products/create">Cadastrar Produto</a></button>
    <button><a href="/categories">Visualizar categorias</a></button>
    <button><a href="/cart">Visualizar Carrinho</a></button>
    <button><a href="/addresses">Meus Endereços</a></button>
    <button><a href="/dashboard">Dashboard de Pedidos</a></button>
    <button><a href="">Usuários cadastrados</a></button>

@endsection