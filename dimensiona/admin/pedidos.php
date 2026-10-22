<?php
$adminPage  = 'pedidos';
$adminTitle = 'Pedidos';
require __DIR__ . '/includes/layout-top.php';
?>
<div class="toolbar">
    <div class="tabs-row" id="order-tabs" role="tablist" aria-label="Filtrar pedidos por status"></div>
    <input class="a-input a-input--sm" type="search" id="order-search" placeholder="Buscar pedido ou cliente" aria-label="Buscar pedido ou cliente">
</div>

<section class="kpis" id="order-kpis" aria-label="Pedidos por status"></section>

<section class="panel">
    <div class="table-wrap">
        <table class="table">
            <thead>
                <tr><th>Pedido / cliente</th><th>Data</th><th>Itens</th><th>Total</th><th>Status</th><th><span class="sr-only">Ações</span></th></tr>
            </thead>
            <tbody id="orders-body"></tbody>
        </table>
    </div>
    <p class="empty" id="orders-empty" hidden>Nenhum pedido com esse filtro.</p>
</section>

<section class="panel">
    <h2 class="panel__title">Evolução de pedidos — últimos 6 meses</h2>
    <div class="chart" id="chart-pedidos"></div>
</section>

<?php require __DIR__ . '/includes/layout-bottom.php'; ?>
