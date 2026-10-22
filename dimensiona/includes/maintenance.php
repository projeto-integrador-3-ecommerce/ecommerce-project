<?php /* Página exibida quando a loja está em manutenção (Admin > Configurações). */ ?>
<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Voltamos em breve · <?= e(SITE_NAME) ?></title>
    <link href="https://fonts.googleapis.com/css2?family=Outfit:wght@400;500&family=Newsreader:opsz,wght@6..72,400&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="assets/css/style.css">
</head>
<body>
    <main class="empty-state min-h-screen grid place-content-center p-6">
        <img src="assets/img/logo.svg" alt="" width="40" height="40" class="mx-auto mb-4.5">
        <h1 class="serif text-[34px] mb-2.5">Estamos em manutenção</h1>
        <p>A loja volta em breve. Pedidos já feitos continuam sendo produzidos normalmente.</p>
        <p class="mt-2">Dúvidas: <a href="mailto:<?= e($loja['email']) ?>" class="text-cyan"><?= e($loja['email']) ?></a></p>
    </main>
</body>
</html>
