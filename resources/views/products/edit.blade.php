@extends('layouts.storefront', ['title' => 'Editar produto'])

@section('content')
<section class="page-head">
    <div class="container">
        <span class="eyebrow">Administração da loja</span>
        <h1>Editar produto</h1>
        <p>Atualize os dados de {{ $product->name }} no catálogo.</p>
        <div class="admin-page-actions">
            <a class="btn btn--outline" href="{{ route('products.show', $product) }}">Ver produto</a>
            <a class="btn btn--outline" href="{{ route('products.index') }}">Voltar ao catálogo</a>
        </div>
    </div>
</section>
<section class="container pb-20">
    @include('products._form', ['product' => $product])
</section>
@endsection
