@extends('layouts.storefront', ['title' => 'Criar categoria'])

@section('content')
<section>
    <div>
        <span>Administração da loja</span>
        <h1>Criar categoria</h1>
        <p>Preencha os dados para organizar melhor o catálogo da loja.</p>
        <div>
            <a href="{{ route('categories.index') }}">Voltar para categorias</a>
            <a href="{{ route('products.create') }}">Cadastrar produto</a>
        </div>
    </div>
</section>
<section>
    @include('categories._form', ['category' => null])
</section>
@endsection
