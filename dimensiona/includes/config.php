<?php
/**
 * Dimensiona — configuração compartilhada entre a loja e o painel admin.
 * Os dados ficam em /data/*.json e são editados pelo painel (admin/).
 * Para migrar para banco de dados, basta trocar data_read()/data_write().
 */

define('ROOT_DIR', dirname(__DIR__));
define('DATA_DIR', ROOT_DIR . '/data');

/** Lê um arquivo de dados JSON. */
function data_read(string $name, $default = [])
{
    $file = DATA_DIR . '/' . basename($name) . '.json';
    if (!is_file($file)) {
        return $default;
    }
    $data = json_decode((string) file_get_contents($file), true);
    return is_array($data) ? $data : $default;
}

/** Grava um arquivo de dados JSON com trava de escrita. */
function data_write(string $name, $data): bool
{
    $file = DATA_DIR . '/' . basename($name) . '.json';
    $json = json_encode($data, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES);
    return $json !== false && file_put_contents($file, $json, LOCK_EX) !== false;
}

/* ---------- Configurações da loja (editáveis em Admin > Configurações) ---------- */
$loja = data_read('loja', [
    'nome' => 'Dimensiona', 'email' => 'contato@dimensiona.com.br', 'telefone' => '(11) 9 9999-9999',
    'cnpj' => '00.000.000/0000-00', 'frete_gratis_min' => 299, 'frete_padrao' => 24.9,
    'cupom' => ['codigo' => 'DIMENSIONA10', 'desconto' => 10, 'minimo' => 0], 'manutencao' => false,
]);

define('SITE_NAME', $loja['nome'] ?? 'Dimensiona');
define('FREE_SHIPPING_MIN', (float) ($loja['frete_gratis_min'] ?? 299));
define('SHIPPING_FEE', (float) ($loja['frete_padrao'] ?? 24.9));
define('COUPON_CODE', (string) ($loja['cupom']['codigo'] ?? ''));
define('COUPON_DISCOUNT', (float) ($loja['cupom']['desconto'] ?? 0));
define('COUPON_MIN', (float) ($loja['cupom']['minimo'] ?? 0));

/* ---------- Categorias e produtos (editáveis em Admin > Categorias / Produtos) ---------- */
$categories = [];
foreach (data_read('categorias') as $cat) {
    $categories[$cat['slug']] = $cat;
}

// A loja só exibe produtos ativos
$products = array_values(array_filter(
    data_read('produtos'),
    fn($p) => ($p['status'] ?? 'ativo') === 'ativo'
));

/** Formata valor em reais: 89 -> "R$ 89,00" */
function money(float $value): string
{
    return 'R$ ' . number_format($value, 2, ',', '.');
}

/** Escapa texto para HTML */
function e(?string $value): string
{
    return htmlspecialchars((string) $value, ENT_QUOTES, 'UTF-8');
}

/** Formata número de porcentagem: 10.0 -> "10" / 7.5 -> "7,5" */
function pct(float $value): string
{
    return rtrim(rtrim(number_format($value, 1, ',', ''), '0'), ',');
}
