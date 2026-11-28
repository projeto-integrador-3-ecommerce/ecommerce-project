@php($isEditing = $category !== null)

<form action="{{ $isEditing ? route('categories.update', $category) : route('categories.store') }}" method="POST" enctype="multipart/form-data" data-supabase-image-upload data-upload-url="{{ route('supabase.images.store') }}" data-supabase-url="{{ config('services.supabase.url') }}" data-supabase-bucket="{{ config('services.supabase.bucket') }}">
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
            <span>Nome da categoria</span>
            <input type="text" name="name" maxlength="100" value="{{ old('name', $category?->name) }}" autocomplete="off" required>
        </label>
        <label>
            <span>Descrição (opcional)</span>
            <textarea name="description" rows="4" maxlength="2000">{{ old('description', $category?->description) }}</textarea>
        </label>
        <input type="hidden" name="image_path" value="{{ old('image_path') }}">
        @if ($isEditing && $category->image)
            <div>
                <span>Imagem atual</span>
                <img data-image-preview src="{{ $category->image_url }}" alt="Imagem atual da categoria {{ $category->name }}">
            </div>
        @endif
        <label>
            <span>{{ $isEditing ? 'Substituir imagem (opcional)' : 'Imagem (opcional)' }}</span>
            <input type="file" name="image" accept="image/jpeg,image/png,image/webp" data-supabase-folder="categories">
            <span>Formatos JPG, PNG ou WEBP; até 5 MB.</span>
            <span data-supabase-upload-status role="status"></span>
        </label>
    </div>

    <div>
        <button type="submit">{{ $isEditing ? 'Salvar alterações' : 'Criar categoria' }}</button>
        <a href="{{ route('categories.index') }}">Cancelar</a>
    </div>
</form>

@pushOnce('scripts')
    <script src="https://cdn.jsdelivr.net/npm/@supabase/supabase-js@2"></script>
    <script src="{{ asset('js/supabase.js') }}"></script>
@endPushOnce
