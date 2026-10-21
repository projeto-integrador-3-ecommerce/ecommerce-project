@extends('layouts.storefront', ['title' => 'Início'])

@section('content')
<section class="hero">
    <div class="container hero__grid">
        <div>
            <span class="pill-new">Impressão 3D</span>
            <h1 class="hero__title">Ideias que ganham forma</h1>
            <p class="hero__text">Explore peças e produtos feitos com tecnologia de impressão 3D, com cuidado em cada detalhe.</p>
            <div class="btn-row">
                <a href="{{ route('products.index') }}" class="btn btn--primary">Ver catálogo</a>
                <a href="{{ route('storefront.about') }}#como-funciona" class="btn btn--outline">Como funciona</a>
            </div>
            <div class="hero__pills"><span>FDM</span><span>SLA</span><span>MSLA</span></div>
        </div>
        <div class="hero__media"><img src="{{ asset('images/dimensiona/placeholder-wide.svg') }}" alt="Peças produzidas em impressão 3D"></div>
    </div>
</section>

<section class="features" aria-label="Diferenciais">
    <div class="feature"><strong>Impressão 3D</strong><span>Peças feitas com precisão</span></div>
    <div class="feature"><strong>Vários materiais</strong><span>Opções para diferentes projetos</span></div>
    <div class="feature"><strong>Compra simples</strong><span>Catálogo fácil de explorar</span></div>
    <div class="feature"><strong>Suporte</strong><span>Estamos aqui para ajudar</span></div>
</section>

<section class="section">
    <div class="container">
        <div class="section__head"><h2 class="section__title">Categorias</h2><a href="{{ route('categories.index') }}" class="section__link">Ver todas</a></div>
        <div class="cat-grid">
            @forelse ($categories as $category)
                <a href="{{ route('products.index', ['category' => $category->id]) }}" class="cat-card">
                    <span class="cat-card__img" aria-hidden="true">Imagem</span>{{ $category->name }}
                    <span class="cat-card__desc">{{ $category->products_count }} produtos</span>
                </a>
            @empty
                <p class="text-muted">As categorias serão exibidas aqui em breve.</p>
            @endforelse
        </div>
    </div>
</section>

<section class="section section--alt">
    <div class="container">
        <div class="section__head"><h2 class="section__title">Produtos em destaque</h2><a href="{{ route('products.index') }}" class="section__link">Ver catálogo</a></div>
        <div class="product-grid">
            @forelse ($products as $product)
                <x-storefront.product-card :product="$product" />
            @empty
                <p class="text-muted">Ainda não há produtos cadastrados.</p>
            @endforelse
        </div>
    </div>
</section>

<section class="section">
    <div class="container split">
        <div class="split__media"><img src="{{ asset('images/dimensiona/placeholder-wide.svg') }}" alt="Impressão 3D em diferentes materiais"></div>
        <div><span class="eyebrow">Tecnologia e criatividade</span><h2>Do projeto à peça pronta</h2><p>Conheça as tecnologias e os materiais que usamos para transformar ideias em produtos.</p><a href="{{ route('storefront.about') }}#tecnologias" class="btn btn--primary">Saiba mais</a></div>
    </div>
</section>

<section class="section section--deep newsletter">
    <div class="container"><img src="{{ asset('images/dimensiona/logo.svg') }}" alt="" width="28" height="28"><h2>Encontre seu próximo projeto</h2><p>Explore nosso catálogo e descubra o que a impressão 3D pode criar.</p><a class="btn btn--primary" href="{{ route('products.index') }}">Explorar catálogo</a></div>
</section>
@endsection
