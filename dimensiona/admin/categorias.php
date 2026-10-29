<?php
$adminPage  = 'categorias';
$adminTitle = 'Categorias';
require __DIR__ . '/includes/layout-top.php';
?>
<div class="toolbar">
    <p class="toolbar__count" id="category-count"></p>
    <button class="a-btn a-btn--grad" type="button" id="new-category">+ Nova categoria</button>
</div>

<section class="cat-cards" id="category-grid" aria-label="Categorias"></section>

<?php require __DIR__ . '/includes/layout-bottom.php'; ?>
