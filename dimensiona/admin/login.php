<?php
require_once __DIR__ . '/includes/bootstrap.php';

if (is_admin()) {
    header('Location: index.php');
    exit;
}

$erro  = '';
$email = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = trim((string) ($_POST['email'] ?? ''));
    $senha = (string) ($_POST['senha'] ?? '');

    // Freio simples contra tentativa e erro
    $_SESSION['tentativas'] = ($_SESSION['tentativas'] ?? 0) + 1;
    if ($_SESSION['tentativas'] > 5) {
        sleep(2);
    }

    if (!csrf_check($_POST['csrf'] ?? null)) {
        $erro = 'A sessão expirou. Recarregue a página e tente de novo.';
    } elseif (admin_login($email, $senha)) {
        session_regenerate_id(true);
        $_SESSION['admin'] = true;
        $_SESSION['tentativas'] = 0;
        header('Location: index.php');
        exit;
    } else {
        $erro = 'E-mail ou senha incorretos.';
    }
}
?>
<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="robots" content="noindex, nofollow">
    <title>Entrar · Painel <?= e(SITE_NAME) ?></title>
    <link href="https://fonts.googleapis.com/css2?family=Outfit:wght@300;400;500;600;700&family=Newsreader:opsz,wght@6..72,400;6..72,500&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="assets/admin.css">
    <link rel="icon" href="../assets/img/logo.svg" type="image/svg+xml">
</head>
<body class="a-login-page">
    <a href="../index.php" class="a-login-back">← Voltar à loja</a>

    <main class="a-login">
        <img src="../assets/img/logo.svg" alt="" width="36" height="36">
        <p class="a-login__eyebrow">Acesso restrito</p>
        <h1>Painel Admin</h1>

        <?php if (!empty(data_read('admin')['senha_padrao'])): ?>
            <!-- Some sozinho depois do primeiro login -->
            <p class="a-login__hint">Primeiro acesso: <strong>admin@dimensiona.com.br</strong> · senha <strong>dimensiona2026</strong></p>
        <?php endif; ?>

        <?php if ($erro): ?>
            <p class="a-alert" role="alert"><?= e($erro) ?></p>
        <?php endif; ?>

        <form method="post" class="js-login-form" novalidate>
            <input type="hidden" name="csrf" value="<?= e(csrf_token()) ?>">
            <div class="a-field">
                <label for="email">E-mail</label>
                <input class="a-input" type="email" id="email" name="email" value="<?= e($email) ?>" placeholder="admin@dimensiona.com.br" autocomplete="username" required>
            </div>
            <div class="a-field">
                <label for="senha">Senha</label>
                <div class="a-pass">
                    <input class="a-input" type="password" id="senha" name="senha" placeholder="••••••••" autocomplete="current-password" required>
                    <button type="button" class="a-icon-btn js-toggle-pass" aria-label="Mostrar senha"><svg viewBox="0 0 24 24" width="18" height="18" fill="none" stroke="currentColor" stroke-width="1.8"><path d="M2 12s3.5-7 10-7 10 7 10 7-3.5 7-10 7S2 12 2 12z"/><circle cx="12" cy="12" r="3"/></svg></button>
                </div>
            </div>
            <button class="a-btn a-btn--grad a-btn--block" type="submit">Entrar no painel</button>
        </form>
    </main>

    <script src="assets/admin.js"></script>
</body>
</html>
