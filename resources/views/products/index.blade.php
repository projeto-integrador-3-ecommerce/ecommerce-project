@extends('layouts.app')
<!-- herdando html e css do layout -->
@section('content')

    <h1>Products</h1>

    <ul>
        @foreach($products as $product)
            <li>
                {{ $product->name }} - ${{ $product->price }}

                <a href="{{ route('products.show', $product) }}">Visualizar Produto</a>

                @auth
                    <form action="{{ route('cart-items.store') }}" method="POST">
                        @csrf
                        <input type="hidden" name="product_id" value="{{ $product->id }}">
                        <label>Quantidade:</label>
                        <input type="number" name="quantity" value="1" min="1">
                        <button type="submit">Adicionar ao carrinho</button>
                    </form>
                @endauth
            </li>
        @endforeach
    </ul>

    <a href="{{ route('categories.index') }}">Visualizar categorias</a>
    @auth
        <a href="{{ route('cart.index') }}">Visualizar carrinho</a>
        <a href="{{ route('addresses.index') }}">Meus endereços</a>
        <a href="{{ route('orders.index') }}">Meus pedidos</a>
    @endauth
    @can('admin')
        <a href="{{ route('products.create') }}">Cadastrar produto</a>
        <a href="{{ route('users.index') }}">Usuários cadastrados</a>
        <a href="{{ route('setting.create') }}">Configurações</a>
    @endcan

@endsection