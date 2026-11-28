@extends('layouts.storefront', ['title' => 'Pesquisar'])

@section('content')
<section class="rounded-3xl border border-aqua bg-white/80 p-6 shadow-sm sm:p-10">
    <span class="text-xs font-semibold tracking-[0.14em] text-teal uppercase">Encontre sua próxima peça</span>
    <h1 class="mt-2 text-3xl font-semibold text-navy sm:text-4xl">Pesquisar</h1>
    <form action="{{ route('storefront.search') }}" method="GET" role="search" data-async-search class="mt-6 flex min-h-14 items-center gap-3 rounded-full border border-navy bg-white py-1 pr-1 pl-5 shadow-sm">
        <svg class="size-5 shrink-0 text-navy" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" aria-hidden="true"><circle cx="11" cy="11" r="7"/><path d="m20 20-3.5-3.5"/></svg>
        <input type="search" name="q" id="search-input" value="{{ $term }}" placeholder="O que você está procurando?" aria-label="Pesquisar produtos" class="min-w-0 flex-1 bg-transparent text-sm text-navy placeholder:text-muted focus:outline-none">
        <button type="submit" class="inline-grid size-11 shrink-0 place-items-center rounded-full bg-navy text-white transition hover:bg-navy/90 focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-blue" aria-label="Buscar">
            <svg class="size-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" aria-hidden="true"><path d="m5 12 14 0M13 6l6 6-6 6"/></svg>
        </button>
    </form>
</section>

<section class="mt-10 space-y-10">
    @if ($term === '')
        <div class="rounded-3xl border border-aqua bg-white/80 p-6 shadow-sm">
            <h2 class="text-sm font-semibold text-navy">Buscas populares</h2>
            <div class="mt-4 flex flex-wrap gap-2">
                @foreach (['Impressão 3D', 'Decoração', 'Acessórios'] as $popularTerm)
                    <button type="button" data-search-term data-term="{{ $popularTerm }}" class="inline-flex min-h-11 items-center rounded-full border border-navy/30 px-5 text-xs font-semibold text-navy transition hover:border-navy hover:bg-navy/5 focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-blue">{{ $popularTerm }}</button>
                @endforeach
            </div>
        </div>
        <div>
            <h2 class="text-sm font-semibold text-navy">Explorar por categoria</h2>
            <div class="mt-4 grid grid-cols-2 gap-3 sm:grid-cols-3 lg:grid-cols-6">
                @foreach ($categories as $category)
                    <a href="{{ route('products.index', ['category' => $category->id]) }}" class="flex min-h-32 flex-col justify-between overflow-hidden rounded-3xl border border-aqua bg-white p-4 transition hover:-translate-y-1 hover:border-blue focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-blue">
                        @if ($category->image_url)
                            <img src="{{ $category->image_url }}" alt="" class="h-14 w-full rounded-2xl object-cover">
                        @else
                            <span aria-hidden="true" class="text-2xl font-bold text-navy/25">{{ str_pad((string) $loop->iteration, 2, '0', STR_PAD_LEFT) }}</span>
                        @endif
                        <span class="mt-3 truncate text-xs font-semibold text-navy">{{ $category->name }}</span>
                    </a>
                @endforeach
            </div>
        </div>
    @endif
    <div>
        <h2 class="text-xl font-semibold text-navy">{{ $term === '' ? 'Todos os produtos' : 'Resultados para “'.$term.'”' }}</h2>
        <div class="mt-6 grid grid-cols-2 gap-4 sm:gap-5 md:grid-cols-3">
            @forelse ($products as $product)
                <x-storefront.product-card :product="$product" />
            @empty
                @if ($term !== '')
                    <div class="col-span-full rounded-3xl border border-aqua bg-white p-8 text-center shadow-sm">
                        <h3 class="text-lg font-semibold text-navy">Nenhum produto encontrado</h3>
                        <p class="mt-2 text-sm text-muted">Tente outro termo ou explore as categorias acima.</p>
                    </div>
                @endif
            @endforelse
        </div>
    </div>
</section>
@endsection
