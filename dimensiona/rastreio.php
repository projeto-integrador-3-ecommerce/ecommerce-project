<?php
$pageTitle  = 'Rastreio de pedido';
$activePage = 'rastreio';
require __DIR__ . '/includes/header.php';
?>

<section class="page-head page-head--center">
    <div class="container">
        <span class="eyebrow">Lorem ipsum dolor</span>
        <h1>Rastreio de pedido</h1>
        <p>Lorem ipsum dolor sit amet consectetur adipisicing elit. Quisquam, voluptatum.</p>
    </div>
</section>

<section class="container">
    <form class="track-form" id="track-form" novalidate>
        <div class="field mb-0">
            <label for="track-input">Código do pedido</label>
            <div class="inline-form">
                <input class="input" type="text" id="track-input" placeholder="Ex: DIM-2026-4821" autocomplete="off" aria-describedby="track-error">
                <button class="btn btn--gradient" type="submit">Rastrear</button>
            </div>
            <p class="form-error" id="track-error"></p>
        </div>
        <p class="form-note">
            Experimente:
            <button type="button" class="js-track-example">DIM-2026-4821</button> ou
            <button type="button" class="js-track-example">DIM-2026-3517</button>
        </p>
    </form>

    <div class="track-result" id="track-result" hidden aria-live="polite">
        <div class="track-card">
            <div class="track-card__head">
                <div>
                    <strong id="track-code"></strong>
                    <p class="form-note mt-0.5" id="track-eta"></p>
                </div>
                <span class="status-pill" id="track-status"></span>
            </div>
            <ol class="timeline" id="track-timeline"></ol>
        </div>
    </div>

    <div class="info-grid">
        <div class="info-card">
            <strong>Lorem ipsum</strong>
            <p>Dolor sit amet consectetur adipisicing elit.</p>
        </div>
        <div class="info-card">
            <strong>Dolor sit</strong>
            <p>Quisquam voluptatem nobis eveniet dolorum.</p>
        </div>
        <div class="info-card">
            <strong>Amet consectetur</strong>
            <p>Lorem ipsum: <a href="mailto:contato@dimensiona.com.br" class="text-cyan">contato@dimensiona.com.br</a></p>
        </div>
    </div>
</section>

<?php require __DIR__ . '/includes/footer.php'; ?>
