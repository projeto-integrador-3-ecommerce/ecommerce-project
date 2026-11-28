@extends('layouts.storefront', ['title' => 'Editar produto'])

@section('content')
<section>
    <div>
        <span>Administração da loja</span>
        <h1>Editar produto</h1>
        <p>Atualize os dados de {{ $product->name }} no catálogo.</p>
        <div>
            <a href="{{ route('products.show', $product) }}">Ver produto</a>
            <a href="{{ route('products.index') }}">Voltar ao catálogo</a>
        </div>
    </div>
</section>
<section>
    @include('products._form', ['product' => $product])
</section>
@endsection
