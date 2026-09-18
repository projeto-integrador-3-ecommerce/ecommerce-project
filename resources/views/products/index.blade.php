@extends('layouts.app')
<!-- herdando html e css do layout -->
@section('content')

    <h1>Products</h1>

    <ul>
        @foreach($products as $product)
            <li>
                {{ $product->name }} - ${{ $product->price }}

                <form action="{{ route('products.destroy', $product) }}" method="POST">
                    @csrf
                    @method('DELETE')
                    <button type="submit">Excluir Produto</button>
                </form>

                <a href="{{ route('products.show', $product) }}">Visualizar Produto</a>

                <form action="{{ route('cart-items.store', $product->id) }}" method="POST">
                    @csrf
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

@endsection