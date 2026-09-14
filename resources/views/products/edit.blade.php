@extends('layouts.app')

@section('content')

    <h1>Editar {{ $product->nome }}</h1>

    <form action="{{ route('products.update', $product)}}" method="POST">
        @csrf

        <div>
            <label>Título do Produto:</label>
            <input type="text" name="name" value="{{ $product->name }}">
        </div>
        <div>
            <label>Descrição do Produto:</label>
            <input type="text" name="description" value="{{ $product->description }}">
        </div>
        <div>
            <label>Categoria do Produto:</label>
            <input type="text" name="category_id" value="{{ $product->category_id }}">
        </div>
        <div>
            <label>Preço do Produto:</label>
            <input type="text" name="price" value="{{ $product->price }}">
        </div>
        <div>
            <label>Estoque do Produto:</label>
            <input type="text" name="stock" value="{{ $product->stock }}">
        </div>
        <div>
            <label>Material do Produto:</label>
            <input type="text" name="material" value="{{ $product->material }}">
        </div>
        <div>
            <label>Cor do Produto:</label>
            <input type="text" name="color" value="{{ $product->color }}">
        </div>
        <div>
            <label>Tamanho do Produto:</label>
            <input type="text" name="size" value="{{ $product->size }}">
        </div>

        <button type="submit">Atualizar Produto</button>
    </form>

    @if($errors->any())
        <div>
            {{ $errors->first() }}
        </div>
    @endif

@endsection