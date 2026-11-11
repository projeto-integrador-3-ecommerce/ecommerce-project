@php($isEditing = $product !== null)

<form action="{{ $isEditing ? route('products.update', $product) : route('products.store') }}" method="POST" enctype="multipart/form-data" class="admin-form" data-supabase-image-upload data-upload-url="{{ route('supabase.images.store') }}" data-supabase-url="{{ config('services.supabase.url') }}" data-supabase-bucket="{{ config('services.supabase.bucket') }}">
    @csrf
    @if ($isEditing)
        @method('PUT')
    @endif

    @if ($errors->any())
        <div class="admin-form__error" role="alert">
            <strong>Confira os campos abaixo.</strong>
            <ul>@foreach ($errors->all() as $error)<li>{{ $error }}</li>@endforeach</ul>
        </div>
    @endif

    <div class="admin-form-grid">
        <label class="field">
            <span>Nome do produto</span>
            <input class="input" type="text" name="name" maxlength="120" value="{{ old('name', $product?->name) }}" autocomplete="off" required>
        </label>
        <label class="field">
            <span>Categoria</span>
            <select class="input" name="category_id" required>
                <option value="">Selecione uma categoria</option>
                @foreach ($categories as $category)
                    <option value="{{ $category->id }}" @selected(old('category_id', $product?->category_id) == $category->id)>{{ $category->name }}</option>
                @endforeach
            </select>
            @if ($categories->isEmpty())
                <span class="form-note">Cadastre uma categoria antes de cadastrar um produto.</span>
            @endif
        </label>
        <label class="field admin-form-grid__full">
            <span>Descrição</span>
            <textarea class="input" name="description" rows="5" maxlength="5000" required>{{ old('description', $product?->description) }}</textarea>
        </label>
        <label class="field">
            <span>Preço (R$)</span>
            <input class="input" type="number" name="price" min="0" max="99999999.99" step="0.01" value="{{ old('price', $product?->price) }}" required>
        </label>
        <label class="field">
            <span>Quantidade em estoque</span>
            <input class="input" type="number" name="stock" min="0" max="999999" value="{{ old('stock', $product?->stock) }}" required>
        </label>
        <label class="field">
            <span>Material</span>
            <input class="input" type="text" name="material" maxlength="80" value="{{ old('material', $product?->material) }}" required>
        </label>
        <label class="field">
            <span>Cor</span>
            <input class="input" type="text" name="color" maxlength="40" value="{{ old('color', $product?->color) }}" required>
        </label>
        <label class="field">
            <span>Tamanho</span>
            <input class="input" type="text" name="size" maxlength="40" value="{{ old('size', $product?->size) }}" required>
        </label>
        <input type="hidden" name="image_path" value="{{ old('image_path') }}">
        @if ($isEditing && $product->image)
            <div class="field">
                <span>Imagem atual</span>
                <img class="admin-form__preview" data-image-preview src="{{ $product->image_url }}" alt="Imagem atual de {{ $product->name }}">
            </div>
        @endif
        <label class="field admin-form-grid__full">
            <span>{{ $isEditing ? 'Substituir imagem (opcional)' : 'Imagem (opcional)' }}</span>
            <input class="input" type="file" name="image" accept="image/jpeg,image/png,image/webp" data-supabase-folder="products">
            <span class="form-note">Formatos JPG, PNG ou WEBP; até 5 MB.</span>
            <span class="form-note" data-supabase-upload-status role="status"></span>
        </label>
    </div>

    <div class="admin-form__actions">
        <button class="btn btn--primary" type="submit" @disabled($categories->isEmpty())>{{ $isEditing ? 'Salvar alterações' : 'Cadastrar produto' }}</button>
        <a class="btn btn--outline" href="{{ $isEditing ? route('products.show', $product) : route('products.index') }}">Cancelar</a>
        @if ($categories->isEmpty())
            <a class="btn btn--ghost" href="{{ route('categories.create') }}">Cadastrar categoria</a>
        @endif
    </div>
</form>

@pushOnce('scripts')
    <script src="https://cdn.jsdelivr.net/npm/@supabase/supabase-js@2"></script>
    <script src="{{ asset('js/supabase.js') }}"></script>
@endPushOnce
