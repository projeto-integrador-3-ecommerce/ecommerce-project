@extends('layouts.app')

@section('content')

    <form action="{{ route('products.store' )}}" method="POST">
        @csrf

         <div>
            <label>Título do Produto:</label>
            <input type="text" name="name" required>
        </div>
        <div>
            <label>Descrição do Produto:</label>
            <input type="text" name="description" required>
        </div>
        <div>
            <label>Categoria do Produto:</label>
            <select name="category_id" id="">
                @foreach($categories as $category)
                    <option value="{{ $category->id }}">{{ $category->name }}</option>
                @endforeach
            </select>
        </div>
        <div>
            <label>Preço do Produto:</label>
            <input type="text" name="price" required>
        </div>
        <div>
            <label>Estoque do Produto:</label>
            <input type="text" name="stock" required>
        </div>
        <div>
            <label>Material do Produto:</label>
            <input type="text" name="material" required>
        </div>
        <div>
            <label>Cor do Produto:</label>
            <input type="text" name="color" required>
        </div>
        <div>
            <label>Tamanho do Produto:</label>
            <input type="text" name="size" required>
        </div>

        <button type="submit">Criar Produto</button>
        <button><a href="/categories/create">Criar Categoria</a></button>
    </form>

    @if($errors->any()){

        <div>
            {{ $errors->first() }}
        </div>
    }

    @endif

@endsection