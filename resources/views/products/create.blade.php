@extends('layouts.storefront', ['title' => 'Cadastrar produto'])

@section('content')
<section>
    <div>
        <span>Administração da loja</span>
        <h1>Cadastrar produto</h1>
        <p>Preencha os dados para adicionar um novo produto ao catálogo.</p>
        <div>
            <a href="{{ route('products.index') }}">Voltar ao catálogo</a>
            <a href="{{ route('categories.create') }}">Cadastrar categoria</a>
        </div>
    </div>
</section>
<section>
    @include('products._form', ['product' => null])
</section>
@endsection
