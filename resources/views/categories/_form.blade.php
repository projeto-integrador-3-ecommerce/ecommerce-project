@php($isEditing = $category !== null)

<form action="{{ $isEditing ? route('categories.update', $category) : route('categories.store') }}" method="POST" enctype="multipart/form-data" data-supabase-image-upload data-upload-url="{{ route('supabase.images.store') }}" data-supabase-url="{{ config('services.supabase.url') }}" data-supabase-bucket="{{ config('services.supabase.bucket') }}" class="rounded-3xl border border-aqua bg-white p-5 shadow-sm sm:p-8">
    @csrf
    @if ($isEditing)
        @method('PUT')
    @endif

    @if ($errors->any())
        <div role="alert" class="mb-6 rounded-2xl bg-red/10 px-4 py-3 text-sm text-red">
            <strong class="font-semibold">Confira os campos abaixo.</strong>
            <ul class="mt-2 list-inside list-disc">@foreach ($errors->all() as $error)<li>{{ $error }}</li>@endforeach</ul>
        </div>
    @endif

    <div class="grid gap-5 lg:grid-cols-[1fr_18rem]">
        <div class="grid gap-5">
            <x-ui.input label="Nome da categoria" type="text" name="name" maxlength="100" value="{{ old('name', $category?->name) }}" autocomplete="off" required />
            <x-ui.textarea name="description" label="Descrição (opcional)" rows="4" maxlength="2000">{{ old('description', $category?->description) }}</x-ui.textarea>
        </div>

        <div class="rounded-3xl border border-aqua bg-bg/70 p-5">
            <h2 class="text-sm font-semibold text-navy">Imagem da categoria</h2>
            <input type="hidden" name="image_path" value="{{ old('image_path') }}">
            @if ($isEditing && $category->image)
                <p class="mt-4 text-xs font-semibold text-muted">Imagem atual</p>
                <img data-image-preview src="{{ $category->image_url }}" alt="Imagem atual da categoria {{ $category->name }}" class="mt-2 aspect-square w-full rounded-3xl bg-mist/40 object-cover">
            @else
                <img data-image-preview alt="Prévia da imagem da categoria" hidden class="mt-2 aspect-square w-full rounded-3xl bg-mist/40 object-cover">
            @endif
            <label class="mt-5 block">
                <span class="mb-2 block text-xs font-semibold text-navy">{{ $isEditing ? 'Substituir imagem (opcional)' : 'Imagem (opcional)' }}</span>
                <input type="file" name="image" accept="image/jpeg,image/png,image/webp" data-supabase-folder="categories" class="w-full text-xs text-navy file:mr-3 file:inline-flex file:min-h-11 file:rounded-full file:border-0 file:bg-navy file:px-4 file:text-xs file:font-semibold file:text-white file:transition file:hover:bg-navy/90">
                <span class="mt-2 block text-xs text-muted">Formatos JPG, PNG ou WEBP; até 5 MB.</span>
            </label>
            <span data-supabase-upload-status role="status" class="mt-3 block text-xs font-semibold text-teal"></span>
        </div>
    </div>

    <div class="mt-8 flex flex-wrap items-center gap-3 border-t border-aqua pt-6">
        <x-ui.button type="submit">{{ $isEditing ? 'Salvar alterações' : 'Criar categoria' }}</x-ui.button>
        <x-ui.link href="{{ route('categories.index') }}" variant="ghost" size="sm">Cancelar</x-ui.link>
    </div>
</form>

@pushOnce('scripts')
    <script src="https://cdn.jsdelivr.net/npm/@supabase/supabase-js@2"></script>
    <script src="{{ asset('js/supabase.js') }}"></script>
@endPushOnce
