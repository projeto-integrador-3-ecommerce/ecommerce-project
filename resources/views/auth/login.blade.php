@extends('layouts.storefront', ['title' => 'Entrar'])
@section('content')
<div class="mx-auto max-w-5xl overflow-hidden rounded-3xl border border-aqua bg-white shadow-[0_18px_50px_rgba(1,15,55,0.10)]">
    <div class="px-6 pt-10 pb-8 text-center sm:px-10">
        <x-ui.logo size="lg" class="mx-auto" />
        <h1 class="mt-5 text-2xl font-semibold text-navy sm:text-3xl">Faça Login/Cadastre-se</h1>
        <p class="mt-3 text-sm text-muted">Acesse sua conta para acompanhar cada etapa da sua compra.</p>
        <p class="mt-4 inline-flex items-center gap-2 rounded-full bg-mist/60 px-4 py-2 text-xs font-semibold text-navy">
            <svg class="size-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><rect x="5" y="10" width="14" height="10" rx="2"/><path d="M8 10V7a4 4 0 0 1 8 0v3"/></svg>
            Todos os dados estão protegidos
        </p>
    </div>

    <div class="grid gap-4 bg-navy px-6 py-6 text-white sm:grid-cols-3 sm:px-10">
        @foreach ([
            ['Preço justo', 'Ajuste no prazo de 30 dias', 'M20.6 13.4 12 22 3 13V4h9ZM7.5 6.25a1.25 1.25 0 1 0 0 2.5 1.25 1.25 0 0 0 0-2.5'],
            ['Trocas e devoluções', 'Em até 90 dias', 'M21 12a9 9 0 1 1-3-6.7M21 4v5h-5'],
            ['Entrega acompanhada', 'Você sabe onde está o pedido', 'M12 3 5 6v6c0 4.3 3 7.4 7 9 4-1.6 7-4.7 7-9V6Zm-3 9 2 2 4-4'],
        ] as $benefit)
            <div @class(['text-center sm:px-4 sm:border-l sm:border-white/20' => $loop->iteration > 1])>
                <svg class="mx-auto size-6 text-aqua" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="{{ $benefit[2] }}"/></svg>
                <strong class="mt-2 block text-sm font-semibold">{{ $benefit[0] }}</strong>
                <span class="mt-1 block text-xs text-white/70">{{ $benefit[1] }}</span>
            </div>
        @endforeach
    </div>

    <div class="px-6 py-8 sm:px-10 sm:py-10">
        <nav aria-label="Entrar ou criar conta" class="flex justify-center gap-2 rounded-full bg-bg p-1.5">
            <a href="{{ route('login') }}" @if (request()->routeIs('login')) aria-current="page" @endif class="inline-flex min-h-11 flex-1 items-center justify-center rounded-full px-4 text-xs font-semibold text-navy transition hover:bg-white focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-blue aria-[current=page]:bg-navy aria-[current=page]:text-white">Entrar</a>
            <a href="{{ route('register') }}" @if (request()->routeIs('register')) aria-current="page" @endif class="inline-flex min-h-11 flex-1 items-center justify-center rounded-full px-4 text-xs font-semibold text-navy transition hover:bg-white focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-blue aria-[current=page]:bg-navy aria-[current=page]:text-white">Criar conta</a>
        </nav>

        @if ($errors->any())
            <p role="alert" aria-live="polite" class="mt-6 rounded-2xl bg-red/10 px-4 py-3 text-center text-sm font-semibold text-red">{{ $errors->first() }}</p>
        @endif

        <div class="mt-8 grid gap-8 lg:grid-cols-2 lg:gap-10">
            <form method="POST" action="{{ route('login.store') }}" class="space-y-5">@csrf
                <x-ui.input label="E-mail" type="email" name="email" maxlength="255" value="{{ old('email') }}" autocomplete="email" placeholder="voce@exemplo.com" required />
                <x-ui.input label="Senha" type="password" name="password" maxlength="255" autocomplete="current-password" placeholder="Digite sua senha" required />
                <div class="flex items-center justify-end">
                    <a href="{{ route('password.request') }}" class="rounded-full px-2 py-1 text-xs font-semibold text-navy underline decoration-blue/50 underline-offset-2 transition hover:text-blue focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-blue">Esqueceu minha senha?</a>
                </div>
                <x-ui.button type="submit" size="lg" class="w-full">Entrar</x-ui.button>
            </form>

            <div class="lg:border-l lg:border-aqua lg:pl-10">
                <div class="flex items-center gap-4" aria-hidden="true">
                    <span class="h-px flex-1 bg-aqua"></span>
                    <span class="text-xs font-semibold text-muted">OU</span>
                    <span class="h-px flex-1 bg-aqua"></span>
                </div>
                <p class="mt-6 text-center text-sm text-muted">Entre com uma conta conectada</p>
                <div class="mt-4 space-y-3">
                    @foreach ([
                        'Google' => [
                            ['#4285F4', 'M21.35 11.1H12v2.98h5.35a4.58 4.58 0 0 1-1.99 3.01v2.5h3.22c1.88-1.74 2.97-4.3 2.97-7.35 0-.63-.06-1.25-.2-1.84Z'],
                            ['#34A853', 'M12 22c2.7 0 4.96-.9 6.61-2.41l-3.22-2.5c-.9.6-2.04.96-3.39.96-2.6 0-4.81-1.76-5.6-4.12H3.1v2.58A10 10 0 0 0 12 22Z'],
                            ['#FBBC05', 'M6.4 13.93A6 6 0 0 1 6.1 12c0-.67.11-1.32.3-1.93V7.49H3.1a10 10 0 0 0 0 9.02l3.3-2.58Z'],
                            ['#EA4335', 'M12 5.98c1.47 0 2.79.51 3.83 1.5l2.85-2.85A9.65 9.65 0 0 0 12 2a10 10 0 0 0-8.9 5.49l3.3 2.58C7.19 7.74 9.4 5.98 12 5.98Z'],
                        ],
                        'Facebook' => [['#1877F2', 'M24 12.073c0-6.627-5.373-12-12-12s-12 5.373-12 12c0 5.99 4.388 10.954 10.125 11.854v-8.385H7.078v-3.47h3.047V9.43c0-3.007 1.792-4.669 4.533-4.669 1.312 0 2.686.235 2.686.235v2.953H15.83c-1.491 0-1.956.925-1.956 1.874v2.25h3.328l-.532 3.47h-2.796v8.385C19.612 23.027 24 18.062 24 12.073z']],
                        'Apple' => [['#010f37', 'M17.05 12.54c.03 3.02 2.65 4.02 2.68 4.03-.02.07-.42 1.45-1.38 2.87-.83 1.23-1.7 2.46-3.06 2.49-1.34.03-1.77-.81-3.3-.81-1.52 0-2 .78-3.27.84-1.31.05-2.3-1.33-3.13-2.56C3.98 17.1 2.5 12.7 4.02 9.9a4.72 4.72 0 0 1 3.98-2.43c1.24 0 2.41.83 3.17.83.75 0 2.17-1.03 3.66-.88.62.03 2.37.25 3.5 1.9-.09.06-2.09 1.22-2.07 3.63M14.7 5.7A4.6 4.6 0 0 0 15.8 2a4.7 4.7 0 0 0-3.06 1.6 4.38 4.38 0 0 0-1.1 3.55 3.87 3.87 0 0 0 3.06-1.45Z']],
                    ] as $provider => $providerPaths)
                        <x-ui.button variant="outline" size="lg" class="w-full" disabled title="Em breve">
                            <svg class="size-5" viewBox="0 0 24 24" aria-hidden="true">
                                @foreach ($providerPaths as [$providerColor, $providerPath])
                                    <path d="{{ $providerPath }}" fill="{{ $providerColor }}" />
                                @endforeach
                            </svg>
                            {{ $provider }}
                        </x-ui.button>
                    @endforeach
                </div>
            </div>
        </div>

        <p class="mt-8 text-center text-xs text-muted">Ainda não tem conta? <a href="{{ route('register') }}" class="rounded-full px-1 font-semibold text-navy underline decoration-blue/50 underline-offset-2 focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-blue">Cadastre-se</a></p>
    </div>

    <div class="border-t border-aqua bg-bg/70 px-6 py-5 text-center text-xs text-muted">
        Ao continuar, concorda com os <a href="#" class="font-semibold text-navy underline focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-blue">Termos de Uso</a> e <a href="#" class="font-semibold text-navy underline focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-blue">Política de Privacidade</a>
    </div>
</div>
@endsection
