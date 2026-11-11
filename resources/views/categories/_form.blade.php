@php($isEditing = $category !== null)

<form action="{{ $isEditing ? route('categories.update', $category) : route('categories.store') }}" method="POST" enctype="multipart/form-data" class="admin-form" data-supabase-image-upload data-upload-url="{{ route('supabase.images.store') }}" data-supabase-url="{{ config('services.supabase.url') }}" data-supabase-bucket="{{ config('services.supabase.bucket') }}">
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
            <span>Nome da categoria</span>
            <input class="input" type="text" name="name" maxlength="100" value="{{ old('name', $category?->name) }}" autocomplete="off" required>
        </label>
        <label class="field admin-form-grid__full">
            <span>Descrição (opcional)</span>
            <textarea class="input" name="description" rows="4" maxlength="2000">{{ old('description', $category?->description) }}</textarea>
        </label>
        <input type="hidden" name="image_path" value="{{ old('image_path') }}">
        @if ($isEditing && $category->image)
            <div class="field">
                <span>Imagem atual</span>
                <img class="admin-form__preview" data-image-preview src="{{ $category->image_url }}" alt="Imagem atual da categoria {{ $category->name }}">
            </div>
        @endif
        <label class="field admin-form-grid__full">
            <span>{{ $isEditing ? 'Substituir imagem (opcional)' : 'Imagem (opcional)' }}</span>
            <input class="input" type="file" name="image" accept="image/jpeg,image/png,image/webp" data-supabase-folder="categories">
            <span class="form-note">Formatos JPG, PNG ou WEBP; até 5 MB.</span>
            <span class="form-note" data-supabase-upload-status role="status"></span>
        </label>
    </div>

    <div class="admin-form__actions">
        <button class="btn btn--primary" type="submit">{{ $isEditing ? 'Salvar alterações' : 'Criar categoria' }}</button>
        <a class="btn btn--outline" href="{{ route('categories.index') }}">Cancelar</a>
    </div>
</form>

@pushOnce('scripts')
    <script src="https://cdn.jsdelivr.net/npm/@supabase/supabase-js@2"></script>
    <script src="{{ asset('js/supabase.js') }}"></script>
@endPushOnce
