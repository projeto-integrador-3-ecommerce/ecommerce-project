@extends('layouts.storefront', ['title' => 'Catálogo'])

@section('content')
<section class="page-head">
    <div class="container">
        <span class="eyebrow">Encontre o que procura</span>
        <h1>Catálogo</h1>
        <div class="chip-row mt-5" aria-label="Filtrar produtos por categoria">
            <a class="chip" href="{{ route('products.index') }}" @if ($selectedCategory === 'all') aria-current="page" @endif>Todos</a>
            @foreach ($categories as $category)
                <a class="chip" href="{{ route('products.index', ['category' => $category->id]) }}" @if ($selectedCategory === (string) $category->id) aria-current="page" @endif>{{ $category->name }}</a>
            @endforeach
        </div>
    </div>
</section>
<section class="container pb-20">
    <div class="section__head"><h2 class="section__title">Todos os produtos</h2><span class="section__link text-faint">{{ $products->count() }} produtos</span></div>
    <div class="product-grid">
        @forelse ($products as $product)
            <x-storefront.product-card :product="$product" />
        @empty
            <div class="empty-state"><h2>O catálogo está vazio</h2><p>Assim que produtos forem cadastrados, eles aparecerão aqui.</p></div>
        @endforelse
    </div>
</section>
@endsection
