<?php
$pageTitle  = 'Carrinho';
$activePage = 'carrinho';
require __DIR__ . '/includes/header.php';
?>

<section class="page-head">
    <div class="container">
        <h1>Carrinho</h1>
    </div>
</section>

<section class="container" id="cart-root">
    <!-- Estado vazio -->
    <div class="empty-state" id="cart-empty">
        <div class="empty-state__icon" aria-hidden="true">
            <svg viewBox="0 0 24 24"><path d="M6 7h12l-1 13H7L6 7z"/><path d="M9 7a3 3 0 0 1 6 0"/></svg>
        </div>
        <h2>Seu carrinho está vazio</h2>
        <p>Explore nosso catálogo e adicione produtos.</p>
        <a href="categorias.php" class="btn btn--gradient">Explorar catálogo</a>
    </div>

    <!-- Carrinho com itens (preenchido via JS) -->
    <div class="cart-layout" id="cart-filled" hidden>
        <div id="cart-list" aria-live="polite"></div>

        <aside class="summary" aria-label="Resumo do pedido">
            <h2>Resumo</h2>
            <div class="summary__row"><span>Subtotal</span><span id="sum-subtotal">R$ 0,00</span></div>
            <div class="summary__row" id="sum-discount-row" hidden><span>Cupom <?= e(COUPON_CODE) ?></span><span id="sum-discount"></span></div>
            <div class="summary__row"><span>Frete</span><span id="sum-shipping"></span></div>
            <p class="summary__ship" id="sum-ship-msg"></p>
            <div class="progress" aria-hidden="true"><span id="sum-progress" style="width:0"></span></div>

            <form class="inline-form" id="coupon-form" novalidate>
                <input class="input" type="text" id="coupon-input" placeholder="Cupom de desconto" aria-label="Cupom de desconto" aria-describedby="coupon-error">
                <button class="btn btn--outline" type="submit">Aplicar</button>
            </form>
            <p class="form-error" id="coupon-error"></p>

            <div class="summary__row summary__row--total"><span>Total</span><span id="sum-total"></span></div>
            <button class="btn btn--gradient btn--block mt-4" type="button" id="checkout-btn">Finalizar compra</button>
            <a href="categorias.php" class="btn btn--outline btn--block mt-2.5">Continuar comprando</a>
        </aside>
    </div>
</section>

<?php require __DIR__ . '/includes/footer.php'; ?>
