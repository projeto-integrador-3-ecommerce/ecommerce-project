@extends('layouts.app')

@section('content')

    <h1>Categorias</h1>

    @foreach($categories as $category)
        <section>
            <h2>{{ $category->name }}</h2>
            @if($category->description)
                <p>{{ $category->description }}</p>
            @endif
        </section>
    @endforeach

    @can('admin')
        <a href="{{ route('categories.create') }}">Criar Categoria</a>
    @endcan

@endsection