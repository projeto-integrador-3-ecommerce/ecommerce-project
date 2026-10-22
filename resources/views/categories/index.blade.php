@extends('layouts.storefront', ['title' => 'Categorias'])

@section('content')

    <section class="page-head"><div class="container"><span class="eyebrow">Explore a loja</span><h1>Categorias</h1><p>Escolha uma categoria para ver os produtos.</p></div></section>
    <section class="container pb-20">
        <div class="cat-grid">
            @forelse ($categories as $category)
                <a href="{{ route('products.index', ['category' => $category->id]) }}" class="cat-card">
                    <span class="cat-card__img" aria-hidden="true">{{ str_pad((string) $loop->iteration, 2, '0', STR_PAD_LEFT) }}</span>{{ $category->name }}
                    <span class="cat-card__desc">{{ $category->products_count }} produtos</span>
                </a>
            @empty
                <div class="empty-state"><h2>Nenhuma categoria cadastrada</h2><p>As categorias aparecerão aqui quando forem criadas.</p></div>
            @endforelse
        </div>
        @can('admin')<p class="mt-8"><a class="btn btn--outline" href="{{ route('categories.create') }}">Criar categoria</a></p>@endcan
    </section>

@endsection
