@extends('layouts.storefront', ['title' => 'Sobre nós'])

@section('content')
<section class="rounded-3xl border border-aqua bg-white/80 p-6 text-center shadow-sm sm:p-12">
    <x-ui.logo size="lg" class="mx-auto" />
    <span class="mt-5 block text-xs font-semibold tracking-[0.14em] text-teal uppercase">Dimensiona</span>
    <h1 class="mt-2 text-3xl font-semibold text-navy sm:text-4xl">Impressão 3D para tirar ideias do papel</h1>
    <p class="mx-auto mt-4 max-w-2xl text-sm text-muted">Conheça um pouco mais sobre os produtos e o cuidado envolvido em cada etapa.</p>
    <x-ui.link href="{{ route('products.index') }}" class="mt-7">Explorar catálogo</x-ui.link>
</section>

<section aria-label="O que fazemos" class="mt-10 grid grid-cols-2 gap-3 lg:grid-cols-4">
    @foreach ([['Peças sob medida', 'Feitas para o seu projeto'], ['Protótipos', 'Ideias em volume'], ['Acessórios', 'Para o dia a dia'], ['Decoração', 'Com personalidade']] as $highlight)
        <div class="rounded-3xl border border-aqua bg-white p-5 text-center shadow-sm">
            <strong class="block text-lg font-semibold text-navy">{{ $highlight[0] }}</strong>
            <span class="mt-1 block text-xs text-muted">{{ $highlight[1] }}</span>
        </div>
    @endforeach
</section>

<section class="mt-14 grid items-center gap-8 lg:grid-cols-2">
    <div class="overflow-hidden rounded-3xl border border-aqua bg-white p-4 shadow-sm">
        <img src="{{ asset('images/dimensiona/placeholder.svg') }}" alt="Peças produzidas em impressão 3D" class="aspect-[4/3] w-full rounded-3xl bg-mist/40 object-contain p-6">
    </div>
    <div>
        <span class="text-xs font-semibold tracking-[0.14em] text-teal uppercase">Nossa proposta</span>
        <h2 class="mt-2 text-2xl font-semibold text-navy sm:text-3xl">Tecnologia e criatividade em cada peça</h2>
        <p class="mt-4 text-sm leading-relaxed text-muted">Criamos peças funcionais, acessórios e objetos para o dia a dia, com atenção ao detalhe em cada etapa.</p>
        <ul class="mt-6 space-y-3 text-sm text-navy">
            @foreach (['Catálogo organizado por categoria', 'Produtos com informações detalhadas', 'Compra e acompanhamento pelo site'] as $item)
                <li class="flex items-start gap-3"><span class="mt-0.5 grid size-5 shrink-0 place-items-center rounded-full bg-teal text-white"><svg class="size-3" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="m5 12 4 4L19 6"/></svg></span>{{ $item }}</li>
            @endforeach
        </ul>
    </div>
</section>

<section id="etapas" class="mt-14">
    <div>
        <span class="text-xs font-semibold tracking-[0.14em] text-teal uppercase">Nossa forma de trabalhar</span>
        <h2 class="mt-2 text-2xl font-semibold text-navy sm:text-3xl">Do detalhe à entrega</h2>
    </div>
    <div class="mt-6 grid gap-4 md:grid-cols-3">
        @foreach ([['01', 'Entendemos a ideia', 'Você escolhe a peça e compartilha o que precisa.'], ['02', 'Cuidamos de cada etapa', 'Preparamos o produto com atenção aos detalhes.'], ['03', 'Acompanhamos a entrega', 'Você acompanha o pedido até receber.']] as $step)
            <article class="rounded-3xl border border-aqua bg-white p-6 shadow-sm">
                <x-ui.badge variant="blue">{{ $step[0] }}</x-ui.badge>
                <h3 class="mt-4 text-base font-semibold text-navy">{{ $step[1] }}</h3>
                <p class="mt-3 text-sm text-muted">{{ $step[2] }}</p>
            </article>
        @endforeach
    </div>
</section>

<section id="como-comprar" class="mt-14 grid items-center gap-8 lg:grid-cols-2">
    <div>
        <span class="text-xs font-semibold tracking-[0.14em] text-teal uppercase">Do catálogo à compra</span>
        <h2 class="mt-2 text-2xl font-semibold text-navy sm:text-3xl">Como comprar</h2>
        <ol class="mt-6 space-y-4">
            @foreach ([['01', 'Explore o catálogo', 'Encontre uma peça ou produto.'], ['02', 'Entre na sua conta', 'Faça login para adicionar ao carrinho.'], ['03', 'Finalize o pedido', 'Escolha os itens e acompanhe seu pedido.']] as $step)
                <li class="flex items-start gap-4 rounded-3xl border border-aqua bg-white p-5 shadow-sm">
                    <span class="grid size-10 shrink-0 place-items-center rounded-full bg-navy text-xs font-bold text-white">{{ $step[0] }}</span>
                    <div><strong class="block text-sm font-semibold text-navy">{{ $step[1] }}</strong><span class="mt-1 block text-xs text-muted">{{ $step[2] }}</span></div>
                </li>
            @endforeach
        </ol>
    </div>
    <div class="overflow-hidden rounded-3xl border border-aqua bg-white p-4 shadow-sm">
        <img src="{{ asset('images/dimensiona/placeholder.svg') }}" alt="Peças impressas em 3D" class="aspect-square w-full rounded-3xl bg-mist/40 object-contain p-6">
    </div>
</section>

<section class="mt-14 rounded-3xl bg-navy p-8 text-center text-white sm:p-12">
    <h2 class="text-2xl font-semibold sm:text-3xl">Vamos encontrar o que você precisa?</h2>
    <p class="mt-3 text-sm text-white/75">Veja os produtos disponíveis na loja.</p>
    <div class="mt-6 flex flex-wrap justify-center gap-3">
        <x-ui.link href="{{ route('products.index') }}" variant="light">Ver catálogo</x-ui.link>
        <x-ui.link href="#contato" variant="light">Falar conosco</x-ui.link>
    </div>
</section>

<footer id="contato" class="mt-10 grid items-start gap-4 rounded-3xl border border-aqua bg-white p-6 shadow-sm sm:grid-cols-3 sm:gap-6">
    <div>
        <strong class="block text-sm font-semibold text-navy">Conta</strong>
        <a href="{{ route('login') }}" class="mt-2 inline-flex min-h-11 items-center rounded-full px-0 py-2 text-xs leading-relaxed text-muted transition hover:text-navy hover:underline focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-blue">Acessar ou criar conta</a>
    </div>
    <div>
        <strong class="block text-sm font-semibold text-navy">Pedidos</strong>
        <a href="{{ auth()->check() ? route('orders.index') : route('login') }}" class="mt-2 inline-flex min-h-11 items-center rounded-full px-0 py-2 text-xs leading-relaxed text-muted transition hover:text-navy hover:underline focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-blue">Acompanhar pedido</a>
    </div>
    <div>
        <strong class="block text-sm font-semibold text-navy">Horário</strong>
        <span class="mt-2 block py-2 text-xs leading-relaxed text-muted">Seg. - Sex. das 9h às 18h</span>
    </div>
</footer>
@endsection
