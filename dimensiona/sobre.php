<?php
$pageTitle     = 'Sobre nós';
$activePage    = 'sobre';
$footerVariant = 'contact';
require __DIR__ . '/includes/header.php';

$techs = [
    [
        'name' => 'Lorem', 'sub' => 'Sit amet consectetur',
        'text' => 'Lorem ipsum dolor sit amet consectetur adipisicing elit. Quisquam, voluptatum eveniet dolorum facilis doloribus nemo.',
        'specs' => ['Lorem ipsum dolor', 'Sit amet consectetur', 'Adipisicing elit'],
    ],
    [
        'name' => 'Ipsum', 'sub' => 'Adipisicing elit',
        'text' => 'Lorem ipsum dolor sit amet consectetur adipisicing elit. Repellendus officiis tempora recusandae accusantium quae nesciunt.',
        'specs' => ['Lorem ipsum dolor', 'Sit amet consectetur', 'Adipisicing elit'],
    ],
    [
        'name' => 'Dolor', 'sub' => 'Quisquam nobis',
        'text' => 'Lorem ipsum dolor sit amet consectetur adipisicing elit. Mollitia odit, sapiente vero dolore eligendi.',
        'specs' => ['Lorem ipsum dolor', 'Sit amet consectetur', 'Adipisicing elit'],
    ],
];

$steps = [
    ['Lorem ipsum dolor', 'Lorem ipsum dolor sit amet consectetur adipisicing elit.'],
    ['Sit amet consectetur', 'Quisquam voluptatem nobis eveniet dolorum facilis.'],
    ['Adipisicing elit', 'Doloribus nemo repellendus officiis tempora.'],
    ['Quisquam nobis', 'Recusandae accusantium quae nesciunt mollitia.'],
];

$testimonials = [
    ['stars' => 5, 'text' => 'Lorem ipsum dolor sit amet consectetur adipisicing elit. Quisquam, voluptatum!', 'name' => 'Lorem Ipsum'],
    ['stars' => 5, 'text' => 'Lorem ipsum dolor sit amet consectetur adipisicing elit. Eveniet, dolorum facilis.', 'name' => 'Dolor Sit'],
    ['stars' => 4, 'text' => 'Lorem ipsum dolor sit amet consectetur. Doloribus nemo.', 'name' => 'Amet Consectetur'],
];
?>

<!-- Hero -->
<section class="about-hero">
    <div class="container">
        <img src="assets/img/logo.svg" alt="" width="36" height="36">
        <span class="eyebrow">Lorem ipsum</span>
        <h1>Lorem ipsum dolor sit amet consectetur</h1>
        <p>Lorem ipsum dolor sit amet consectetur adipisicing elit. Quisquam, voluptatum eveniet dolorum facilis doloribus nemo.</p>
        <a href="categorias.php" class="btn btn--primary">Explorar catálogo</a>
    </div>
</section>

<!-- Números -->
<section class="stats" aria-label="Números">
    <div class="stat"><strong>5.000+</strong><span>Lorem ipsum</span></div>
    <div class="stat"><strong>4,9 ★</strong><span>Dolor sit</span></div>
    <div class="stat"><strong>48h</strong><span>Amet consectetur</span></div>
    <div class="stat"><strong>2021</strong><span>Adipisicing elit</span></div>
</section>

<!-- Missão -->
<section class="section">
    <div class="container split">
        <div class="split__media">
            <img src="assets/img/placeholder-wide.svg" alt="Imagem ilustrativa">
        </div>
        <div>
            <span class="eyebrow">Lorem ipsum</span>
            <h2 class="serif">Dolor sit amet consectetur</h2>
            <p>Lorem ipsum dolor sit amet consectetur adipisicing elit. Repellendus officiis tempora recusandae accusantium quae nesciunt.</p>
            <ul class="checklist">
                <li>Lorem ipsum dolor sit amet</li>
                <li>Consectetur adipisicing elit</li>
                <li>Quisquam voluptatem nobis</li>
                <li>Eveniet dolorum facilis</li>
            </ul>
        </div>
    </div>
</section>

<!-- Tecnologias -->
<section class="section section--alt" id="tecnologias">
    <div class="container">
        <div class="center mb-8">
            <span class="eyebrow">Lorem ipsum</span>
            <h2 class="section__title--serif">Lorem ipsum dolor sit</h2>
        </div>
        <div class="tech-grid">
            <?php foreach ($techs as $t): ?>
                <article class="tech-card">
                    <h3><?= e($t['name']) ?></h3>
                    <p class="tech-card__sub"><?= e($t['sub']) ?></p>
                    <p><?= e($t['text']) ?></p>
                    <ul class="checklist">
                        <?php foreach ($t['specs'] as $s): ?><li><?= e($s) ?></li><?php endforeach; ?>
                    </ul>
                </article>
            <?php endforeach; ?>
        </div>
    </div>
</section>

<!-- Como funciona -->
<section class="section" id="como-funciona">
    <div class="container split">
        <div>
            <span class="eyebrow">Lorem ipsum</span>
            <h2 class="serif">Lorem ipsum dolor sit</h2>
            <ol class="steps">
                <?php foreach ($steps as $i => $s): ?>
                    <li>
                        <span class="steps__num"><?= str_pad((string) ($i + 1), 2, '0', STR_PAD_LEFT) ?></span>
                        <div><strong><?= e($s[0]) ?></strong><span><?= e($s[1]) ?></span></div>
                    </li>
                <?php endforeach; ?>
            </ol>
        </div>
        <div class="split__media">
            <img src="assets/img/placeholder-wide.svg" alt="Imagem ilustrativa">
        </div>
    </div>
</section>

<!-- Depoimentos -->
<section class="section section--alt">
    <div class="container">
        <h2 class="section__title--serif center mb-8">Quisquam voluptatem nobis</h2>
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

<!-- CTA -->
<section class="section cta">
    <div class="container">
        <h2>Lorem ipsum dolor sit amet?</h2>
        <p>Lorem ipsum dolor sit amet consectetur adipisicing elit. Quisquam, voluptatum.</p>
        <div class="btn-row">
            <a href="categorias.php" class="btn btn--primary">Ver catálogo</a>
            <a href="#contato" class="btn btn--outline">Falar conosco</a>
        </div>
    </div>
</section>

<?php require __DIR__ . '/includes/footer.php'; ?>
