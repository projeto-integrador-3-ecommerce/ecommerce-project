@extends('layouts.storefront', ['title' => $product->name])

@section('content')

    <section>
        <article>
            <div><img src="{{ $product->image_url ?: asset('images/dimensiona/placeholder.svg') }}" alt="{{ $product->name }}"></div>
            <div>
                <span>{{ $product->category?->name ?? 'Produto' }}</span>
                <h1>{{ $product->name }}</h1>
                <p>{{ $product->description }}</p>
                <p>R$ {{ number_format((float) $product->price, 2, ',', '.') }}</p>
                @auth
                    @unless (auth()->user()->is_admin)
                        @if ($product->stock > 0)
                            <form action="{{ route('cart-items.store') }}" method="POST" data-cart-mutation>@csrf
                                <input type="hidden" name="product_id" value="{{ $product->id }}">
                                <label><span>Quantidade disponível: {{ $product->stock }}</span><input type="number" name="quantity" value="1" min="1" max="{{ $product->stock }}" required></label>
                                <button type="submit">Adicionar ao carrinho</button>
                            </form>
                        @else
                            <p>Produto sem estoque no momento</p>
                        @endif
                    @endunless
                @else
                    @if ($product->stock > 0)
                        <a href="{{ route('login') }}">Entre para comprar</a>
                    @else
                        <p>Produto sem estoque no momento</p>
                    @endif
                @endauth
                @can('admin')
                    <div><a href="{{ route('products.edit', $product) }}">Editar produto</a>
                        <form action="{{ route('products.destroy', $product) }}" method="POST">@csrf @method('DELETE')<button type="submit">Excluir produto</button></form>
                    </div>
                @endcan
            </div>
        </article>
    </section>

@endsection
