@extends('layouts.storefront', ['title' => 'Minha conta'])

@section('content')
<section class="rounded-3xl border border-aqua bg-white/80 p-6 shadow-sm sm:p-10">
    <span class="text-xs font-semibold tracking-[0.14em] text-teal uppercase">Sua conta</span>
    <h1 class="mt-2 text-3xl font-semibold text-navy sm:text-4xl">Minha conta</h1>
    <p class="mt-3 text-sm text-muted">Confira seus dados e atualize o que precisar.</p>
</section>

<section class="mt-8 grid items-start gap-6 lg:grid-cols-2">
    <article class="rounded-3xl border border-aqua bg-white p-5 shadow-sm sm:p-8">
        <h2 class="text-base font-semibold text-navy">Informações da conta</h2>
        <dl class="mt-5 divide-y divide-aqua text-sm">
            @foreach ([['Nome', $user->name], ['E-mail', $user->email], ['Tipo de conta', $user->is_admin ? 'Administrador' : 'Cliente'], ['Membro desde', $user->created_at->format('d/m/Y')]] as $detail)
                <div class="py-3"><dt class="text-xs text-muted">{{ $detail[0] }}</dt><dd class="mt-1 font-semibold text-navy">{{ $detail[1] }}</dd></div>
            @endforeach
        </dl>
        <div class="mt-6 flex flex-wrap gap-2 border-t border-aqua pt-5">
            <x-ui.link href="{{ route('addresses.index') }}" variant="outline" size="sm">Meus endereços</x-ui.link>
            <x-ui.link href="{{ route('orders.index') }}" variant="outline" size="sm">Meus pedidos</x-ui.link>
        </div>
    </article>
    <article class="rounded-3xl border border-aqua bg-white p-5 shadow-sm sm:p-8">
        <h2 class="text-base font-semibold text-navy">Editar dados</h2>
        @if ($errors->any())
            <p role="alert" class="mt-5 rounded-2xl bg-red/10 px-4 py-3 text-sm font-semibold text-red">{{ $errors->first() }}</p>
        @endif
        <form action="{{ route('profile.update') }}" method="POST" class="mt-5 space-y-5">@csrf @method('PATCH')
            <x-ui.input label="Nome" type="text" name="name" value="{{ old('name', $user->name) }}" maxlength="150" autocomplete="name" required />
            <x-ui.input label="E-mail" type="email" name="email" value="{{ old('email', $user->email) }}" maxlength="255" autocomplete="email" required />
            <p class="text-xs text-muted">Para manter sua senha, deixe os campos abaixo em branco.</p>
            <x-ui.input label="Senha atual" type="password" name="current_password" autocomplete="current-password" />
            <x-ui.input label="Nova senha" type="password" name="password" minlength="8" maxlength="255" autocomplete="new-password" />
            <x-ui.input label="Confirme a nova senha" type="password" name="password_confirmation" minlength="8" maxlength="255" autocomplete="new-password" />
            <x-ui.button type="submit" size="lg">Salvar alterações</x-ui.button>
        </form>
    </article>
</section>
@endsection
