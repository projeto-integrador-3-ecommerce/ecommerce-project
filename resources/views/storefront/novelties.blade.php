@extends('layouts.storefront', ['title' => 'Novidades'])

@section('content')
<section class="rounded-3xl border border-aqua bg-white/80 p-6 shadow-sm sm:p-10">
    <span class="text-xs font-semibold tracking-[0.14em] text-teal uppercase">Acabaram de chegar</span>
    <h1 class="mt-2 text-3xl font-semibold text-navy sm:text-4xl">Novidades</h1>
    <p class="mt-3 text-sm text-muted">Confira os produtos mais recentes do catálogo.</p>
</section>
<section class="mt-10">
    <div class="grid grid-cols-2 gap-4 sm:gap-5 md:grid-cols-3">
        @forelse ($products as $product)
            <x-storefront.product-card :product="$product" :forceNewBadge="true" />
        @empty
            <div class="col-span-full rounded-3xl border border-aqua bg-white p-8 text-center shadow-sm">
                <h2 class="text-xl font-semibold text-navy">Nenhuma novidade por enquanto</h2>
                <p class="mt-2 text-sm text-muted">Novos produtos aparecerão aqui assim que forem cadastrados.</p>
                <x-ui.link href="{{ route('products.index') }}" class="mt-6">Ver catálogo</x-ui.link>
            </div>
        @endforelse
    </div>
</section>
@endsection
