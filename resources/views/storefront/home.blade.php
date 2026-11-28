@extends('layouts.storefront', ['title' => 'Início'])

@section('content')
<section>
    <div>
        <div>
            <span>Impressão 3D</span>
            <h1>Ideias que ganham forma</h1>
            <p>Explore peças e produtos feitos com tecnologia de impressão 3D, com cuidado em cada detalhe.</p>
            <div>
                <a href="{{ route('products.index') }}">Ver catálogo</a>
                <a href="{{ route('storefront.about') }}#como-funciona">Como funciona</a>
            </div>
            <div><span>FDM</span><span>SLA</span><span>MSLA</span></div>
        </div>
        <div><img src="{{ asset('images/dimensiona/placeholder.svg') }}" alt="Ilustração de uma peça criada em impressão 3D"></div>
    </div>
</section>

<section aria-label="Diferenciais">
    <div><strong>Impressão 3D</strong><span>Peças feitas com precisão</span></div>
    <div><strong>Vários materiais</strong><span>Opções para diferentes projetos</span></div>
    <div><strong>Compra simples</strong><span>Catálogo fácil de explorar</span></div>
    <div><strong>Suporte</strong><span>Estamos aqui para ajudar</span></div>
</section>

<section>
    <div>
        <div><h2>Categorias</h2><a href="{{ route('categories.index') }}">Ver todas</a></div>
        <div>
            @forelse ($categories as $category)
                <a href="{{ route('products.index', ['category' => $category->id]) }}">
                    @if ($category->image_url)
                        <img src="{{ $category->image_url }}" alt="">
                    @else
                        <span aria-hidden="true">{{ str_pad((string) $loop->iteration, 2, '0', STR_PAD_LEFT) }}</span>
                    @endif
                    {{ $category->name }}
                    <span>{{ $category->products_count }} produtos</span>
                </a>
            @empty
                <p>As categorias serão exibidas aqui em breve.</p>
            @endforelse
        </div>
    </div>
</section>

<section>
    <div>
        <div><h2>Produtos em destaque</h2><a href="{{ route('products.index') }}">Ver catálogo</a></div>
        <div>
            @forelse ($products as $product)
                <x-storefront.product-card :product="$product" />
            @empty
                <p>Ainda não há produtos cadastrados.</p>
            @endforelse
        </div>
    </div>
</section>

<section>
    <div>
        <div><img src="{{ asset('images/dimensiona/placeholder.svg') }}" alt="Peça geométrica produzida por impressão 3D"></div>
        <div><span>Tecnologia e criatividade</span><h2>Do projeto à peça pronta</h2><p>Conheça as tecnologias e os materiais que usamos para transformar ideias em produtos.</p><a href="{{ route('storefront.about') }}#tecnologias">Saiba mais</a></div>
    </div>
</section>

<section>
    <div><img src="{{ asset('images/dimensiona/logo.svg') }}" alt="" width="28" height="28"><h2>Encontre seu próximo projeto</h2><p>Explore nosso catálogo e descubra o que a impressão 3D pode criar.</p><a href="{{ route('products.index') }}">Explorar catálogo</a></div>
</section>
@endsection
