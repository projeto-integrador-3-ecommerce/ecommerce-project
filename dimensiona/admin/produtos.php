<?php
$adminPage  = 'produtos';
$adminTitle = 'Produtos';
require __DIR__ . '/includes/layout-top.php';
?>
<div class="toolbar">
    <p class="toolbar__count" id="product-count"></p>
    <div class="toolbar__right">
        <input class="a-input a-input--sm" type="search" id="product-search" placeholder="Buscar produto" aria-label="Buscar produto">
        <button class="a-btn a-btn--grad" type="button" id="new-product">+ Novo produto</button>
    </div>
</div>

<section class="panel">
    <div class="table-wrap">
        <table class="table">
            <thead>
                <tr><th>Imagem</th><th>Nome</th><th>Preço</th><th>Categoria</th><th>Badge</th><th>Status</th><th><span class="sr-only">Ações</span></th></tr>
            </thead>
            <tbody id="products-body"></tbody>
        </table>
    </div>
    <p class="empty" id="products-empty" hidden>Nenhum produto encontrado.</p>
</section>

<?php require __DIR__ . '/includes/layout-bottom.php'; ?>
