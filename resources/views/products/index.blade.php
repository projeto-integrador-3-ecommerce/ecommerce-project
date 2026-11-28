@php($categoryName = $selectedCategory !== 'all' ? $categories->firstWhere('id', (int) $selectedCategory)?->name : null)
@extends('layouts.storefront', ['title' => $categoryName ? 'Catálogo · '.$categoryName : 'Catálogo'])

@section('content')
<section>
    <div>
        <span>Encontre o que procura</span>
        <h1>{{ $categoryName ?? 'Catálogo' }}</h1>
        <p>Peças selecionadas para transformar suas ideias em realidade.</p>
        @can('admin')
            <div>
                <a href="{{ route('products.create') }}">Cadastrar produto</a>
                <a href="{{ route('categories.create') }}">Criar categoria</a>
            </div>
        @endcan
        <div aria-label="Filtrar produtos por categoria">
            <a @if ($selectedCategory === 'all') aria-current="page" @endif href="{{ route('products.index') }}" @if ($selectedCategory === 'all') aria-current="page" @endif>Todos</a>
            @foreach ($categories as $category)
                <a @if ($selectedCategory === (string) $category->id) aria-current="page" @endif href="{{ route('products.index', ['category' => $category->id]) }}" @if ($selectedCategory === (string) $category->id) aria-current="page" @endif>{{ $category->name }}</a>
            @endforeach
        </div>
    </div>
</section>
<section>
    <div><h2>{{ $categoryName ?? 'Todos os produtos' }}</h2><span>{{ $products->count() }} {{ $products->count() === 1 ? 'produto' : 'produtos' }}</span></div>
    <div>
        @forelse ($products as $product)
            <x-storefront.product-card :product="$product" />
        @empty
            <div><h2>{{ $categoryName ? 'Ainda sem produtos nesta categoria' : 'O catálogo está vazio' }}</h2><p>{{ $categoryName ? 'Explore outra categoria ou volte para ver todos os produtos.' : 'Assim que produtos forem cadastrados, eles aparecerão aqui.' }}</p>@if ($categoryName)<a href="{{ route('products.index') }}">Ver todos os produtos</a>@endif</div>
        @endforelse
    </div>
</section>
@endsection
