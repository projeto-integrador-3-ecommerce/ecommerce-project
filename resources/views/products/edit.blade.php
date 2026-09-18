@extends('layouts.app')

@section('content')

```
<h1>Products</h1>

<ul>
    @foreach($products as $product)

        <li>
            {{ $product->name }} - ${{ $product->price }}
        </li>

        <!-- Formulário para excluir -->
        <form action="/products/{{ $product->id }}" method="POST">
            @csrf
            @method('DELETE')

            <button type="submit">
                Excluir Produto
            </button>
        </form>

        <!-- Visualizar produto -->
        <a href="{{ route('products.show', $product) }}">
            Visualizar Produto
        </a>

        <!-- Formulário para adicionar ao carrinho -->
        <form action="{{ route('cart-items.store', $product->id) }}" method="POST">
            @csrf
            <label>Qtde:</label>

            <input
                type="number"
                name="quantity"
                value="1"
                min="1"
            >

            <button type="submit">
                Adicionar ao carrinho
            </button>
        </form>

    @endforeach
</ul>

<a href="/products/create">
    Cadastrar Produto
</a>

<a href="/categories">
    Visualizar categorias
</a>

<a href="/cart">
    Visualizar Carrinho
</a>
```

@endsection
