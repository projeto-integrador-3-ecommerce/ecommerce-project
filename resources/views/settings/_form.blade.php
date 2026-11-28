@php($isEditing = $setting !== null)

<form action="{{ $isEditing ? route('setting.update', $setting) : route('setting.store') }}" method="POST">
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
            <span>Nome da loja</span>
            <input type="text" name="name" maxlength="120" value="{{ old('name', $setting?->name) }}" autocomplete="off" required>
        </label>
        <label>
            <span>CNPJ</span>
            <input type="text" name="cnpj" maxlength="18" value="{{ old('cnpj', $setting?->cnpj) }}" placeholder="Ex: 000000000/0001-00" autocomplete="off" required>
        </label>
        <label>
            <span>E-mail de contato</span>
            <input type="email" name="email" maxlength="255" value="{{ old('email', $setting?->email) }}" autocomplete="off" required>
        </label>
        <label>
            <span>Telefone da loja</span>
            <input type="tel" name="telephone" maxlength="20" value="{{ old('telephone', $setting?->telephone) }}" placeholder="Ex: 11955555555" autocomplete="off" required>
        </label>
    </div>

    <div>
        <button type="submit">{{ $isEditing ? 'Salvar alterações' : 'Salvar dados da loja' }}</button>
        <a href="{{ route('admin.dashboard') }}">Voltar ao painel</a>
    </div>
</form>
