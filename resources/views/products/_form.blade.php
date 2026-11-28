@php($isEditing = $product !== null)

<form action="{{ $isEditing ? route('products.update', $product) : route('products.store') }}" method="POST" enctype="multipart/form-data" data-supabase-image-upload data-upload-url="{{ route('supabase.images.store') }}" data-supabase-url="{{ config('services.supabase.url') }}" data-supabase-bucket="{{ config('services.supabase.bucket') }}" class="rounded-3xl border border-aqua bg-white p-5 shadow-sm sm:p-8">
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
        <div class="grid gap-5 sm:grid-cols-2">
            <x-ui.input label="Nome do produto" type="text" name="name" maxlength="120" value="{{ old('name', $product?->name) }}" autocomplete="off" required class="sm:col-span-2" />
            <x-ui.select name="category_id" label="Categoria" required class="sm:col-span-2" hint="{{ $categories->isEmpty() ? 'Cadastre uma categoria antes de cadastrar um produto.' : null }}">
                <option value="">Selecione uma categoria</option>
                @foreach ($categories as $category)
                    <option value="{{ $category->id }}" @selected(old('category_id', $product?->category_id) == $category->id)>{{ $category->name }}</option>
                @endforeach
            </x-ui.select>
            <x-ui.textarea name="description" label="Descrição" rows="5" maxlength="5000" required class="sm:col-span-2">{{ old('description', $product?->description) }}</x-ui.textarea>
            <x-ui.input label="Preço (R$)" type="number" name="price" min="0" max="99999999.99" step="0.01" value="{{ old('price', $product?->price) }}" required />
            <x-ui.input label="Quantidade em estoque" type="number" name="stock" min="0" max="999999" value="{{ old('stock', $product?->stock) }}" required />
            <x-ui.input label="Material" type="text" name="material" maxlength="80" value="{{ old('material', $product?->material) }}" required />
            <x-ui.input label="Cor" type="text" name="color" maxlength="40" value="{{ old('color', $product?->color) }}" required />
            <x-ui.input label="Tamanho" type="text" name="size" maxlength="40" value="{{ old('size', $product?->size) }}" required />
        </div>

        <div class="rounded-3xl border border-aqua bg-bg/70 p-5">
            <h2 class="text-sm font-semibold text-navy">Imagem do produto</h2>
            <input type="hidden" name="image_path" value="{{ old('image_path') }}">
            @if ($isEditing && $product->image)
                <p class="mt-4 text-xs font-semibold text-muted">Imagem atual</p>
                <img data-image-preview src="{{ $product->image_url }}" alt="Imagem atual de {{ $product->name }}" class="mt-2 aspect-square w-full rounded-3xl bg-mist/40 object-cover">
            @else
                <img data-image-preview alt="Prévia da imagem do produto" hidden class="mt-2 aspect-square w-full rounded-3xl bg-mist/40 object-cover">
            @endif
            <label class="mt-5 block">
                <span class="mb-2 block text-xs font-semibold text-navy">{{ $isEditing ? 'Substituir imagem (opcional)' : 'Imagem (opcional)' }}</span>
                <input type="file" name="image" accept="image/jpeg,image/png,image/webp" data-supabase-folder="products" class="w-full text-xs text-navy file:mr-3 file:inline-flex file:min-h-11 file:rounded-full file:border-0 file:bg-navy file:px-4 file:text-xs file:font-semibold file:text-white file:transition file:hover:bg-navy/90">
                <span class="mt-2 block text-xs text-muted">Formatos JPG, PNG ou WEBP; até 5 MB.</span>
            </label>
            <span data-supabase-upload-status role="status" class="mt-3 block text-xs font-semibold text-teal"></span>
        </div>
    </div>

    <div class="mt-8 flex flex-wrap items-center gap-3 border-t border-aqua pt-6">
        <x-ui.button type="submit" :disabled="$categories->isEmpty()">{{ $isEditing ? 'Salvar alterações' : 'Cadastrar produto' }}</x-ui.button>
        <x-ui.link href="{{ $isEditing ? route('products.show', $product) : route('products.index') }}" variant="ghost" size="sm">Cancelar</x-ui.link>
        @if ($categories->isEmpty())
            <x-ui.link href="{{ route('categories.create') }}" variant="outline" size="sm">Cadastrar categoria</x-ui.link>
        @endif
    </div>
</form>

@pushOnce('scripts')
    <script src="https://cdn.jsdelivr.net/npm/@supabase/supabase-js@2"></script>
    <script src="{{ asset('js/supabase.js') }}"></script>
@endPushOnce
