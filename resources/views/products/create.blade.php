@extends('layouts.storefront', ['title' => 'Cadastrar produto'])

@section('content')
<section class="rounded-3xl border border-aqua bg-white/80 p-6 shadow-sm sm:p-10">
    <span class="text-xs font-semibold tracking-[0.14em] text-teal uppercase">Administração da loja</span>
    <h1 class="mt-2 text-3xl font-semibold text-navy sm:text-4xl">Cadastrar produto</h1>
    <p class="mt-3 text-sm text-muted">Preencha os dados para adicionar um novo produto ao catálogo.</p>
    <div class="mt-6 flex flex-wrap gap-3">
        <x-ui.link href="{{ route('products.index') }}" variant="outline" size="sm">Voltar ao catálogo</x-ui.link>
        <x-ui.link href="{{ route('categories.create') }}" variant="ghost" size="sm">Cadastrar categoria</x-ui.link>
    </div>
</section>
<section class="mt-8">
    @include('products._form', ['product' => null])
</section>
@endsection
