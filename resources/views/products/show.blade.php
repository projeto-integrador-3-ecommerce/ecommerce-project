@php($oldPrice = $product->old_price ?? $product->compare_at_price ?? null)
@extends('layouts.storefront', ['title' => $product->name])

@section('content')
<nav aria-label="Trilha de navegação" class="mb-6 flex flex-wrap items-center gap-2 text-xs text-muted">
    <a href="{{ route('storefront.home') }}" class="rounded-full px-2 py-1 transition hover:text-navy focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-blue">Página Inicial</a>
    <span aria-hidden="true">›</span>
    <a href="{{ route('products.index') }}" class="rounded-full px-2 py-1 transition hover:text-navy focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-blue">Produtos</a>
    <span aria-hidden="true">›</span>
    <span class="font-semibold text-navy">{{ $product->name }}</span>
</nav>

<section class="grid gap-8 lg:grid-cols-2 lg:gap-12">
    <div class="overflow-hidden rounded-3xl border border-aqua bg-white p-4 shadow-sm sm:p-6">
        <img src="{{ $product->image_url ?: asset('images/dimensiona/placeholder.svg') }}" alt="{{ $product->name }}" class="aspect-square w-full rounded-3xl object-cover bg-mist/40">
    </div>
    <article class="rounded-3xl border border-aqua bg-white p-6 shadow-sm sm:p-8">
        <x-ui.badge variant="blue">{{ $product->category?->name ?? 'Produto' }}</x-ui.badge>
        <h1 class="mt-4 text-3xl font-semibold text-navy sm:text-4xl">{{ $product->name }}</h1>
        <p class="mt-4 text-sm leading-relaxed text-muted">{{ $product->description }}</p>
        <p class="mt-6 flex flex-wrap items-baseline gap-3">
            <span class="text-2xl font-bold text-navy">R$ {{ number_format((float) $product->price, 2, ',', '.') }}</span>
            @if ($oldPrice)
                <span class="text-sm text-muted line-through">R$ {{ number_format((float) $oldPrice, 2, ',', '.') }}</span>
            @endif
        </p>
        <div class="mt-6 border-t border-aqua pt-6">
            @auth
                @unless (auth()->user()->is_admin)
                    @if ($product->stock > 0)
                        <form action="{{ route('cart-items.store') }}" method="POST" data-cart-mutation class="space-y-5">@csrf
                            <input type="hidden" name="product_id" value="{{ $product->id }}">
                            <x-ui.input label="Quantidade disponível: {{ $product->stock }}" type="number" name="quantity" value="1" min="1" max="{{ $product->stock }}" required class="max-w-40" />
                            <x-ui.button type="submit" size="lg">
                                <svg class="size-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.7" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="M3 4h2l2.1 10.1a2 2 0 0 0 2 1.6h7.8a2 2 0 0 0 2-1.6L20 8H6"/><path d="M12 5v6M9 8h6"/><circle cx="10" cy="20" r="1"/><circle cx="17" cy="20" r="1"/></svg>
                                Adicionar ao carrinho
                            </x-ui.button>
                        </form>
                    @else
                        <p class="inline-flex rounded-full bg-muted/20 px-4 py-2 text-sm font-semibold text-navy">Produto sem estoque no momento</p>
                    @endif
                @endunless
            @else
                @if ($product->stock > 0)
                    <x-ui.link href="{{ route('login') }}" size="lg">Entre para comprar</x-ui.link>
                @else
                    <p class="inline-flex rounded-full bg-muted/20 px-4 py-2 text-sm font-semibold text-navy">Produto sem estoque no momento</p>
                @endif
            @endauth
        </div>
        @can('admin')
            <div class="mt-8 flex flex-wrap gap-3 border-t border-aqua pt-6">
                <x-ui.link href="{{ route('products.edit', $product) }}" variant="outline" size="sm">Editar produto</x-ui.link>
                <form action="{{ route('products.destroy', $product) }}" method="POST">@csrf @method('DELETE')<x-ui.button type="submit" variant="danger" size="sm">Excluir produto</x-ui.button></form>
            </div>
        @endcan
    </article>
</section>
@endsection
