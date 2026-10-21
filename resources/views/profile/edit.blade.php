@extends('layouts.storefront', ['title' => 'Minha conta'])

@section('content')
<section class="page-head"><div class="container"><span class="eyebrow">Sua conta</span><h1>Minha conta</h1><p>Confira seus dados e atualize o que precisar.</p></div></section>
<section class="container cart-layout pb-20">
    <article class="info-card">
        <h2>Informações da conta</h2>
        <p><strong>Nome</strong><br>{{ $user->name }}</p>
        <p class="mt-4"><strong>E-mail</strong><br>{{ $user->email }}</p>
        <p class="mt-4"><strong>Tipo de conta</strong><br>{{ $user->is_admin ? 'Administrador' : 'Cliente' }}</p>
        <p class="mt-4"><strong>Membro desde</strong><br>{{ $user->created_at->format('d/m/Y') }}</p>
        <div class="btn-row mt-6">
            <a class="btn btn--outline" href="{{ route('addresses.index') }}">Meus endereços</a>
            <a class="btn btn--ghost" href="{{ route('orders.index') }}">Meus pedidos</a>
        </div>
    </article>
    <article class="summary">
        <h2>Editar dados</h2>
        @if ($errors->any())<p class="form-error" role="alert">{{ $errors->first() }}</p>@endif
        <form action="{{ route('profile.update') }}" method="POST">@csrf @method('PATCH')
            <label class="field"><span>Nome</span><input class="input" type="text" name="name" value="{{ old('name', $user->name) }}" maxlength="150" autocomplete="name" required></label>
            <label class="field"><span>E-mail</span><input class="input" type="email" name="email" value="{{ old('email', $user->email) }}" maxlength="255" autocomplete="email" required></label>
            <p class="form-note">Para manter sua senha, deixe os campos abaixo em branco.</p>
            <label class="field"><span>Senha atual</span><input class="input" type="password" name="current_password" autocomplete="current-password"></label>
            <label class="field"><span>Nova senha</span><input class="input" type="password" name="password" minlength="8" maxlength="255" autocomplete="new-password"></label>
            <label class="field"><span>Confirme a nova senha</span><input class="input" type="password" name="password_confirmation" minlength="8" maxlength="255" autocomplete="new-password"></label>
            <button class="btn btn--primary btn--block" type="submit">Salvar alterações</button>
        </form>
    </article>
</section>
@endsection
