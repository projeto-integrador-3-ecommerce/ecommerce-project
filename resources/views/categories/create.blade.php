@extends('layouts.storefront', ['title' => 'Criar categoria'])

@section('content')
<section class="rounded-3xl border border-aqua bg-white/80 p-6 shadow-sm sm:p-10">
    <span class="text-xs font-semibold tracking-[0.14em] text-teal uppercase">Administração da loja</span>
    <h1 class="mt-2 text-3xl font-semibold text-navy sm:text-4xl">Criar categoria</h1>
    <p class="mt-3 text-sm text-muted">Preencha os dados para organizar melhor o catálogo da loja.</p>
    <div class="mt-6 flex flex-wrap gap-3">
        <x-ui.link href="{{ route('categories.index') }}" variant="outline" size="sm">Voltar para categorias</x-ui.link>
        <x-ui.link href="{{ route('products.create') }}" variant="ghost" size="sm">Cadastrar produto</x-ui.link>
    </div>
</section>
<section class="mt-8">
    @include('categories._form', ['category' => null])
</section>
@endsection
