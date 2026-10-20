<?php
$adminPage  = 'visao-geral';
$adminTitle = 'Visão Geral';
require __DIR__ . '/includes/layout-top.php';
?>
<section class="kpis" id="kpis" aria-label="Indicadores do mês"></section>

<div class="grid grid--2-1">
    <section class="panel">
        <h2 class="panel__title">Receita vs meta (últimos 6 meses)</h2>
        <div class="chart" id="chart-receita"></div>
    </section>
    <section class="panel">
        <h2 class="panel__title">Vendas por categoria</h2>
        <div id="chart-categorias"></div>
    </section>
</div>

<section class="panel">
    <h2 class="panel__title">Pedidos por dia (semana atual)</h2>
    <div class="chart" id="chart-semana"></div>
</section>

<div class="grid grid--2-1">
    <section class="panel">
        <div class="panel__head">
            <h2 class="panel__title">Pedidos recentes</h2>
            <a href="pedidos.php" class="panel__link">Ver todos</a>
        </div>
        <ul class="list" id="recent-orders"></ul>
    </section>
    <section class="panel">
        <h2 class="panel__title">Top produtos</h2>
        <ul class="list" id="top-products"></ul>
    </section>
</div>

<section class="panel panel--warn" id="stock-alerts" hidden>
    <h2 class="panel__title">Alertas de estoque</h2>
    <div class="alert-grid" id="stock-alert-list"></div>
</section>

<?php require __DIR__ . '/includes/layout-bottom.php'; ?>
