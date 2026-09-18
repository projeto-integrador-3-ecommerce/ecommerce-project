@extends('layouts.app')

@section('content')

    <h1>Criar Categoria</h1>

    <form action="/categories/{{ $category->id }}" method="POST">
        @csrf

        <div>
            <label>Name</label>
            <input type="text" name="name" placeholder="Enter your category name" required>
        </div>

        <button type="submit">Atualizar Categoria</button>
    </form>

    @if($errors->any())
        <div>
            {{ $errors->first() }}
        </div>
    @endif

@endsection