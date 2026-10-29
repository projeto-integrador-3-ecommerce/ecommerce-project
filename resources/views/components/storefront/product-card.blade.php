<article class="product-card" data-product-category="{{ $product->category_id }}">
    <a class="product-card__media" href="{{ route('products.show', $product) }}">
        <img src="{{ $product->image ? Storage::disk('public')->url($product->image) : asset('images/dimensiona/placeholder.svg') }}" alt="{{ $product->name }}" loading="lazy">
        @if ($product->stock < 1)<span class="product-card__tag">Indisponível</span>@endif
    </a>
    <div class="product-card__body">
        @if ($product->category)<p class="product-card__cat">{{ $product->category->name }}</p>@endif
        <h3 class="product-card__name"><a href="{{ route('products.show', $product) }}">{{ $product->name }}</a></h3>
        <p class="product-card__price">R$ {{ number_format((float) $product->price, 2, ',', '.') }}</p>
    </div>
    @auth
        @unless (auth()->user()->is_admin)
            @if ($product->stock > 0)
                <form action="{{ route('cart-items.store') }}" method="POST" data-cart-mutation>@csrf
                    <input type="hidden" name="product_id" value="{{ $product->id }}">
                    <input type="hidden" name="quantity" value="1">
                    <button class="btn btn--ghost btn--block" type="submit">Adicionar ao carrinho</button>
                </form>
            @else
                <button class="btn btn--ghost btn--block" type="button" disabled>Sem estoque</button>
            @endif
        @endunless
    @else
        @if ($product->stock > 0)
            <a class="btn btn--ghost btn--block" href="{{ route('login') }}">Entre para comprar</a>
        @else
            <span class="btn btn--ghost btn--block is-disabled" aria-disabled="true">Sem estoque</span>
        @endif
    @endauth
</article>
