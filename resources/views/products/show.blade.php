@extends('layouts.storefront', ['title' => $product->name])

@section('content')

    <section class="container section">
        <article class="featured">
            <div class="featured__media"><img src="{{ $product->image ? Storage::disk('public')->url($product->image) : asset('images/dimensiona/placeholder.svg') }}" alt="{{ $product->name }}"></div>
            <div class="featured__body">
                <span class="eyebrow">{{ $product->category?->name ?? 'Produto' }}</span>
                <h1>{{ $product->name }}</h1>
                <p>{{ $product->description }}</p>
                <p class="featured__price">R$ {{ number_format((float) $product->price, 2, ',', '.') }}</p>
                @auth
                    @unless (auth()->user()->is_admin)
                        @if ($product->stock > 0)
                            <form action="{{ route('cart-items.store') }}" method="POST" class="inline-form" data-cart-mutation>@csrf
                                <input type="hidden" name="product_id" value="{{ $product->id }}">
                                <label class="field"><span>Quantidade disponível: {{ $product->stock }}</span><input class="input" type="number" name="quantity" value="1" min="1" max="{{ $product->stock }}" required></label>
                                <button class="btn btn--primary" type="submit">Adicionar ao carrinho</button>
                            </form>
                        @else
                            <p class="status-pill">Produto sem estoque no momento</p>
                        @endif
                    @endunless
                @else
                    @if ($product->stock > 0)
                        <a class="btn btn--primary" href="{{ route('login') }}">Entre para comprar</a>
                    @else
                        <p class="status-pill">Produto sem estoque no momento</p>
                    @endif
                @endauth
                @can('admin')
                    <div class="btn-row mt-5"><a class="btn btn--outline" href="{{ route('products.edit', $product) }}">Editar produto</a>
                        <form action="{{ route('products.destroy', $product) }}" method="POST">@csrf @method('DELETE')<button class="btn btn--outline" type="submit">Excluir produto</button></form>
                    </div>
                @endcan
            </div>
        </article>
    </section>

@endsection
