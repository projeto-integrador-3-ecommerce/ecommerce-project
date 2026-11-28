@props(['product', 'forceNewBadge' => false])

@php
    $oldPrice = $product->old_price ?? $product->compare_at_price ?? null;
    $isRecent = $forceNewBadge || ($product->created_at?->greaterThanOrEqualTo(now()->subDays(14)) ?? false);
@endphp
<article data-product-category="{{ $product->category_id }}" class="group/card rounded-3xl border-2 border-transparent p-1.5 transition duration-200 hover:border-blue focus-within:border-blue">
    <a href="{{ route('products.show', $product) }}" class="relative block overflow-hidden rounded-2xl bg-mist/40 focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-blue">
        <img src="{{ $product->image_url ?: asset('images/dimensiona/placeholder.svg') }}" alt="{{ $product->name }}" loading="lazy" class="aspect-square w-full object-cover transition duration-300 group-hover/card:scale-[1.03]">
        @if ($product->stock < 1)
            <span class="absolute inset-x-2 bottom-2 rounded-full bg-navy/85 px-3 py-1.5 text-center text-xs font-semibold text-white backdrop-blur">Indisponível</span>
        @endif
    </a>
    <div class="px-1.5 pt-3">
        @if ($product->category)
            <p class="truncate text-[11px] text-muted">{{ $product->category->name }}</p>
        @endif
        <div class="flex min-w-0 items-center gap-2">
            @if ($isRecent)
                <x-ui.badge-new />
            @endif
            <h3 class="min-w-0 truncate text-sm font-semibold text-navy">
                <a href="{{ route('products.show', $product) }}" class="rounded-full focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-blue">{{ $product->name }}</a>
            </h3>
        </div>
        <div class="mt-2 flex flex-wrap items-end justify-between gap-2">
            <p class="flex flex-wrap items-baseline gap-2">
                <span class="text-sm font-bold text-navy">R$ {{ number_format((float) $product->price, 2, ',', '.') }}</span>
                @if ($oldPrice)
                    <span class="text-xs text-muted line-through">R$ {{ number_format((float) $oldPrice, 2, ',', '.') }}</span>
                @endif
            </p>
            @auth
                @unless (auth()->user()->is_admin)
                    @if ($product->stock > 0)
                        <form action="{{ route('cart-items.store') }}" method="POST" data-cart-mutation>@csrf
                            <input type="hidden" name="product_id" value="{{ $product->id }}">
                            <input type="hidden" name="quantity" value="1">
                            <button type="submit" aria-label="Adicionar ao carrinho"
                                    class="inline-grid min-h-11 w-11 place-items-center rounded-full border border-navy text-navy transition hover:bg-navy hover:text-white focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-blue disabled:cursor-not-allowed disabled:opacity-50">
                                <svg class="size-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.7" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="M3 4h2l2.1 10.1a2 2 0 0 0 2 1.6h7.8a2 2 0 0 0 2-1.6L20 8H6"/><path d="M12 5v6M9 8h6"/><circle cx="10" cy="20" r="1"/><circle cx="17" cy="20" r="1"/></svg>
                                <span class="sr-only">Adicionar ao carrinho</span>
                            </button>
                        </form>
                    @else
                        <button type="button" disabled class="inline-flex min-h-11 items-center rounded-full border border-muted/40 px-3 text-[11px] font-semibold text-muted">Sem estoque</button>
                    @endif
                @endunless
            @else
                @if ($product->stock > 0)
                    <a href="{{ route('login') }}" class="inline-flex min-h-11 items-center rounded-full border border-navy px-3 text-[11px] font-semibold text-navy transition hover:bg-navy hover:text-white focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-blue">Entre para comprar</a>
                @else
                    <span aria-disabled="true" class="inline-flex min-h-11 items-center rounded-full border border-muted/40 px-3 text-[11px] font-semibold text-muted">Sem estoque</span>
                @endif
            @endauth
        </div>
    </div>
</article>
