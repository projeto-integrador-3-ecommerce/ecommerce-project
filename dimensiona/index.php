<?php
$pageTitle     = 'Início';
$activePage    = 'inicio';
$footerVariant = 'full';
require __DIR__ . '/includes/header.php';

$testimonials = [
    ['stars' => 5, 'text' => 'Lorem ipsum dolor sit amet consectetur adipisicing elit. Quisquam, voluptatum!', 'name' => 'Lorem Ipsum'],
    ['stars' => 5, 'text' => 'Lorem ipsum dolor sit amet consectetur adipisicing elit. Eveniet, dolorum facilis.', 'name' => 'Dolor Sit'],
    ['stars' => 4, 'text' => 'Lorem ipsum dolor sit amet consectetur. Doloribus nemo.', 'name' => 'Amet Consectetur'],
];
?>

<!-- Hero -->
<section class="hero">
    <div class="container hero__grid">
        <div>
            <span class="pill-new">Lorem ipsum 2026</span>
            <h1 class="hero__title">Lorem ipsum dolor sit amet</h1>
            <p class="hero__text">Lorem ipsum dolor sit amet consectetur adipisicing elit. Quisquam, voluptatum eveniet dolorum facilis doloribus nemo.</p>
            <div class="btn-row">
                <a href="categorias.php" class="btn btn--primary">Ver catálogo</a>
                <a href="sobre.php#como-funciona" class="btn btn--outline">Como funciona</a>
            </div>
            <div class="hero__pills">
                <span>Lorem ipsum</span>
                <span>Dolor sit amet</span>
                <span>Consectetur</span>
            </div>
        </div>
        <div class="hero__media">
            <img src="assets/img/placeholder-wide.svg" alt="Imagem ilustrativa">
        </div>
    </div>
</section>

<!-- Diferenciais -->
<section class="features" aria-label="Diferenciais">
    <div class="feature">
        <strong>Lorem ipsum</strong>
        <span>Dolor sit amet consectetur</span>
    </div>
    <div class="feature">
        <strong>Adipisicing elit</strong>
        <span>Quisquam voluptatem nobis</span>
    </div>
    <div class="feature">
        <strong>Eveniet dolorum</strong>
        <span>Facilis doloribus nemo</span>
    </div>
    <div class="feature">
        <strong>Repellendus</strong>
        <span>Officiis tempora recusandae</span>
    </div>
</section>

<!-- Categorias -->
<section class="section">
    <div class="container">
        <div class="section__head">
            <h2 class="section__title">Lorem ipsum</h2>
            <a href="categorias.php" class="section__link">Ver todas</a>
        </div>
        <div class="cat-grid">
            <?php foreach ($categories as $slug => $cat): ?>
                <a href="categorias.php?cat=<?= $slug ?>" class="cat-card">
                    <span class="cat-card__img" aria-hidden="true">Imagem</span>
                    <?= e($cat['name']) ?>
                </a>
            <?php endforeach; ?>
        </div>
    </div>
</section>

<!-- Destaques -->
<section class="section section--alt">
    <div class="container">
        <div class="section__head">
            <h2 class="section__title">Dolor sit amet</h2>
            <a href="categorias.php" class="section__link">Ver todos</a>
        </div>
        <div class="product-grid">
            <?php foreach ($products as $p) product_card($p); ?>
        </div>
    </div>
</section>

<!-- Banner FDM -->
<section class="section">
    <div class="container split">
        <div class="split__media">
            <img src="assets/img/placeholder-wide.svg" alt="Imagem ilustrativa">
        </div>
        <div>
            <span class="eyebrow">Lorem ipsum</span>
            <h2>Consectetur adipisicing elit sed</h2>
            <p>Lorem ipsum dolor sit amet consectetur adipisicing elit. Repellendus officiis tempora recusandae accusantium quae nesciunt.</p>
            <a href="sobre.php#tecnologias" class="btn btn--primary">Saiba mais</a>
        </div>
    </div>
</section>

<!-- Depoimentos -->
<section class="section section--alt">
    <div class="container">
        <h2 class="section__title center mb-8">Quisquam voluptatem nobis</h2>
        <div class="testimonials">
            <?php foreach ($testimonials as $t): ?>
                <figure class="testimonial">
                    <div class="stars" aria-label="<?= $t['stars'] ?> de 5 estrelas">
                        <?= str_repeat('★', $t['stars']) ?><span class="off"><?= str_repeat('★', 5 - $t['stars']) ?></span>
                    </div>
                    <p>“<?= e($t['text']) ?>”</p>
                    <figcaption><strong><?= e($t['name']) ?></strong></figcaption>
                </figure>
            <?php endforeach; ?>
        </div>
    </div>
</section>

<!-- Newsletter -->
<section class="section section--deep newsletter">
    <div class="container">
        <img src="assets/img/logo.svg" alt="" width="28" height="28">
        <h2>Lorem ipsum dolor sit</h2>
        <p>Lorem ipsum dolor sit amet consectetur adipisicing elit. Quisquam, voluptatum eveniet.</p>
        <form class="inline-form" id="newsletter-form" novalidate>
            <input class="input" type="email" id="newsletter-email" aria-label="Seu e-mail" placeholder="seu@email.com" required>
            <button class="btn btn--primary" type="submit">Assinar</button>
        </form>
        <p class="form-note">Lorem ipsum dolor sit amet.</p>
    </div>
</section>

<?php require __DIR__ . '/includes/footer.php'; ?>
