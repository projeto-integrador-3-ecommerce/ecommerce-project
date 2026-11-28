@php($isEditing = $setting !== null)

<form action="{{ $isEditing ? route('setting.update', $setting) : route('setting.store') }}" method="POST" class="rounded-3xl border border-aqua bg-white p-5 shadow-sm sm:p-8">
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

    <div class="grid gap-5 sm:grid-cols-2">
        <x-ui.input label="Nome da loja" type="text" name="name" maxlength="120" value="{{ old('name', $setting?->name) }}" autocomplete="off" required />
        <x-ui.input label="CNPJ" type="text" name="cnpj" maxlength="18" value="{{ old('cnpj', $setting?->cnpj) }}" placeholder="Ex: 000000000/0001-00" autocomplete="off" required />
        <x-ui.input label="E-mail de contato" type="email" name="email" maxlength="255" value="{{ old('email', $setting?->email) }}" autocomplete="off" required />
        <x-ui.input label="Telefone da loja" type="tel" name="telephone" maxlength="20" value="{{ old('telephone', $setting?->telephone) }}" placeholder="Ex: 11955555555" autocomplete="off" required />
    </div>

    <div class="mt-8 flex flex-wrap items-center gap-3 border-t border-aqua pt-6">
        <x-ui.button type="submit">{{ $isEditing ? 'Salvar alterações' : 'Salvar dados da loja' }}</x-ui.button>
        <x-ui.link href="{{ route('admin.dashboard') }}" variant="ghost" size="sm">Voltar ao painel</x-ui.link>
    </div>
</form>
