@extends('layouts.app')

@section('content')

    <h1>Categoria {{ $category->name }}</h1>

    <h2>{{ $category->name }}</h2>

    <button><a href="/categories/{{ $category->id }}/edit">Editar Categoria</a></button>
    <forms action="{{ route('categories.destroy', $category) }}" method="POST">
        <button type="submit">Excluir categoria</a></button>
    </forms>
    <button><a href="/categories">Visualizar Todas as Categorias</a></button>

@endsection