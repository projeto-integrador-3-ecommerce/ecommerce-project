@php($categoryName = $selectedCategory !== 'all' ? $categories->firstWhere('id', (int) $selectedCategory)?->name : null)
@extends('layouts.storefront', ['title' => $categoryName ? 'Catálogo · '.$categoryName : 'Catálogo'])

@section('content')
<section class="rounded-3xl border border-aqua bg-white/80 p-6 shadow-sm sm:p-10">
    <span class="text-xs font-semibold tracking-[0.14em] text-teal uppercase">Encontre o que procura</span>
    <h1 class="mt-2 text-3xl font-semibold text-navy sm:text-4xl">{{ $categoryName ?? 'Catálogo' }}</h1>
    <p class="mt-3 max-w-2xl text-sm text-muted">Peças selecionadas para transformar suas ideias em realidade.</p>
    @can('admin')
        <div class="mt-6 flex flex-wrap gap-3">
            <x-ui.link href="{{ route('products.create') }}" size="sm">Cadastrar produto</x-ui.link>
            <x-ui.link href="{{ route('categories.create') }}" variant="outline" size="sm">Criar categoria</x-ui.link>
        </div>
    @endcan
    <div aria-label="Filtrar produtos por categoria" class="mt-8 flex flex-wrap gap-2">
        <a @if ($selectedCategory === 'all') aria-current="page" @endif href="{{ route('products.index') }}"
           class="inline-flex min-h-11 items-center rounded-full border border-navy px-5 text-xs font-semibold text-navy transition hover:bg-navy hover:text-white focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-blue aria-[current=page]:bg-blue aria-[current=page]:border-blue aria-[current=page]:text-white">Todos</a>
        @foreach ($categories as $category)
            <a @if ($selectedCategory === (string) $category->id) aria-current="page" @endif href="{{ route('products.index', ['category' => $category->id]) }}"
               class="inline-flex min-h-11 items-center rounded-full border border-navy/30 px-5 text-xs font-semibold text-navy transition hover:border-navy hover:bg-navy/5 focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-blue aria-[current=page]:border-blue aria-[current=page]:bg-blue aria-[current=page]:text-white">{{ $category->name }}</a>
        @endforeach
    </div>
</section>

<section class="mt-10">
    <div class="flex flex-wrap items-end justify-between gap-3">
        <h2 class="text-2xl font-semibold text-navy">{{ $categoryName ?? 'Todos os produtos' }}</h2>
        <span class="text-xs text-muted">{{ $products->count() }} {{ $products->count() === 1 ? 'produto' : 'produtos' }}</span>
    </div>
    <div class="mt-6 grid grid-cols-2 gap-4 sm:gap-5 md:grid-cols-3">
        @forelse ($products as $product)
            <x-storefront.product-card :product="$product" />
        @empty
            <div class="col-span-full rounded-3xl border border-aqua bg-white p-8 text-center shadow-sm">
                <h2 class="text-xl font-semibold text-navy">{{ $categoryName ? 'Ainda sem produtos nesta categoria' : 'O catálogo está vazio' }}</h2>
                <p class="mx-auto mt-2 max-w-md text-sm text-muted">{{ $categoryName ? 'Explore outra categoria ou volte para ver todos os produtos.' : 'Assim que produtos forem cadastrados, eles aparecerão aqui.' }}</p>
                @if ($categoryName)
                    <x-ui.link href="{{ route('products.index') }}" class="mt-6">Ver todos os produtos</x-ui.link>
                @endif
            </div>
        @endforelse
    </div>
</section>
@endsection
