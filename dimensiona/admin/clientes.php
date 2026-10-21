<?php
$adminPage  = 'clientes';
$adminTitle = 'Clientes';
require __DIR__ . '/includes/layout-top.php';
?>
<section class="kpis" id="client-kpis" aria-label="Indicadores de clientes"></section>

<section class="panel">
    <h2 class="panel__title">Crescimento de clientes — últimos 6 meses</h2>
    <div class="chart" id="chart-clientes"></div>
</section>

<section class="panel">
    <h2 class="panel__title">Melhores clientes</h2>
    <ol class="list list--ranked" id="best-clients"></ol>
</section>

<div class="grid grid--2">
    <section class="panel">
        <h2 class="panel__title">Retenção mensal</h2>
        <div class="chart" id="chart-retencao"></div>
    </section>
    <section class="panel">
        <h2 class="panel__title">Avaliações recebidas</h2>
        <ul class="ratings" id="ratings"></ul>
    </section>
</div>

<?php require __DIR__ . '/includes/layout-bottom.php'; ?>
