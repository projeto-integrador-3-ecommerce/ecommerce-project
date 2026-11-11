@extends('layouts.storefront', ['title' => 'Categorias'])

@section('content')

    <section class="page-head"><div class="container"><span class="eyebrow">Explore a loja</span><h1>Categorias</h1><p>Escolha uma categoria para ver os produtos.</p></div></section>
    <section class="container pb-20">
        @can('admin')
            <p class="mb-6"><a class="btn btn--primary" href="{{ route('categories.create') }}">Criar categoria</a></p>
        @endcan
        <div class="cat-grid">
            @forelse ($categories as $category)
                <div class="cat-cell">
                    <a href="{{ route('products.index', ['category' => $category->id]) }}" class="cat-card">
                        @if ($category->image_url)
                            <img class="cat-card__img object-cover" src="{{ $category->image_url }}" alt="">
                        @else
                            <span class="cat-card__img" aria-hidden="true">{{ str_pad((string) $loop->iteration, 2, '0', STR_PAD_LEFT) }}</span>
                        @endif
                        {{ $category->name }}
                        @if ($category->description)<span class="cat-card__desc">{{ $category->description }}</span>@endif
                        <span class="cat-card__desc">{{ $category->products_count }} produtos</span>
                    </a>
                    @can('admin')
                        <div class="cat-cell__actions">
                            <a class="btn btn--ghost" href="{{ route('categories.edit', $category) }}">Editar</a>
                            <form action="{{ route('categories.destroy', $category) }}" method="POST" onsubmit="return confirm('Excluir a categoria {{ addslashes($category->name) }}?')">
                                @csrf
                                @method('DELETE')
                                <button class="btn btn--ghost" type="submit">Excluir</button>
                            </form>
                        </div>
                    @endcan
                </div>
            @empty
                <div class="empty-state"><h2>Nenhuma categoria cadastrada</h2><p>As categorias aparecerão aqui quando forem criadas.</p>@can('admin')<a class="btn btn--outline" href="{{ route('categories.create') }}">Criar a primeira categoria</a>@endcan</div>
            @endforelse
        </div>
    </section>

@endsection
