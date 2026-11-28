@extends('layouts.storefront', ['title' => 'Editar dados da loja'])

@section('content')
<section class="rounded-3xl border border-aqua bg-white/80 p-6 shadow-sm sm:p-10">
    <span class="text-xs font-semibold tracking-[0.14em] text-teal uppercase">Administração da loja</span>
    <h1 class="mt-2 text-3xl font-semibold text-navy sm:text-4xl">Editar dados da loja</h1>
    <p class="mt-3 text-sm text-muted">Atualize as informações exibidas sobre a loja.</p>
    <x-ui.link href="{{ route('admin.dashboard') }}" variant="outline" size="sm" class="mt-6">Voltar ao painel</x-ui.link>
</section>
<section class="mt-8">
    @include('settings._form', ['setting' => $setting])
</section>
@endsection
