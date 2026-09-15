@extends('layouts.app')

@section('content')

    <h1>Categoria {{ $category->name }}</h1>

    <h2>{{ $category->name }}</h2>

    <button><a href="/categories/{{ $category->id }}/edit">Editar Categoria</a></button>
    <button><a href="">Excluir categoria</a></button>

@endsection