<?php
$pageTitle  = 'Promoções';
$activePage = 'promocoes';
require __DIR__ . '/includes/header.php';

$onSale = array_filter($products, fn($p) => !empty($p['old_price']));
?>

<section class="page-head">
    <div class="container">
        <span class="eyebrow">Lorem ipsum</span>
        <h1>Promoções</h1>
        <p>Lorem ipsum dolor sit amet consectetur adipisicing elit.<?php if (COUPON_CODE): ?> Some ao cupom <strong><?= e(COUPON_CODE) ?></strong> e ganhe mais <?= pct(COUPON_DISCOUNT) ?>% de desconto.<?php endif; ?></p>
    </div>
</section>

<section class="container pb-20">
    <?php if ($onSale): ?>
        <div class="product-grid">
            <?php foreach ($onSale as $p) product_card($p, ['showCategory' => true]); ?>
        </div>
    <?php else: ?>
        <div class="empty-state">
            <h2>Nenhuma promoção ativa agora</h2>
            <p>Assine a newsletter para saber primeiro das próximas ofertas.</p>
            <a href="index.php#newsletter-form" class="btn btn--primary">Assinar newsletter</a>
        </div>
    <?php endif; ?>

    <div class="section__head mt-16">
        <h2 class="section__title">Dolor sit amet consectetur</h2>
        <a href="categorias.php" class="section__link">Ver catálogo</a>
    </div>
    <div class="product-grid">
        <?php foreach ($products as $p) if (empty($p['old_price'])) product_card($p, ['showCategory' => true]); ?>
    </div>
</section>

<?php require __DIR__ . '/includes/footer.php'; ?>
