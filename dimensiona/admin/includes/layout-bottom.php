<?php /* Rodapé do painel: modal, toast e dados iniciais para o JavaScript. */ ?>
    </main>
</div>

<div class="modal" id="modal" hidden>
    <div class="modal__backdrop" data-close></div>
    <div class="modal__box" role="dialog" aria-modal="true" aria-labelledby="modal-title">
        <div class="modal__head">
            <h2 id="modal-title"></h2>
            <button type="button" class="a-icon-btn" data-close aria-label="Fechar">✕</button>
        </div>
        <div class="modal__body" id="modal-body"></div>
    </div>
</div>

<div class="a-toast" id="a-toast" role="status" aria-live="polite"></div>

<script>
    window.ADMIN = {
        api: 'api.php',
        csrf: <?= json_encode(csrf_token()) ?>,
        root: '../',
        data: <?= json_encode(admin_data(), JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES | JSON_HEX_TAG | JSON_HEX_AMP) ?>
    };
</script>
<script src="assets/admin.js"></script>
</body>
</html>
