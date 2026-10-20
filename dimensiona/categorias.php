<?php
$pageTitle  = 'Categorias';
$activePage = 'categorias';
require __DIR__ . '/includes/header.php';

$initial = $_GET['cat'] ?? 'todos';
if ($initial !== 'todos' && !isset($categories[$initial])) {
    $initial = 'todos';
}
?>

<section class="page-head">
    <div class="container">
        <span class="eyebrow">Lorem ipsum dolor</span>
        <h1>Categorias</h1>

        <div class="chip-row mt-5" id="category-filter" data-initial="<?= e($initial) ?>" aria-label="Filtrar por categoria">
            <button type="button" class="chip" data-cat="todos" aria-pressed="false">Todos</button>
            <?php foreach ($categories as $slug => $cat): ?>
                <button type="button" class="chip" data-cat="<?= $slug ?>" aria-pressed="false">
                    <?= e($cat['name']) ?>
                </button>
            <?php endforeach; ?>
        </div>
    </div>
</section>

<section class="container">
    <div class="cat-grid">
        <?php foreach ($categories as $slug => $cat): ?>
            <a href="categorias.php?cat=<?= $slug ?>" class="cat-card js-cat-link" data-cat="<?= $slug ?>">
                <span class="cat-card__img" aria-hidden="true">Imagem</span>
                <?= e($cat['name']) ?>
                <span class="cat-card__desc"><?= e($cat['desc']) ?></span>
            </a>
        <?php endforeach; ?>
    </div>
</section>

<section class="section" id="todos-os-produtos">
    <div class="container">
        <div class="section__head">
            <h2 class="section__title">Todos os produtos</h2>
            <span class="section__link text-faint" id="catalog-count"><?= count($products) ?> produtos</span>
        </div>
        <div class="product-grid" id="catalog-grid">
            <?php foreach ($products as $p) product_card($p, ['tag' => true, 'buttonLabel' => '+ Adicionar ao carrinho']); ?>
        </div>
    </div>
</section>

<?php require __DIR__ . '/includes/footer.php'; ?>
