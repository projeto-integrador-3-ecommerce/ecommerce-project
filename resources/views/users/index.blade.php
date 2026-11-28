@extends('layouts.storefront', ['title' => 'Usuários cadastrados'])

@section('content')
<section class="rounded-3xl border border-aqua bg-white/80 p-6 shadow-sm sm:p-10">
    <span class="text-xs font-semibold tracking-[0.14em] text-teal uppercase">Administração</span>
    <h1 class="mt-2 text-3xl font-semibold text-navy sm:text-4xl">Usuários cadastrados</h1>
    <p class="mt-3 text-sm text-muted">{{ $users->total() }} contas registradas na loja.</p>
</section>

<section class="mt-8">
    @if ($errors->has('user'))
        <p role="alert" class="mb-5 rounded-2xl bg-red/10 px-4 py-3 text-sm font-semibold text-red">{{ $errors->first('user') }}</p>
    @endif
    <div class="hidden overflow-x-auto rounded-3xl border border-aqua bg-white shadow-sm md:block">
        <table class="w-full min-w-[640px] text-left text-sm">
            <thead class="bg-mist/60 text-xs text-navy">
                <tr><th scope="col" class="px-5 py-4 font-semibold">Nome</th><th scope="col" class="px-5 py-4 font-semibold">E-mail</th><th scope="col" class="px-5 py-4 font-semibold">Cadastro</th><th scope="col" class="px-5 py-4 font-semibold">Tipo</th><th scope="col" class="px-5 py-4 font-semibold">Ações</th></tr>
            </thead>
            <tbody class="divide-y divide-aqua">
                @forelse ($users as $user)
                    <tr class="transition hover:bg-bg/70">
                        <td class="px-5 py-4 font-semibold text-navy">{{ $user->name }}</td>
                        <td class="px-5 py-4 text-muted">{{ $user->email }}</td>
                        <td class="px-5 py-4 text-muted">{{ $user->created_at->format('d/m/Y') }}</td>
                        <td class="px-5 py-4"><x-ui.badge :variant="$user->is_admin ? 'blue' : 'muted'">{{ $user->is_admin ? 'Administrador' : 'Cliente' }}</x-ui.badge></td>
                        <td class="px-5 py-4">
                            @unless ($user->is_admin)
                                <form action="{{ route('users.destroy', $user) }}" method="POST" onsubmit="return confirm('Remover este usuário?')">
                                    @csrf
                                    @method('DELETE')
                                    <x-ui.button type="submit" variant="danger" size="sm">Remover usuário</x-ui.button>
                                </form>
                            @endunless
                        </td>
                    </tr>
                @empty
                    <tr><td colspan="5" class="px-5 py-10 text-center text-sm text-muted">Nenhum usuário cadastrado</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
    <div class="grid gap-4 md:hidden">
        @forelse ($users as $user)
            <article class="rounded-3xl border border-aqua bg-white p-5 shadow-sm">
                <div class="flex items-start justify-between gap-3">
                    <div><h2 class="text-sm font-semibold text-navy">{{ $user->name }}</h2><p class="mt-1 text-xs text-muted">{{ $user->email }}</p><p class="mt-1 text-xs text-muted">Cadastro: {{ $user->created_at->format('d/m/Y') }}</p></div>
                    <x-ui.badge :variant="$user->is_admin ? 'blue' : 'muted'">{{ $user->is_admin ? 'Administrador' : 'Cliente' }}</x-ui.badge>
                </div>
                @unless ($user->is_admin)
                    <form action="{{ route('users.destroy', $user) }}" method="POST" onsubmit="return confirm('Remover este usuário?')" class="mt-4 border-t border-aqua pt-4">
                        @csrf
                        @method('DELETE')
                        <x-ui.button type="submit" variant="danger" size="sm">Remover usuário</x-ui.button>
                    </form>
                @endunless
            </article>
        @empty
            <div class="rounded-3xl border border-aqua bg-white p-8 text-center shadow-sm"><h2 class="text-xl font-semibold text-navy">Nenhum usuário cadastrado</h2></div>
        @endforelse
    </div>
    <div class="mt-6 flex flex-wrap gap-2 [&_a]:inline-flex [&_a]:min-h-11 [&_a]:items-center [&_a]:rounded-full [&_a]:border [&_a]:border-navy/20 [&_a]:px-3 [&_a]:text-xs [&_a]:font-semibold [&_a]:text-navy [&_a]:hover:bg-aqua/40 [&_span]:inline-flex [&_span]:min-h-11 [&_span]:items-center [&_span]:px-2 [&_span]:text-xs [&_span]:text-muted">{{ $users->links() }}</div>
</section>
@endsection
