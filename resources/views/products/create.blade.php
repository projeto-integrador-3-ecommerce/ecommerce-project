@extends('layouts.app')

@section('content')

    <form action="{{ route('products.store') }}" method="POST" enctype="multipart/form-data">
        @csrf

        <div>
            <label for="name">Título do Produto</label>
            <input id="name" type="text" name="name" maxlength="120" value="{{ old('name') }}" required>
        </div>
        <div>
            <label for="description">Descrição do Produto</label>
            <textarea id="description" name="description" maxlength="5000" required>{{ old('description') }}</textarea>
        </div>
        <div>
            <label for="category_id">Categoria do Produto</label>
            <select name="category_id" id="category_id" required>
                @foreach($categories as $category)
                    <option value="{{ $category->id }}" @selected(old('category_id') == $category->id)>{{ $category->name }}</option>
                @endforeach
            </select>
        </div>
        <div>
            <label for="price">Preço do Produto</label>
            <input id="price" type="number" name="price" min="0" max="99999999.99" step="0.01" value="{{ old('price') }}" required>
        </div>
        <div>
            <label for="stock">Estoque do Produto</label>
            <input id="stock" type="number" name="stock" min="0" max="999999" value="{{ old('stock') }}" required>
        </div>
        <div>
            <label for="material">Material do Produto</label>
            <input id="material" type="text" name="material" maxlength="80" value="{{ old('material') }}" required>
        </div>
        <div>
            <label for="color">Cor do Produto</label>
            <input id="color" type="text" name="color" maxlength="40" value="{{ old('color') }}" required>
        </div>
        <div>
            <label for="size">Tamanho do Produto</label>
            <input id="size" type="text" name="size" maxlength="40" value="{{ old('size') }}" required>
        </div>
        <div>
            <label for="image">Imagem do Produto</label>
            <input id="image" type="file" name="image" accept="image/jpeg,image/png,image/webp">
        </div>

        <button type="submit">Criar Produto</button>
        <a href="{{ route('categories.create') }}">Criar Categoria</a>
    </form>

    @if($errors->any())
        <div role="alert">
            <p>{{ $errors->first() }}</p>
        </div>
    @endif

@endsection