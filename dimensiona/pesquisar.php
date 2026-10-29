<?php
$pageTitle  = 'Pesquisar';
$activePage = 'pesquisar';
require __DIR__ . '/includes/header.php';

$q = trim($_GET['q'] ?? '');
$popular = ['Lorem', 'Ipsum', 'Dolor', 'Amet', 'Consectetur', 'Adipisicing', 'Elit', 'Quisquam'];
?>

<section class="page-head page-head--center">
    <div class="container">
        <h1>Pesquisar</h1>
        <form class="search-box mt-5" action="pesquisar.php" method="get" role="search">
            <svg viewBox="0 0 24 24" aria-hidden="true"><circle cx="11" cy="11" r="7"/><path d="m20 20-3.5-3.5"/></svg>
            <input class="input" type="search" name="q" id="search-input" value="<?= e($q) ?>"
                   placeholder="O que você está procurando?" aria-label="Pesquisar produtos" autocomplete="off">
        </form>
    </div>
</section>

<section class="container pb-20">
    <div class="search-block">
        <p class="search-block__title">Buscas populares</p>
        <div class="chip-row">
            <?php foreach ($popular as $term): ?>
                <button type="button" class="chip js-search-term" data-term="<?= e($term) ?>"><?= e($term) ?></button>
            <?php endforeach; ?>
        </div>
    </div>

    <div class="search-block">
        <p class="search-block__title">Explorar por categoria</p>
        <div class="cat-row">
            <?php foreach ($categories as $slug => $cat): ?>
                <a href="categorias.php?cat=<?= $slug ?>" class="cat-card">
                    <span class="cat-card__img" aria-hidden="true">Imagem</span>
                    <?= e($cat['name']) ?>
                </a>
            <?php endforeach; ?>
        </div>
    </div>

    <div class="search-block">
        <p class="search-block__title">Todos os produtos</p>
        <div class="product-grid" id="search-grid">
            <?php foreach ($products as $p) product_card($p); ?>
        </div>
        <p class="empty-note" id="search-empty" hidden>
            Nenhum produto encontrado. Tente outro termo ou explore as categorias acima.
        </p>
    </div>
</section>

<?php require __DIR__ . '/includes/footer.php'; ?>
