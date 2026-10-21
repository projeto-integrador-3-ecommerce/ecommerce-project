<?php
/**
 * Topo do painel: <head>, menu lateral e cabeçalho da página.
 * Defina $adminPage e $adminTitle antes de incluir.
 */
require_once __DIR__ . '/bootstrap.php';
require_admin();

$adminNav = [
    'visao-geral'   => ['index.php',         'Visão Geral',   'visao-geral'],
    'pedidos'       => ['pedidos.php',       'Pedidos',       'pedidos'],
    'produtos'      => ['produtos.php',      'Produtos',      'produtos'],
    'categorias'    => ['categorias.php',    'Categorias',    'categorias'],
    'impressoras'   => ['impressoras.php',   'Impressoras',   'impressoras'],
    'estoque'       => ['estoque.php',       'Estoque',       'estoque'],
    'clientes'      => ['clientes.php',      'Clientes',      'clientes'],
    'configuracoes' => ['configuracoes.php', 'Configurações', 'configuracoes'],
];
/** Ícones de linha do menu do painel. */
function admin_icon(string $name): string
{
    $icons = [
        'visao-geral' => '<svg viewBox="0 0 24 24"><path d="M4 20V10M10 20V4M16 20v-7M22 20H2"/></svg>',
        'pedidos' => '<svg viewBox="0 0 24 24"><path d="M4 7.5 12 3l8 4.5v9L12 21l-8-4.5z"/><path d="M4 7.5 12 12l8-4.5M12 12v9"/></svg>',
        'produtos' => '<svg viewBox="0 0 24 24"><rect x="4" y="4" width="16" height="16" rx="2"/><path d="M4 15l4-4 4 4 3-3 5 5"/></svg>',
        'categorias' => '<svg viewBox="0 0 24 24"><rect x="4" y="4" width="7" height="7" rx="1"/><rect x="13" y="4" width="7" height="7" rx="1"/><rect x="4" y="13" width="7" height="7" rx="1"/><rect x="13" y="13" width="7" height="7" rx="1"/></svg>',
        'impressoras' => '<svg viewBox="0 0 24 24"><path d="M7 8V3h10v5"/><rect x="3" y="8" width="18" height="9" rx="2"/><path d="M7 14h10v7H7z"/></svg>',
        'estoque' => '<svg viewBox="0 0 24 24"><path d="M3 7h18v13H3zM3 7l2-4h14l2 4M9 11h6"/></svg>',
        'clientes' => '<svg viewBox="0 0 24 24"><circle cx="9" cy="8" r="3.5"/><path d="M2.5 20c1-3.5 3.5-5.5 6.5-5.5s5.5 2 6.5 5.5"/><path d="M16 4.5a3.5 3.5 0 0 1 0 7M18 14.5c2 .6 3.2 2.5 3.5 5.5"/></svg>',
        'configuracoes' => '<svg viewBox="0 0 24 24"><circle cx="12" cy="12" r="3"/><path d="M12 2v3M12 19v3M4.2 4.2l2.1 2.1M17.7 17.7l2.1 2.1M2 12h3M19 12h3M4.2 19.8l2.1-2.1M17.7 6.3l2.1-2.1"/></svg>',
    ];
    return $icons[$name] ?? '';
}
?>
<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="robots" content="noindex, nofollow">
    <title><?= e($adminTitle) ?> · Painel <?= e(SITE_NAME) ?></title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Outfit:wght@300;400;500;600;700&family=Newsreader:opsz,wght@6..72,400;6..72,500&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="assets/admin.css">
    <link rel="icon" href="../assets/img/logo.svg" type="image/svg+xml">
</head>
<body data-page="<?= e($adminPage) ?>">

<aside class="a-side" id="a-side" aria-label="Menu do painel">
    <div class="a-side__head">
        <img src="../assets/img/logo.svg" alt="" width="20" height="20">
        <div>
            <strong><?= e(SITE_NAME) ?></strong>
            <span>Painel admin</span>
        </div>
    </div>
    <nav>
        <ul>
            <?php foreach ($adminNav as $key => [$href, $label, $icon]): $on = $adminPage === $key; ?>
                <li>
                    <a href="<?= $href ?>" class="<?= $on ? 'is-active' : '' ?>" <?= $on ? 'aria-current="page"' : '' ?>>
                        <span class="a-nav-icon" aria-hidden="true"><?= admin_icon($icon) ?></span> <?= e($label) ?>
                    </a>
                </li>
            <?php endforeach; ?>
        </ul>
    </nav>
    <div class="a-side__foot">
        <a href="../index.php">← Voltar à loja</a>
        <a href="logout.php">Sair</a>
    </div>
</aside>
<div class="a-overlay js-side-close" aria-hidden="true"></div>

<div class="a-main">
    <header class="a-head">
        <button class="a-menu js-side-open" type="button" aria-label="Abrir menu" aria-controls="a-side">☰</button>
        <div>
            <h1><?= e($adminTitle) ?></h1>
            <p class="a-head__meta" id="a-updated">Atualizado agora</p>
        </div>
        <div class="a-head__right">
            <span class="a-status"><span class="dot"></span> Sistema OK</span>
            <span class="a-avatar" title="Administrador">AD</span>
        </div>
    </header>

    <main class="a-content" id="conteudo">
