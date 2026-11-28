@extends('layouts.storefront', ['title' => 'Editar produto'])

@section('content')
<section class="rounded-3xl border border-aqua bg-white/80 p-6 shadow-sm sm:p-10">
    <span class="text-xs font-semibold tracking-[0.14em] text-teal uppercase">Administração da loja</span>
    <h1 class="mt-2 text-3xl font-semibold text-navy sm:text-4xl">Editar produto</h1>
    <p class="mt-3 text-sm text-muted">Atualize os dados de {{ $product->name }} no catálogo.</p>
    <div class="mt-6 flex flex-wrap gap-3">
        <x-ui.link href="{{ route('products.show', $product) }}" variant="outline" size="sm">Ver produto</x-ui.link>
        <x-ui.link href="{{ route('products.index') }}" variant="ghost" size="sm">Voltar ao catálogo</x-ui.link>
    </div>
</section>
<section class="mt-8">
    @include('products._form', ['product' => $product])
</section>
@endsection
