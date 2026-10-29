@extends('layouts.storefront', ['title' => 'Novidades'])

@section('content')
<section class="page-head"><div class="container"><span class="eyebrow">Acabaram de chegar</span><h1>Novidades</h1><p>Confira os produtos mais recentes do catálogo.</p></div></section>
<section class="container pb-20">
    <div class="product-grid">
        @forelse ($products as $product)
            <x-storefront.product-card :product="$product" />
        @empty
            <div class="empty-state"><h2>Nenhuma novidade por enquanto</h2><p>Novos produtos aparecerão aqui assim que forem cadastrados.</p><a href="{{ route('products.index') }}" class="btn btn--primary">Ver catálogo</a></div>
        @endforelse
    </div>
</section>
@endsection
