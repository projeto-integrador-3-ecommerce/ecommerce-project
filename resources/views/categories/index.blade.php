@extends('layouts.storefront', ['title' => 'Categorias'])

@section('content')

    <section><div><span>Explore a loja</span><h1>Categorias</h1><p>Escolha uma categoria para ver os produtos.</p></div></section>
    <section>
        @can('admin')
            <p><a href="{{ route('categories.create') }}">Criar categoria</a></p>
        @endcan
        <div>
            @forelse ($categories as $category)
                <div>
                    <a href="{{ route('products.index', ['category' => $category->id]) }}">
                        @if ($category->image_url)
                            <img src="{{ $category->image_url }}" alt="">
                        @else
                            <span aria-hidden="true">{{ str_pad((string) $loop->iteration, 2, '0', STR_PAD_LEFT) }}</span>
                        @endif
                        {{ $category->name }}
                        @if ($category->description)<span>{{ $category->description }}</span>@endif
                        <span>{{ $category->products_count }} produtos</span>
                    </a>
                    @can('admin')
                        <div>
                            <a href="{{ route('categories.edit', $category) }}">Editar</a>
                            <form action="{{ route('categories.destroy', $category) }}" method="POST" onsubmit="return confirm('Excluir a categoria {{ addslashes($category->name) }}?')">
                                @csrf
                                @method('DELETE')
                                <button type="submit">Excluir</button>
                            </form>
                        </div>
                    @endcan
                </div>
            @empty
                <div><h2>Nenhuma categoria cadastrada</h2><p>As categorias aparecerão aqui quando forem criadas.</p>@can('admin')<a href="{{ route('categories.create') }}">Criar a primeira categoria</a>@endcan</div>
            @endforelse
        </div>
    </section>

@endsection
