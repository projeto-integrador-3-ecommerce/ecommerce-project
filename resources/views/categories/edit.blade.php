@extends('layouts.storefront', ['title' => 'Editar categoria'])

@section('content')
<section class="page-head">
    <div class="container">
        <span class="eyebrow">Administração da loja</span>
        <h1>Editar categoria</h1>
        <p>Atualize os dados da categoria {{ $category->name }}.</p>
        <div class="admin-page-actions">
            <a class="btn btn--outline" href="{{ route('categories.index') }}">Voltar para categorias</a>
        </div>
    </div>
</section>
<section class="container pb-20">
    @include('categories._form', ['category' => $category])
</section>
@endsection
