<?php
/**
 * Renderiza um card de produto.
 * $opts['showCategory'] exibe a categoria sob o nome.
 * $opts['tag']          exibe a categoria como etiqueta sobre a imagem.
 * $opts['buttonLabel']  texto do botão.
 */
function product_card(array $p, array $opts = []): void
{
    global $categories;
    $catName = $categories[$p['category']]['name'] ?? '';
    $label   = $opts['buttonLabel'] ?? '+ Carrinho';
    ?>
    <article class="product-card" data-category="<?= e($p['category']) ?>" data-name="<?= e(mb_strtolower($p['name'] . ' ' . $catName . ' ' . ($p['desc'] ?? ''))) ?>">
        <div class="product-card__media">
            <?php if (($p['badge'] ?? null) === 'novo'): ?>
                <span class="badge badge--new">Novo</span>
            <?php elseif (($p['badge'] ?? null) === 'oferta'): ?>
                <span class="badge badge--sale">Oferta</span>
            <?php endif; ?>
            <?php if (!empty($opts['tag'])): ?>
                <span class="product-card__tag"><?= e($catName) ?></span>
            <?php endif; ?>
            <img src="<?= e($p['img']) ?>" alt="<?= e($p['name']) ?>" loading="lazy">
        </div>
        <div class="product-card__body">
            <h3 class="product-card__name"><?= e($p['name']) ?></h3>
            <?php if (!empty($opts['showCategory'])): ?>
                <p class="product-card__cat"><?= e($catName) ?></p>
            <?php endif; ?>
            <p class="product-card__price">
                <?= money($p['price']) ?>
                <?php if (!empty($p['old_price'])): ?><s><?= money($p['old_price']) ?></s><?php endif; ?>
            </p>
        </div>
        <button class="btn btn--ghost btn--block js-add-to-cart" type="button"
                data-id="<?= (int) $p['id'] ?>"
                data-name="<?= e($p['name']) ?>"
                data-price="<?= e((string) $p['price']) ?>"
                data-img="<?= e($p['img']) ?>">
            <?= e($label) ?>
        </button>
    </article>
    <?php
}
