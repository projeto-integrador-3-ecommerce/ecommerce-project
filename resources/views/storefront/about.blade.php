@extends('layouts.storefront', ['title' => 'Sobre nós'])

@section('content')
<section class="about-hero"><div class="container">
    <img src="{{ asset('images/dimensiona/logo.svg') }}" alt="" width="36" height="36">
    <span class="eyebrow">Dimensiona</span><h1>Impressão 3D para tirar ideias do papel</h1>
    <p>Conheça um pouco mais sobre as tecnologias e os processos por trás dos nossos produtos.</p>
    <a href="{{ route('products.index') }}" class="btn btn--primary">Explorar catálogo</a>
</div></section>
<section class="stats" aria-label="Tecnologias disponíveis"><div class="stat"><strong>FDM</strong><span>Filamento</span></div><div class="stat"><strong>SLA</strong><span>Resina</span></div><div class="stat"><strong>MSLA</strong><span>Alta resolução</span></div><div class="stat"><strong>3D</strong><span>Novas possibilidades</span></div></section>
<section class="section"><div class="container split">
    <div class="split__media"><img src="{{ asset('images/dimensiona/placeholder.svg') }}" alt="Peças produzidas em impressão 3D"></div>
    <div><span class="eyebrow">Nossa proposta</span><h2 class="serif">Tecnologia e criatividade em cada peça</h2><p>Trabalhamos com diferentes métodos de impressão 3D para criar peças funcionais, acessórios e objetos para o dia a dia.</p><ul class="checklist"><li>Catálogo organizado por categoria</li><li>Produtos com informações detalhadas</li><li>Compra e acompanhamento pelo site</li></ul></div>
</div></section>
<section class="section section--alt" id="tecnologias"><div class="container">
    <div class="center mb-8"><span class="eyebrow">Como produzimos</span><h2 class="section__title--serif">Tecnologias de impressão</h2></div>
    <div class="tech-grid">
        <article class="tech-card"><h3>FDM</h3><p class="tech-card__sub">Filamento fundido</p><p>Uma opção versátil para produzir peças resistentes em diferentes formatos.</p></article>
        <article class="tech-card"><h3>SLA</h3><p class="tech-card__sub">Resina</p><p>Processo indicado para peças com detalhes finos e superfícies suaves.</p></article>
        <article class="tech-card"><h3>MSLA</h3><p class="tech-card__sub">Resina com máscara LCD</p><p>Uma tecnologia precisa para imprimir várias peças com alto nível de detalhe.</p></article>
    </div>
</div></section>
<section class="section" id="como-funciona"><div class="container split">
    <div><span class="eyebrow">Do catálogo à compra</span><h2 class="serif">Como funciona</h2><ol class="steps">
        <li><span class="steps__num">01</span><div><strong>Explore o catálogo</strong><span>Encontre uma peça ou produto.</span></div></li>
        <li><span class="steps__num">02</span><div><strong>Entre na sua conta</strong><span>Faça login para adicionar ao carrinho.</span></div></li>
        <li><span class="steps__num">03</span><div><strong>Finalize o pedido</strong><span>Escolha os itens e acompanhe seu pedido.</span></div></li>
    </ol></div>
    <div class="split__media"><img src="{{ asset('images/dimensiona/placeholder.svg') }}" alt="Peças impressas em 3D"></div>
</div></section>
<section class="section cta"><div class="container"><h2>Vamos encontrar o que você precisa?</h2><p>Veja os produtos disponíveis na loja.</p><div class="btn-row"><a href="{{ route('products.index') }}" class="btn btn--primary">Ver catálogo</a><a href="#contato" class="btn btn--outline">Falar conosco</a></div></div></section>
<footer class="contact-strip" id="contato"><div><strong>Conta</strong><a href="{{ route('login') }}">Acessar ou criar conta</a></div><div><strong>Pedidos</strong><a href="{{ auth()->check() ? route('orders.index') : route('login') }}">Acompanhar pedido</a></div><div><strong>Horário</strong><span>Seg. - Sex. das 9h às 18h</span></div></footer>
@endsection
