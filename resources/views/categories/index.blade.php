@extends('layouts.app')

@section('content')

    <h1>Categorias</h1>

    <select name="categories">
        @foreach($categories as $category)
            <option value="{{ $category->id }}">{{ $category->name }}</option>
        @endforeach
    </select>

@endsection