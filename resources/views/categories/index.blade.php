@extends('layouts.storefront', ['title' => 'Categorias'])

@section('content')
<section class="rounded-3xl border border-aqua bg-white/80 p-6 shadow-sm sm:p-10">
    <span class="text-xs font-semibold tracking-[0.14em] text-teal uppercase">Navegue pelo catálogo</span>
    <h1 class="mt-2 text-3xl font-semibold text-navy sm:text-4xl">Categorias</h1>
    <p class="mt-3 text-sm text-muted">Escolha uma categoria para ver os produtos.</p>
</section>

<section class="mt-8">
    @can('admin')
        <p class="mb-5"><x-ui.link href="{{ route('categories.create') }}" size="sm">Criar categoria</x-ui.link></p>
    @endcan
    <div class="grid grid-cols-2 gap-4 sm:gap-5 md:grid-cols-3">
        @forelse ($categories as $category)
            <article class="flex flex-col overflow-hidden rounded-3xl border border-aqua bg-white shadow-sm transition hover:-translate-y-1 hover:border-blue focus-within:border-blue">
                <a href="{{ route('products.index', ['category' => $category->id]) }}" class="flex flex-1 flex-col p-4 focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-blue">
                    @if ($category->image_url)
                        <img src="{{ $category->image_url }}" alt="" class="aspect-[4/3] w-full rounded-2xl bg-mist/40 object-cover">
                    @else
                        <span aria-hidden="true" class="grid aspect-[4/3] w-full place-items-center rounded-2xl bg-gradient-to-br from-aqua/50 via-bg to-mist text-4xl font-bold text-navy/25">{{ str_pad((string) $loop->iteration, 2, '0', STR_PAD_LEFT) }}</span>
                    @endif
                    <h2 class="mt-4 text-sm font-semibold text-navy">{{ $category->name }}</h2>
                    @if ($category->description)
                        <span class="mt-1 line-clamp-2 text-xs text-muted">{{ $category->description }}</span>
                    @endif
                    <span class="mt-3 text-[11px] font-semibold text-teal">{{ $category->products_count }} produtos</span>
                </a>
                @can('admin')
                    <div class="flex flex-wrap gap-2 border-t border-aqua px-4 py-3">
                        <x-ui.link href="{{ route('categories.edit', $category) }}" variant="outline" size="sm">Editar</x-ui.link>
                        <form action="{{ route('categories.destroy', $category) }}" method="POST" onsubmit="return confirm('Excluir a categoria {{ addslashes($category->name) }}?')">
                            @csrf
                            @method('DELETE')
                            <x-ui.button type="submit" variant="danger" size="sm">Excluir</x-ui.button>
                        </form>
                    </div>
                @endcan
            </article>
        @empty
            <div class="col-span-full rounded-3xl border border-aqua bg-white p-8 text-center shadow-sm">
                <h2 class="text-xl font-semibold text-navy">Nenhuma categoria cadastrada</h2>
                <p class="mt-2 text-sm text-muted">As categorias aparecerão aqui quando forem criadas.</p>
                @can('admin')<x-ui.link href="{{ route('categories.create') }}" class="mt-6">Criar a primeira categoria</x-ui.link>@endcan
            </div>
        @endforelse
    </div>
</section>
@endsection
