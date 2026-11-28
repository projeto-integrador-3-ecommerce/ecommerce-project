@extends('layouts.storefront', ['title' => 'Pesquisar'])

@section('content')
<section>
    <div><h1>Pesquisar</h1>
        <form action="{{ route('storefront.search') }}" method="GET" role="search" data-async-search>
            <svg viewBox="0 0 24 24" aria-hidden="true"><circle cx="11" cy="11" r="7"/><path d="m20 20-3.5-3.5"/></svg>
            <input type="search" name="q" id="search-input" value="{{ $term }}" placeholder="O que você está procurando?" aria-label="Pesquisar produtos">
        </form>
    </div>
</section>
<section>
    @if ($term === '')
        <div><p>Buscas populares</p><div>
            @foreach (['Impressão 3D', 'Decoração', 'Acessórios'] as $popularTerm)<button type="button" data-search-term data-term="{{ $popularTerm }}">{{ $popularTerm }}</button>@endforeach
        </div></div>
        <div><p>Explorar por categoria</p><div>
            @foreach ($categories as $category)
                <a href="{{ route('products.index', ['category' => $category->id]) }}">
                    @if ($category->image_url)
                        <img src="{{ $category->image_url }}" alt="">
                    @else
                        <span aria-hidden="true">{{ str_pad((string) $loop->iteration, 2, '0', STR_PAD_LEFT) }}</span>
                    @endif
                    {{ $category->name }}
                </a>
            @endforeach
        </div></div>
    @endif
    <div><p>{{ $term === '' ? 'Todos os produtos' : 'Resultados para “'.$term.'”' }}</p>
        <div>
            @forelse ($products as $product)<x-storefront.product-card :product="$product" />
            @empty
                @if ($term !== '')<p>Nenhum produto encontrado. Tente outro termo ou explore as categorias acima.</p>@endif
            @endforelse
        </div>
    </div>
</section>
@endsection
