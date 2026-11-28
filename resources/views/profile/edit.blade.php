@extends('layouts.storefront', ['title' => 'Minha conta'])

@section('content')
<section><div><span>Sua conta</span><h1>Minha conta</h1><p>Confira seus dados e atualize o que precisar.</p></div></section>
<section>
    <article>
        <h2>Informações da conta</h2>
        <p><strong>Nome</strong><br>{{ $user->name }}</p>
        <p><strong>E-mail</strong><br>{{ $user->email }}</p>
        <p><strong>Tipo de conta</strong><br>{{ $user->is_admin ? 'Administrador' : 'Cliente' }}</p>
        <p><strong>Membro desde</strong><br>{{ $user->created_at->format('d/m/Y') }}</p>
        <div>
            <a href="{{ route('addresses.index') }}">Meus endereços</a>
            <a href="{{ route('orders.index') }}">Meus pedidos</a>
        </div>
    </article>
    <article>
        <h2>Editar dados</h2>
        @if ($errors->any())<p role="alert">{{ $errors->first() }}</p>@endif
        <form action="{{ route('profile.update') }}" method="POST">@csrf @method('PATCH')
            <label><span>Nome</span><input type="text" name="name" value="{{ old('name', $user->name) }}" maxlength="150" autocomplete="name" required></label>
            <label><span>E-mail</span><input type="email" name="email" value="{{ old('email', $user->email) }}" maxlength="255" autocomplete="email" required></label>
            <p>Para manter sua senha, deixe os campos abaixo em branco.</p>
            <label><span>Senha atual</span><input type="password" name="current_password" autocomplete="current-password"></label>
            <label><span>Nova senha</span><input type="password" name="password" minlength="8" maxlength="255" autocomplete="new-password"></label>
            <label><span>Confirme a nova senha</span><input type="password" name="password_confirmation" minlength="8" maxlength="255" autocomplete="new-password"></label>
            <button type="submit">Salvar alterações</button>
        </form>
    </article>
</section>
@endsection
