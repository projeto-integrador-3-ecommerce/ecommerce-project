@extends('layouts.storefront', ['title' => 'Cadastrar produto'])

@section('content')
<section class="page-head">
    <div class="container">
        <span class="eyebrow">Administração da loja</span>
        <h1>Cadastrar produto</h1>
        <p>Preencha os dados para adicionar um novo produto ao catálogo.</p>
        <div class="admin-page-actions">
            <a class="btn btn--outline" href="{{ route('products.index') }}">Voltar ao catálogo</a>
            <a class="btn btn--ghost" href="{{ route('categories.create') }}">Cadastrar categoria</a>
        </div>
    </div>
</section>
<section class="container pb-20">
    @include('products._form', ['product' => null])
</section>
@endsection
