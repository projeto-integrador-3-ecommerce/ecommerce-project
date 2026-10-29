<?php
/**
 * Base do painel: carrega a configuração da loja, inicia a sessão
 * e oferece as funções de autenticação e proteção CSRF.
 */
require_once dirname(__DIR__, 2) . '/includes/config.php';

if (session_status() === PHP_SESSION_NONE) {
    session_set_cookie_params([
        'httponly' => true,
        'samesite' => 'Lax',
        'secure'   => !empty($_SERVER['HTTPS']),
    ]);
    session_start();
}

function is_admin(): bool
{
    return !empty($_SESSION['admin']);
}

/** Bloqueia páginas do painel para quem não fez login. */
function require_admin(): void
{
    if (!is_admin()) {
        header('Location: login.php');
        exit;
    }
}

function csrf_token(): string
{
    if (empty($_SESSION['csrf'])) {
        $_SESSION['csrf'] = bin2hex(random_bytes(32));
    }
    return $_SESSION['csrf'];
}

function csrf_check(?string $token): bool
{
    return is_string($token) && hash_equals($_SESSION['csrf'] ?? '', $token);
}

/**
 * Confere as credenciais do admin (data/admin.json).
 * No primeiro login a senha padrão é convertida em hash e removida do arquivo.
 */
function admin_login(string $email, string $senha): bool
{
    $admin = data_read('admin');
    if (!$admin || !hash_equals(mb_strtolower($admin['email']), mb_strtolower(trim($email)))) {
        return false;
    }
    if (!empty($admin['senha_hash'])) {
        return password_verify($senha, $admin['senha_hash']);
    }
    if (!empty($admin['senha_padrao']) && hash_equals($admin['senha_padrao'], $senha)) {
        $admin['senha_hash'] = password_hash($senha, PASSWORD_DEFAULT);
        unset($admin['senha_padrao']);
        data_write('admin', $admin);
        return true;
    }
    return false;
}

/** Todos os dados que o painel precisa, enviados ao JavaScript. */
function admin_data(): array
{
    global $loja;
    return [
        'loja'        => $loja,
        'produtos'    => data_read('produtos'),
        'categorias'  => data_read('categorias'),
        'pedidos'     => data_read('pedidos'),
        'impressoras' => data_read('impressoras'),
        'materiais'   => data_read('materiais'),
        'painel'      => data_read('painel'),
    ];
}
