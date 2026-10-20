@extends('layouts.app')

@section('content')

    <h1>Criar Categoria</h1>

    <form action="/categories" method="POST">
        @csrf

        <div>
            <label>Name</label>
            <input type="text" name="name" maxlength="100" value="{{ old('name') }}" placeholder="Enter your category name" required>
        </div>

        <div>
            <label for="description">Descrição</label>
            <textarea id="description" name="description" maxlength="2000">{{ old('description') }}</textarea>
        </div>

        <button type="submit">Criar Categoria</button>
    </form>

    @if($errors->any())
        <div role="alert">
            {{ $errors->first() }}
        </div>
    @endif

@endsection