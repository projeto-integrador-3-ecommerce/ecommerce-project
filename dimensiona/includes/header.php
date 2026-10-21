<?php
/**
 * Cabeçalho comum: <head>, barra de aviso e menu lateral.
 * Defina $pageTitle e $activePage antes de incluir.
 */
require_once __DIR__ . '/config.php';
require_once __DIR__ . '/product-card.php';

// Modo manutenção (ativado em Admin > Configurações). Admin logado continua vendo a loja.
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
if (!empty($loja['manutencao']) && empty($_SESSION['admin'])) {
    http_response_code(503);
    header('Retry-After: 3600');
    require __DIR__ . '/maintenance.php';
    exit;
}

$pageTitle  = $pageTitle  ?? SITE_NAME;
$activePage = $activePage ?? '';

$navMain = [
    'inicio'     => ['href' => 'index.php',      'label' => 'Início',     'icon' => 'home'],
    'categorias' => ['href' => 'categorias.php', 'label' => 'Categorias', 'icon' => 'grid'],
    'novidades'  => ['href' => 'novidades.php',  'label' => 'Novidades',  'icon' => 'star'],
    'promocoes'  => ['href' => 'promocoes.php',  'label' => 'Promoções',  'icon' => 'tag'],
    'sobre'      => ['href' => 'sobre.php',      'label' => 'Sobre',      'icon' => 'info'],
];
$navAccount = [
    'pesquisar' => ['href' => 'pesquisar.php', 'label' => 'Pesquisar',        'icon' => 'search'],
    'carrinho'  => ['href' => 'carrinho.php',  'label' => 'Carrinho',         'icon' => 'cart'],
    'rastreio'  => ['href' => 'rastreio.php',  'label' => 'Rastrear Pedido',  'icon' => 'truck'],
    'login'     => ['href' => 'login.php',     'label' => 'Login / Cadastro', 'icon' => 'user'],
];

function nav_icon(string $name): string
{
    $icons = [
        'home' => '<svg viewBox="0 0 24 24"><path d="M3 11 12 4l9 7"/><path d="M5 10v10h14V10"/></svg>',
        'grid' => '<svg viewBox="0 0 24 24"><rect x="4" y="4" width="7" height="7" rx="1"/><rect x="13" y="4" width="7" height="7" rx="1"/><rect x="4" y="13" width="7" height="7" rx="1"/><rect x="13" y="13" width="7" height="7" rx="1"/></svg>',
        'star' => '<svg viewBox="0 0 24 24"><path d="m12 4 2.4 5 5.6.6-4.2 3.8 1.2 5.6L12 16.2 7 19l1.2-5.6L4 9.6 9.6 9z"/></svg>',
        'tag' => '<svg viewBox="0 0 24 24"><path d="M3 12V4h8l10 10-8 8z"/><circle cx="7.5" cy="8.5" r="1.3"/></svg>',
        'info' => '<svg viewBox="0 0 24 24"><circle cx="12" cy="12" r="9"/><path d="M12 11v6M12 7.5v.5"/></svg>',
        'search' => '<svg viewBox="0 0 24 24"><circle cx="11" cy="11" r="7"/><path d="m20 20-3.5-3.5"/></svg>',
        'cart'   => '<svg viewBox="0 0 24 24"><path d="M3 4h2l2.4 11h11L21 7H6.2"/><circle cx="9" cy="19.5" r="1.3"/><circle cx="17" cy="19.5" r="1.3"/></svg>',
        'truck'  => '<svg viewBox="0 0 24 24"><path d="M3 6h11v10H3zM14 10h4l3 3v3h-7"/><circle cx="7" cy="17.5" r="1.8"/><circle cx="17.5" cy="17.5" r="1.8"/></svg>',
        'user'   => '<svg viewBox="0 0 24 24"><circle cx="12" cy="8" r="4"/><path d="M4 21c1.5-4 4.5-6 8-6s6.5 2 8 6"/></svg>',
    ];
    return $icons[$name] ?? '';
}
?>
<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title><?= e($pageTitle) ?> · <?= SITE_NAME ?></title>
    <meta name="description" content="Dimensiona — impressão 3D FDM, SLA e MSLA com entrega para todo o Brasil.">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Outfit:wght@300;400;500;600;700&family=Newsreader:opsz,wght@6..72,400;6..72,500&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="assets/css/style.css">
    <link rel="icon" href="assets/img/logo.svg" type="image/svg+xml">
</head>
<body data-free-shipping="<?= FREE_SHIPPING_MIN ?>"
      data-shipping-fee="<?= SHIPPING_FEE ?>"
      data-coupon="<?= e(COUPON_CODE) ?>"
      data-coupon-discount="<?= COUPON_DISCOUNT ?>"
      data-coupon-min="<?= COUPON_MIN ?>"
      data-api="api/">

<a class="skip-link" href="#conteudo">Pular para o conteúdo</a>

<!-- Menu lateral -->
<aside class="sidebar" id="sidebar" aria-label="Menu principal">
    <div class="sidebar__head">
        <a href="index.php" class="logo">
            <img src="assets/img/logo.svg" alt="" width="22" height="22">
            <span><?= SITE_NAME ?></span>
        </a>
        <button class="icon-btn js-sidebar-close" type="button" aria-label="Fechar menu">✕</button>
    </div>

    <nav class="sidebar__nav">
        <p class="sidebar__label">Navegação</p>
        <ul>
            <?php foreach ($navMain as $key => $item): $active = $activePage === $key; ?>
                <li>
                    <a href="<?= $item['href'] ?>" class="<?= $active ? 'is-active' : '' ?>" <?= $active ? 'aria-current="page"' : '' ?>>
                        <span class="sidebar__icon sidebar__icon--line" aria-hidden="true"><?= nav_icon($item['icon']) ?></span>
                        <span class="sidebar__text"><?= e($item['label']) ?></span>
                        <?php if ($active): ?><span class="chev" aria-hidden="true">›</span><?php endif; ?>
                    </a>
                </li>
            <?php endforeach; ?>
        </ul>

        <p class="sidebar__label">Conta</p>
        <ul>
            <?php foreach ($navAccount as $key => $item): $active = $activePage === $key; ?>
                <li>
                    <a href="<?= $item['href'] ?>" class="<?= $active ? 'is-active' : '' ?>" <?= $active ? 'aria-current="page"' : '' ?>>
                        <span class="sidebar__icon sidebar__icon--line" aria-hidden="true"><?= nav_icon($item['icon']) ?></span>
                        <span class="sidebar__text"><?= e($item['label']) ?></span>
                        <?php if ($key === 'carrinho'): ?><span class="cart-count js-cart-count" hidden>0</span><?php endif; ?>
                        <span class="chev" aria-hidden="true">›</span>
                    </a>
                </li>
            <?php endforeach; ?>
        </ul>
    </nav>

    <p class="sidebar__foot">© <?= date('Y') ?> <?= SITE_NAME ?> · CNPJ <?= e($loja['cnpj']) ?></p>
</aside>
<div class="sidebar-overlay js-sidebar-close" aria-hidden="true"></div>

<div class="page">
    <div class="topbar">
        Frete grátis em pedidos acima de <?= money(FREE_SHIPPING_MIN) ?><?php if (COUPON_CODE): ?> · Use o cupom: <strong><?= e(COUPON_CODE) ?></strong><?php endif; ?>
    </div>
    <button class="menu-btn js-sidebar-open" type="button" aria-label="Abrir menu" aria-controls="sidebar" aria-expanded="false">
        <span></span><span></span><span></span>
    </button>

    <main id="conteudo">
