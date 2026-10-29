<?php
$pageTitle  = 'Novidades';
$activePage = 'novidades';
require __DIR__ . '/includes/header.php';

// Lorem ipsums: produtos marcados como novos primeiro
$launches = $products;
usort($launches, fn($a, $b) => ($b['badge'] === 'novo') <=> ($a['badge'] === 'novo'));
$featured = $launches[0] ?? null;
?>

<section class="page-head">
    <div class="container">
        <span class="eyebrow">Lorem ipsum</span>
        <h1>Novidades</h1>
        <p>Lorem ipsum dolor sit amet consectetur adipisicing elit.</p>
    </div>
</section>

<section class="container">
    <?php if ($featured): ?>
    <!-- Lorem ipsum em destaque -->
    <article class="featured">
        <div class="featured__media">
            <?php if ($featured['badge'] === 'novo'): ?><span class="badge badge--new">Novo</span><?php endif; ?>
            <img src="<?= e($featured['img']) ?>" alt="<?= e($featured['name']) ?>">
        </div>
        <div class="featured__body">
            <span class="eyebrow">Lorem ipsum<?= !empty($featured['launch']) ? ' · ' . e($featured['launch']) : '' ?></span>
            <h2><?= e($featured['name']) ?></h2>
            <p><?= e($featured['desc']) ?></p>
            <div class="featured__rating">
                <span class="stars" aria-hidden="true">★★★★★</span>
                <span><?= number_format((float) ($featured['rating'] ?? 5), 1, ',', '') ?> (<?= (int) ($featured['reviews'] ?? 0) ?> avaliações)</span>
            </div>
            <p class="featured__price"><?= money($featured['price']) ?></p>
            <div>
                <button class="btn btn--primary js-add-to-cart" type="button"
                        data-id="<?= (int) $featured['id'] ?>"
                        data-name="<?= e($featured['name']) ?>"
                        data-price="<?= e((string) $featured['price']) ?>"
                        data-img="<?= e($featured['img']) ?>">Adicionar ao carrinho</button>
            </div>
        </div>
    </article>
    <?php endif; ?>

    <!-- Dolor sit amet -->
    <div class="section__head">
        <h2 class="section__title">Dolor sit amet</h2>
    </div>
    <div class="product-grid pb-20">
        <?php foreach ($launches as $p) product_card($p, ['showCategory' => true]); ?>
    </div>
</section>

<?php require __DIR__ . '/includes/footer.php'; ?>
