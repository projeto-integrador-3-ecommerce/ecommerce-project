@php($isEditing = $product !== null)

<form action="{{ $isEditing ? route('products.update', $product) : route('products.store') }}" method="POST" enctype="multipart/form-data" data-supabase-image-upload data-upload-url="{{ route('supabase.images.store') }}" data-supabase-url="{{ config('services.supabase.url') }}" data-supabase-bucket="{{ config('services.supabase.bucket') }}">
    @csrf
    @if ($isEditing)
        @method('PUT')
    @endif

    @if ($errors->any())
        <div role="alert">
            <strong>Confira os campos abaixo.</strong>
            <ul>@foreach ($errors->all() as $error)<li>{{ $error }}</li>@endforeach</ul>
        </div>
    @endif

    <div>
        <label>
            <span>Nome do produto</span>
            <input type="text" name="name" maxlength="120" value="{{ old('name', $product?->name) }}" autocomplete="off" required>
        </label>
        <label>
            <span>Categoria</span>
            <select name="category_id" required>
                <option value="">Selecione uma categoria</option>
                @foreach ($categories as $category)
                    <option value="{{ $category->id }}" @selected(old('category_id', $product?->category_id) == $category->id)>{{ $category->name }}</option>
                @endforeach
            </select>
            @if ($categories->isEmpty())
                <span>Cadastre uma categoria antes de cadastrar um produto.</span>
            @endif
        </label>
        <label>
            <span>Descrição</span>
            <textarea name="description" rows="5" maxlength="5000" required>{{ old('description', $product?->description) }}</textarea>
        </label>
        <label>
            <span>Preço (R$)</span>
            <input type="number" name="price" min="0" max="99999999.99" step="0.01" value="{{ old('price', $product?->price) }}" required>
        </label>
        <label>
            <span>Quantidade em estoque</span>
            <input type="number" name="stock" min="0" max="999999" value="{{ old('stock', $product?->stock) }}" required>
        </label>
        <label>
            <span>Material</span>
            <input type="text" name="material" maxlength="80" value="{{ old('material', $product?->material) }}" required>
        </label>
        <label>
            <span>Cor</span>
            <input type="text" name="color" maxlength="40" value="{{ old('color', $product?->color) }}" required>
        </label>
        <label>
            <span>Tamanho</span>
            <input type="text" name="size" maxlength="40" value="{{ old('size', $product?->size) }}" required>
        </label>
        <input type="hidden" name="image_path" value="{{ old('image_path') }}">
        @if ($isEditing && $product->image)
            <div>
                <span>Imagem atual</span>
                <img data-image-preview src="{{ $product->image_url }}" alt="Imagem atual de {{ $product->name }}">
            </div>
        @endif
        <label>
            <span>{{ $isEditing ? 'Substituir imagem (opcional)' : 'Imagem (opcional)' }}</span>
            <input type="file" name="image" accept="image/jpeg,image/png,image/webp" data-supabase-folder="products">
            <span>Formatos JPG, PNG ou WEBP; até 5 MB.</span>
            <span data-supabase-upload-status role="status"></span>
        </label>
    </div>

    <div>
        <button type="submit" @disabled($categories->isEmpty())>{{ $isEditing ? 'Salvar alterações' : 'Cadastrar produto' }}</button>
        <a href="{{ $isEditing ? route('products.show', $product) : route('products.index') }}">Cancelar</a>
        @if ($categories->isEmpty())
            <a href="{{ route('categories.create') }}">Cadastrar categoria</a>
        @endif
    </div>
</form>

@pushOnce('scripts')
    <script src="https://cdn.jsdelivr.net/npm/@supabase/supabase-js@2"></script>
    <script src="{{ asset('js/supabase.js') }}"></script>
@endPushOnce
