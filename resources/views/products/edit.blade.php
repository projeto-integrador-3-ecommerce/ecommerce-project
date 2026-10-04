@extends('layouts.app')

@section('content')
    <h1>Editar Produto</h1>

    <form action="{{ route('products.update', $product) }}" method="POST" enctype="multipart/form-data">
        @csrf
        @method('PUT')

        <div>
            <label for="name">Título do Produto</label>
            <input id="name" type="text" name="name" maxlength="120" value="{{ old('name', $product->name) }}" required>
        </div>
        <div>
            <label for="description">Descrição do Produto</label>
            <textarea id="description" name="description" maxlength="5000" required>{{ old('description', $product->description) }}</textarea>
        </div>
        <div>
            <label for="category_id">Categoria do Produto</label>
            <select id="category_id" name="category_id" required>
                @foreach($categories as $category)
                    <option value="{{ $category->id }}" @selected(old('category_id', $product->category_id) == $category->id)>{{ $category->name }}</option>
                @endforeach
            </select>
        </div>
        <div>
            <label for="price">Preço do Produto</label>
            <input id="price" type="number" name="price" min="0" max="99999999.99" step="0.01" value="{{ old('price', $product->price) }}" required>
        </div>
        <div>
            <label for="stock">Estoque do Produto</label>
            <input id="stock" type="number" name="stock" min="0" max="999999" value="{{ old('stock', $product->stock) }}" required>
        </div>
        <div>
            <label for="material">Material do Produto</label>
            <input id="material" type="text" name="material" maxlength="80" value="{{ old('material', $product->material) }}" required>
        </div>
        <div>
            <label for="color">Cor do Produto</label>
            <input id="color" type="text" name="color" maxlength="40" value="{{ old('color', $product->color) }}" required>
        </div>
        <div>
            <label for="size">Tamanho do Produto</label>
            <input id="size" type="text" name="size" maxlength="40" value="{{ old('size', $product->size) }}" required>
        </div>
        @if($product->image)
            <img src="{{ Storage::disk('public')->url($product->image) }}" alt="Imagem atual de {{ $product->name }}" width="180">
        @endif
        <div>
            <label for="image">Substituir imagem</label>
            <input id="image" type="file" name="image" accept="image/jpeg,image/png,image/webp">
        </div>

        <button type="submit">Salvar produto</button>
    </form>

    @if($errors->any())
        <div role="alert">
            <p>{{ $errors->first() }}</p>
        </div>
    @endif
@endsection
