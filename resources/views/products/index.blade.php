@php($categoryName = $selectedCategory !== 'all' ? $categories->firstWhere('id', (int) $selectedCategory)?->name : null)
@extends('layouts.storefront', ['title' => $categoryName ? 'Catálogo · '.$categoryName : 'Catálogo'])

@section('content')
<section class="page-head">
    <div class="container">
        <span class="eyebrow">Encontre o que procura</span>
        <h1>{{ $categoryName ?? 'Catálogo' }}</h1>
        <p>Peças selecionadas para transformar suas ideias em realidade.</p>
        <div class="chip-row mt-5" aria-label="Filtrar produtos por categoria">
            <a class="chip {{ $selectedCategory === 'all' ? 'is-active' : '' }}" href="{{ route('products.index') }}" @if ($selectedCategory === 'all') aria-current="page" @endif>Todos</a>
            @foreach ($categories as $category)
                <a class="chip {{ $selectedCategory === (string) $category->id ? 'is-active' : '' }}" href="{{ route('products.index', ['category' => $category->id]) }}" @if ($selectedCategory === (string) $category->id) aria-current="page" @endif>{{ $category->name }}</a>
            @endforeach
        </div>
    </div>
</section>
<section class="container pb-20">
    <div class="section__head"><h2 class="section__title">{{ $categoryName ?? 'Todos os produtos' }}</h2><span class="section__link text-faint">{{ $products->count() }} {{ $products->count() === 1 ? 'produto' : 'produtos' }}</span></div>
    <div class="product-grid">
        @forelse ($products as $product)
            <x-storefront.product-card :product="$product" />
        @empty
            <div class="empty-state"><h2>{{ $categoryName ? 'Ainda sem produtos nesta categoria' : 'O catálogo está vazio' }}</h2><p>{{ $categoryName ? 'Explore outra categoria ou volte para ver todos os produtos.' : 'Assim que produtos forem cadastrados, eles aparecerão aqui.' }}</p>@if ($categoryName)<a class="btn btn--outline" href="{{ route('products.index') }}">Ver todos os produtos</a>@endif</div>
        @endforelse
    </div>
</section>
@endsection
