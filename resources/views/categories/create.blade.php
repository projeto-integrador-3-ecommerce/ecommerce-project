@extends('layouts.storefront', ['title' => 'Criar categoria'])

@section('content')
<section class="page-head">
    <div class="container">
        <span class="eyebrow">Administração da loja</span>
        <h1>Criar categoria</h1>
        <p>Preencha os dados para organizar melhor o catálogo da loja.</p>
        <div class="admin-page-actions">
            <a class="btn btn--outline" href="{{ route('categories.index') }}">Voltar para categorias</a>
            <a class="btn btn--ghost" href="{{ route('products.create') }}">Cadastrar produto</a>
        </div>
    </div>
</section>
<section class="container pb-20">
    @include('categories._form', ['category' => null])
</section>
@endsection
