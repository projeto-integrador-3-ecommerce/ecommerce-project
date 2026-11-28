@extends('layouts.storefront', ['title' => 'Início'])

@section('content')
@if ($categories->isNotEmpty() && $categories->first()->image_url)
    <img src="{{ $categories->first()->image_url }}" alt="" aria-hidden="true" class="hidden">
@endif
<section class="grid items-center gap-10 lg:grid-cols-[1fr_1.05fr] lg:gap-14">
    <div>
        <span class="inline-flex items-center gap-2 rounded-full border border-navy/20 bg-white/70 px-4 py-1.5 text-[11px] font-semibold tracking-[0.14em] text-navy uppercase">Ideias que ganham forma</span>
        <h1 class="mt-5 text-4xl leading-[1.05] font-bold tracking-tight sm:text-5xl lg:text-6xl">
            <span class="block text-transparent [-webkit-text-stroke:1.5px_#010f37]">Dê vida às suas</span>
            <span class="block text-navy">ideias em 3D</span>
        </h1>
        <p class="mt-5 max-w-xl text-sm leading-relaxed text-muted">Explore peças e produtos feitos com tecnologia de impressão 3D, com cuidado em cada detalhe.</p>
        <div class="mt-8">
            <x-ui.link href="{{ route('products.index') }}" size="lg">Ver catálogo</x-ui.link>
        </div>
    </div>

    <div data-carousel class="relative min-w-0 rounded-3xl bg-navy/5 p-2 shadow-[0_18px_50px_rgba(1,15,55,0.12)] sm:p-3">
        <div data-carousel-track class="flex snap-x snap-mandatory gap-2 overflow-x-auto rounded-3xl [scrollbar-width:none] [&::-webkit-scrollbar]:hidden sm:gap-3">
            @foreach ([
                ['Impressão 3D', 'Peças feitas com precisão e cuidado em cada detalhe.'],
                ['Cada detalhe importa', 'Sua ideia ganha volume com tecnologia e criatividade.'],
                ['Entrega para todo o Brasil', 'Escolha, compre e acompanhe seu pedido de perto.'],
            ] as $slide)
                <div data-carousel-slide class="relative shrink-0 snap-center basis-full overflow-hidden rounded-3xl bg-gradient-to-br from-aqua via-bg to-mist">
                    <img src="{{ asset('images/dimensiona/placeholder.svg') }}" alt="{{ $slide[0] }}" class="aspect-[4/3] w-full object-contain p-8 opacity-70">
                    <div class="absolute inset-x-4 bottom-4 rounded-2xl bg-navy/85 px-4 py-3 text-white backdrop-blur">
                        <p class="text-sm font-semibold">{{ $slide[0] }}</p>
                        <p class="mt-1 text-xs text-white/75">{{ $slide[1] }}</p>
                    </div>
                </div>
            @endforeach
        </div>
        <button data-carousel-prev type="button" aria-label="Slide anterior"
                class="absolute top-1/2 left-4 inline-grid size-11 -translate-y-1/2 place-items-center rounded-full border border-white/60 bg-navy/55 text-white transition hover:bg-navy focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-blue sm:left-6">
            <svg class="size-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="m15 18-6-6 6-6"/></svg>
        </button>
        <button data-carousel-next type="button" aria-label="Próximo slide"
                class="absolute top-1/2 right-4 inline-grid size-11 -translate-y-1/2 place-items-center rounded-full border border-white/60 bg-navy/55 text-white transition hover:bg-navy focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-blue sm:right-6">
            <svg class="size-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="m9 6 6 6-6 6"/></svg>
        </button>
        <div class="mt-3 flex items-center justify-center gap-2" aria-label="Escolher slide">
            @foreach ([1, 2, 3] as $slideIndex)
                <button data-carousel-dot data-carousel-index="{{ $slideIndex - 1 }}" type="button" aria-label="Ir para o slide {{ $slideIndex }}" @if ($slideIndex === 1) aria-current="true" @endif
                        class="size-2.5 rounded-full bg-navy/25 transition hover:bg-navy focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-blue aria-[current=true]:w-6 aria-[current=true]:bg-navy"></button>
            @endforeach
        </div>
    </div>
</section>

<section aria-label="Diferenciais" class="mt-16 grid grid-cols-2 gap-3 lg:grid-cols-4 lg:gap-5">
    @foreach ([
        ['Impressão 3D', 'Peças feitas com precisão'],
        ['Vários materiais', 'Opções para diferentes projetos'],
        ['Compra simples', 'Catálogo fácil de explorar'],
        ['Suporte', 'Estamos aqui para ajudar'],
    ] as $differential)
        <div class="rounded-3xl border border-aqua bg-white/80 p-4 shadow-sm sm:p-5">
            <strong class="block text-sm font-semibold text-navy">{{ $differential[0] }}</strong>
            <span class="mt-1 block text-xs text-muted">{{ $differential[1] }}</span>
        </div>
    @endforeach
</section>

<section class="mt-16">
    <div class="flex flex-wrap items-end justify-between gap-3">
        <div>
            <span class="text-xs font-semibold tracking-[0.14em] text-teal uppercase">Acabaram de chegar</span>
            <h2 class="mt-1 text-2xl font-semibold text-navy sm:text-3xl">Produtos em destaque</h2>
        </div>
        <a href="{{ route('products.index') }}" class="inline-flex min-h-11 items-center gap-1 rounded-full px-3 text-xs font-semibold text-navy transition hover:bg-aqua/40 focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-blue">Ver catálogo <span aria-hidden="true">›</span></a>
    </div>
    <div class="mt-6 grid grid-cols-2 gap-4 sm:gap-5 md:grid-cols-3">
        @forelse ($products as $product)
            <x-storefront.product-card :product="$product" :forceNewBadge="true" />
        @empty
            <p class="col-span-full rounded-3xl border border-aqua bg-white p-6 text-sm text-muted">Ainda não há produtos cadastrados.</p>
        @endforelse
    </div>
    <div class="mt-10 flex justify-center">
        <x-ui.link href="{{ route('products.index') }}" variant="outline" size="lg">
            Ver mais
            <svg class="size-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="m6 9 6 6 6-6"/></svg>
        </x-ui.link>
    </div>
</section>
@endsection
