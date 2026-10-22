<?php
$pageTitle  = 'Entrar ou cadastrar';
$activePage = 'login';
require __DIR__ . '/includes/header.php';
?>

<section class="container">
    <div class="auth">
        <div class="auth__head">
            <img src="assets/img/logo.svg" alt="" width="32" height="32">
            <h1>Lorem ipsum dolor</h1>
            <p>Lorem ipsum dolor sit amet consectetur.</p>
        </div>

        <div class="tabs" role="tablist" aria-label="Acesso à conta">
            <button type="button" role="tab" id="tab-login" aria-controls="panel-login" aria-selected="true">Entrar</button>
            <button type="button" role="tab" id="tab-signup" aria-controls="panel-signup" aria-selected="false" tabindex="-1">Cadastrar</button>
        </div>

        <!-- Entrar -->
        <form class="js-auth-form" id="panel-login" role="tabpanel" aria-labelledby="tab-login"
              data-success="Login realizado. Redirecionando para sua conta…" novalidate>
            <div class="field">
                <label for="login-email">E-mail</label>
                <input class="input" type="email" id="login-email" placeholder="seu@email.com" autocomplete="email" required>
                <span class="form-error"></span>
            </div>
            <div class="field">
                <label for="login-pass">Senha</label>
                <input class="input" type="password" id="login-pass" placeholder="••••••••" autocomplete="current-password" required>
                <span class="form-error"></span>
            </div>
            <a href="#" class="forgot">Esqueci minha senha</a>
            <button class="btn btn--gradient btn--block" type="submit">Entrar</button>
        </form>

        <!-- Cadastrar -->
        <form class="js-auth-form" id="panel-signup" role="tabpanel" aria-labelledby="tab-signup"
              data-success="Conta criada. Enviamos um e-mail de confirmação." novalidate hidden>
            <div class="field">
                <label for="signup-name">Nome completo</label>
                <input class="input" type="text" id="signup-name" placeholder="Seu nome" autocomplete="name" required>
                <span class="form-error"></span>
            </div>
            <div class="field">
                <label for="signup-email">E-mail</label>
                <input class="input" type="email" id="signup-email" placeholder="seu@email.com" autocomplete="email" required>
                <span class="form-error"></span>
            </div>
            <div class="field">
                <label for="signup-pass">Senha</label>
                <input class="input" type="password" id="signup-pass" placeholder="Mínimo de 8 caracteres" minlength="8" autocomplete="new-password" required>
                <span class="form-error"></span>
            </div>
            <div class="field">
                <label for="signup-pass2">Confirmar senha</label>
                <input class="input" type="password" id="signup-pass2" placeholder="Repita a senha" data-match="signup-pass" autocomplete="new-password" required>
                <span class="form-error"></span>
            </div>
            <button class="btn btn--gradient btn--block" type="submit">Criar conta</button>
        </form>

        <div class="perks">
            <p class="perks__title">Lorem ipsum</p>
            <div class="perks__grid">
                <div>Dolor sit amet</div>
                <div>Consectetur</div>
                <div>Adipisicing elit</div>
                <div>Quisquam nobis</div>
            </div>
        </div>
    </div>
</section>

<?php require __DIR__ . '/includes/footer.php'; ?>
