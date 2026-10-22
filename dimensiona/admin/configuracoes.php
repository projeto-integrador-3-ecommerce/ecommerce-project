<?php
$adminPage  = 'configuracoes';
$adminTitle = 'Configurações';
require __DIR__ . '/includes/layout-top.php';
?>
<div class="settings">
    <form class="panel js-settings" data-section="info" novalidate>
        <h2 class="panel__title">Informações da loja</h2>
        <div class="a-field"><label for="cfg-nome">Nome da loja</label><input class="a-input" id="cfg-nome" name="nome" required></div>
        <div class="a-field"><label for="cfg-cnpj">CNPJ</label><input class="a-input" id="cfg-cnpj" name="cnpj" inputmode="numeric"></div>
        <div class="a-field"><label for="cfg-email">E-mail de contato</label><input class="a-input" type="email" id="cfg-email" name="email" required></div>
        <div class="a-field"><label for="cfg-tel">Telefone</label><input class="a-input" id="cfg-tel" name="telefone"></div>
        <button class="a-btn a-btn--grad" type="submit">Salvar alterações</button>
    </form>

    <form class="panel js-settings" data-section="frete" novalidate>
        <h2 class="panel__title">Frete e entrega</h2>
        <div class="a-field"><label for="cfg-frete-min">Frete grátis a partir de (R$)</label><input class="a-input" type="number" min="0" step="0.01" id="cfg-frete-min" name="frete_gratis_min" required></div>
        <div class="a-field"><label for="cfg-frete">Frete padrão (R$)</label><input class="a-input" type="number" min="0" step="0.01" id="cfg-frete" name="frete_padrao" required></div>
        <div class="a-field"><label for="cfg-prazo">Prazo médio de entrega (dias úteis)</label><input class="a-input" type="number" min="1" id="cfg-prazo" name="prazo_entrega" required></div>
        <div class="a-field"><label for="cfg-cep">CEP de origem</label><input class="a-input" id="cfg-cep" name="cep_origem" inputmode="numeric"></div>
        <button class="a-btn a-btn--grad" type="submit">Salvar alterações</button>
    </form>

    <form class="panel js-settings" data-section="cupom" novalidate>
        <h2 class="panel__title">Cupom e promoções</h2>
        <div class="a-field"><label for="cfg-cupom">Código ativo</label><input class="a-input" id="cfg-cupom" name="codigo" placeholder="Deixe vazio para desativar"></div>
        <div class="a-field"><label for="cfg-desc">Desconto (%)</label><input class="a-input" type="number" min="1" max="90" id="cfg-desc" name="desconto"></div>
        <div class="a-field"><label for="cfg-min">Pedido mínimo (R$)</label><input class="a-input" type="number" min="0" step="0.01" id="cfg-min" name="minimo"></div>
        <button class="a-btn a-btn--grad" type="submit">Salvar alterações</button>
    </form>

    <section class="panel panel--danger">
        <h2 class="panel__title">Zona de risco</h2>
        <p class="panel__text" id="maint-text"></p>
        <div class="btn-row">
            <button class="a-btn a-btn--danger-outline" type="button" id="toggle-maint"></button>
            <button class="a-btn a-btn--outline" type="button" id="export-data">Exportar dados</button>
        </div>
    </section>
</div>

<?php require __DIR__ . '/includes/layout-bottom.php'; ?>
