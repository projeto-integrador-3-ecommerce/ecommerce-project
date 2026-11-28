@extends('layouts.storefront', ['title' => 'Sobre nós'])

@section('content')
<section><div>
    <img src="{{ asset('images/dimensiona/logo.svg') }}" alt="" width="36" height="36">
    <span>Dimensiona</span><h1>Impressão 3D para tirar ideias do papel</h1>
    <p>Conheça um pouco mais sobre as tecnologias e os processos por trás dos nossos produtos.</p>
    <a href="{{ route('products.index') }}">Explorar catálogo</a>
</div></section>
<section aria-label="Tecnologias disponíveis"><div><strong>FDM</strong><span>Filamento</span></div><div><strong>SLA</strong><span>Resina</span></div><div><strong>MSLA</strong><span>Alta resolução</span></div><div><strong>3D</strong><span>Novas possibilidades</span></div></section>
<section><div>
    <div><img src="{{ asset('images/dimensiona/placeholder.svg') }}" alt="Peças produzidas em impressão 3D"></div>
    <div><span>Nossa proposta</span><h2>Tecnologia e criatividade em cada peça</h2><p>Trabalhamos com diferentes métodos de impressão 3D para criar peças funcionais, acessórios e objetos para o dia a dia.</p><ul><li>Catálogo organizado por categoria</li><li>Produtos com informações detalhadas</li><li>Compra e acompanhamento pelo site</li></ul></div>
</div></section>
<section id="tecnologias"><div>
    <div><span>Como produzimos</span><h2>Tecnologias de impressão</h2></div>
    <div>
        <article><h3>FDM</h3><p>Filamento fundido</p><p>Uma opção versátil para produzir peças resistentes em diferentes formatos.</p></article>
        <article><h3>SLA</h3><p>Resina</p><p>Processo indicado para peças com detalhes finos e superfícies suaves.</p></article>
        <article><h3>MSLA</h3><p>Resina com máscara LCD</p><p>Uma tecnologia precisa para imprimir várias peças com alto nível de detalhe.</p></article>
    </div>
</div></section>
<section id="como-funciona"><div>
    <div><span>Do catálogo à compra</span><h2>Como funciona</h2><ol>
        <li><span>01</span><div><strong>Explore o catálogo</strong><span>Encontre uma peça ou produto.</span></div></li>
        <li><span>02</span><div><strong>Entre na sua conta</strong><span>Faça login para adicionar ao carrinho.</span></div></li>
        <li><span>03</span><div><strong>Finalize o pedido</strong><span>Escolha os itens e acompanhe seu pedido.</span></div></li>
    </ol></div>
    <div><img src="{{ asset('images/dimensiona/placeholder.svg') }}" alt="Peças impressas em 3D"></div>
</div></section>
<section><div><h2>Vamos encontrar o que você precisa?</h2><p>Veja os produtos disponíveis na loja.</p><div><a href="{{ route('products.index') }}">Ver catálogo</a><a href="#contato">Falar conosco</a></div></div></section>
<footer id="contato"><div><strong>Conta</strong><a href="{{ route('login') }}">Acessar ou criar conta</a></div><div><strong>Pedidos</strong><a href="{{ auth()->check() ? route('orders.index') : route('login') }}">Acompanhar pedido</a></div><div><strong>Horário</strong><span>Seg. - Sex. das 9h às 18h</span></div></footer>
@endsection
