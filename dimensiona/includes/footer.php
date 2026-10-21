<?php
/**
 * Rodapé comum. Use $footerVariant = 'full' | 'contact' | 'none'.
 */
$footerVariant = $footerVariant ?? 'none';
?>
    </main>

    <?php if ($footerVariant === 'full'): ?>
    <footer class="footer">
        <div class="footer__grid">
            <div>
                <p class="footer__brand"><?= SITE_NAME ?></p>
                <p class="footer__text">Lorem ipsum dolor sit amet consectetur adipisicing elit. Quisquam, voluptatum.</p>
                <p class="footer__social">
                    <a href="#">Instagram</a><a href="#">Facebook</a><a href="#">TikTok</a>
                </p>
            </div>
            <div>
                <p class="footer__title">Institucional</p>
                <ul>
                    <li><a href="sobre.php">Sobre nós</a></li>
                    <li><a href="#">Blog</a></li>
                    <li><a href="#">Trabalhe conosco</a></li>
                    <li><a href="sobre.php#contato">Contato</a></li>
                </ul>
            </div>
            <div>
                <p class="footer__title">Ajuda</p>
                <ul>
                    <li><a href="login.php">Minha conta</a></li>
                    <li><a href="rastreio.php">Acompanhar pedido</a></li>
                    <li><a href="#">Trocas e devoluções</a></li>
                    <li><a href="#">Política de privacidade</a></li>
                    <li><a href="#">Termos de uso</a></li>
                    <li><a href="admin/login.php">Área do lojista</a></li>
                </ul>
            </div>
            <div>
                <p class="footer__title">Contato</p>
                <ul>
                    <li><a href="mailto:<?= e($loja['email']) ?>"><?= e($loja['email']) ?></a></li>
                    <li><a href="tel:<?= e(preg_replace('/\D/', '', $loja['telefone'])) ?>"><?= e($loja['telefone']) ?></a></li>
                    <li>Seg. a Sex. das 9h às 18h</li>
                </ul>
                <p class="footer__pay"><span>Pix</span><span>Visa</span><span>Master</span></p>
            </div>
        </div>
        <div class="footer__bottom">
            <span>© <?= date('Y') ?> <?= SITE_NAME ?>. Todos os direitos reservados.</span>
            <span>CNPJ: <?= e($loja['cnpj']) ?></span>
        </div>
    </footer>
    <?php elseif ($footerVariant === 'contact'): ?>
    <footer class="contact-strip" id="contato">
        <div>
            
            <strong>Email</strong>
            <a href="mailto:<?= e($loja['email']) ?>"><?= e($loja['email']) ?></a>
        </div>
        <div>
            
            <strong>Telefone</strong>
            <a href="tel:<?= e(preg_replace('/\D/', '', $loja['telefone'])) ?>"><?= e($loja['telefone']) ?></a>
        </div>
        <div>
            
            <strong>Horário</strong>
            <span>Seg. - Sex. das 9h às 18h</span>
        </div>
    </footer>
    <?php endif; ?>
</div>

<div class="toast" id="toast" role="status" aria-live="polite"></div>

<script src="assets/js/main.js"></script>
</body>
</html>
