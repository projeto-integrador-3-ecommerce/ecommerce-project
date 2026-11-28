<article data-product-category="{{ $product->category_id }}">
    <a href="{{ route('products.show', $product) }}">
        <img src="{{ $product->image_url ?: asset('images/dimensiona/placeholder.svg') }}" alt="{{ $product->name }}" loading="lazy">
        @if ($product->stock < 1)<span>Indisponível</span>@endif
    </a>
    <div>
        @if ($product->category)<p>{{ $product->category->name }}</p>@endif
        <h3><a href="{{ route('products.show', $product) }}">{{ $product->name }}</a></h3>
        <p>R$ {{ number_format((float) $product->price, 2, ',', '.') }}</p>
    </div>
    @auth
        @unless (auth()->user()->is_admin)
            @if ($product->stock > 0)
                <form action="{{ route('cart-items.store') }}" method="POST" data-cart-mutation>@csrf
                    <input type="hidden" name="product_id" value="{{ $product->id }}">
                    <input type="hidden" name="quantity" value="1">
                    <button type="submit">Adicionar ao carrinho</button>
                </form>
            @else
                <button type="button" disabled>Sem estoque</button>
            @endif
        @endunless
    @else
        @if ($product->stock > 0)
            <a href="{{ route('login') }}">Entre para comprar</a>
        @else
            <span aria-disabled="true">Sem estoque</span>
        @endif
    @endauth
</article>
