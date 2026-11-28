@extends('layouts.storefront', ['title' => 'Editar categoria'])

@section('content')
<section>
    <div>
        <span>Administração da loja</span>
        <h1>Editar categoria</h1>
        <p>Atualize os dados da categoria {{ $category->name }}.</p>
        <div>
            <a href="{{ route('categories.index') }}">Voltar para categorias</a>
        </div>
    </div>
</section>
<section>
    @include('categories._form', ['category' => $category])
</section>
@endsection
