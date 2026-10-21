<?php
$adminPage  = 'estoque';
$adminTitle = 'Estoque';
require __DIR__ . '/includes/layout-top.php';
?>
<section class="kpis" id="stock-kpis" aria-label="Resumo do estoque"></section>

<section class="panel">
    <div class="panel__head">
        <h2 class="panel__title">Materiais de impressão</h2>
        <button class="a-btn a-btn--grad a-btn--sm" type="button" id="new-material">+ Adicionar</button>
    </div>
    <div class="table-wrap">
        <table class="table">
            <thead>
                <tr><th>Material</th><th>Tipo</th><th>Nível</th><th>Preço/kg</th><th><span class="sr-only">Ações</span></th></tr>
            </thead>
            <tbody id="materials-body"></tbody>
        </table>
    </div>
</section>

<section class="panel">
    <h2 class="panel__title">Consumo de materiais (kg/mês)</h2>
    <div class="chart" id="chart-consumo"></div>
</section>

<?php require __DIR__ . '/includes/layout-bottom.php'; ?>
