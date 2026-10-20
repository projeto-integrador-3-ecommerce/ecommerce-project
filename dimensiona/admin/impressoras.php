<?php
$adminPage  = 'impressoras';
$adminTitle = 'Impressoras';
require __DIR__ . '/includes/layout-top.php';
?>
<section class="kpis kpis--3" id="printer-kpis" aria-label="Status das impressoras"></section>

<section class="printer-grid" id="printer-grid" aria-label="Impressoras"></section>

<section class="panel">
    <h2 class="panel__title">Horas de impressão ativa — últimos 7 dias</h2>
    <div class="chart" id="chart-horas"></div>
</section>

<?php require __DIR__ . '/includes/layout-bottom.php'; ?>
