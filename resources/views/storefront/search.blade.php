@extends('layouts.storefront', ['title' => 'Pesquisar'])

@section('content')
<section class="page-head page-head--center">
    <div class="container"><h1>Pesquisar</h1>
        <form class="search-box mt-5" action="{{ route('storefront.search') }}" method="GET" role="search">
            <svg viewBox="0 0 24 24" aria-hidden="true"><circle cx="11" cy="11" r="7"/><path d="m20 20-3.5-3.5"/></svg>
            <input class="input" type="search" name="q" id="search-input" value="{{ $term }}" placeholder="O que você está procurando?" aria-label="Pesquisar produtos">
        </form>
    </div>
</section>
<section class="container pb-20">
    @if ($term === '')
        <div class="search-block"><p class="search-block__title">Buscas populares</p><div class="chip-row">
            @foreach (['Impressão 3D', 'Decoração', 'Acessórios'] as $popularTerm)<button type="button" class="chip js-search-term" data-term="{{ $popularTerm }}">{{ $popularTerm }}</button>@endforeach
        </div></div>
        <div class="search-block"><p class="search-block__title">Explorar por categoria</p><div class="cat-row">
            @foreach ($categories as $category)<a href="{{ route('products.index', ['category' => $category->id]) }}" class="cat-card"><span class="cat-card__img" aria-hidden="true">Imagem</span>{{ $category->name }}</a>@endforeach
        </div></div>
    @endif
    <div class="search-block"><p class="search-block__title">{{ $term === '' ? 'Todos os produtos' : 'Resultados para “'.$term.'”' }}</p>
        <div class="product-grid">
            @forelse ($products as $product)<x-storefront.product-card :product="$product" />
            @empty
                @if ($term !== '')<p class="empty-note">Nenhum produto encontrado. Tente outro termo ou explore as categorias acima.</p>@endif
            @endforelse
        </div>
    </div>
</section>
@endsection
