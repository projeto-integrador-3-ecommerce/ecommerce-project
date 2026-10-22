<?php
/**
 * API do painel. Todas as ações exigem login e token CSRF.
 *   POST api.php?action=produto.salvar   (JSON no corpo)
 *   GET  api.php?action=exportar
 * Resposta: { ok: true, data: ... } ou { ok: false, erro: "mensagem" }
 */
require_once __DIR__ . '/includes/bootstrap.php';

header('Content-Type: application/json; charset=utf-8');
header('Cache-Control: no-store');

function reply($data = null, int $code = 200): void
{
    http_response_code($code);
    echo json_encode(['ok' => $code < 400, 'data' => $data], JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES);
    exit;
}

function fail(string $msg, int $code = 422): void
{
    http_response_code($code);
    echo json_encode(['ok' => false, 'erro' => $msg], JSON_UNESCAPED_UNICODE);
    exit;
}

function slugify(string $text): string
{
    $text = iconv('UTF-8', 'ASCII//TRANSLIT//IGNORE', $text) ?: $text;
    $text = strtolower(trim(preg_replace('/[^A-Za-z0-9]+/', '-', $text), '-'));
    return $text ?: 'categoria';
}

function str(array $in, string $key, int $max = 200): string
{
    return mb_substr(trim((string) ($in[$key] ?? '')), 0, $max);
}

function num($value): ?float
{
    if ($value === null || $value === '') {
        return null;
    }
    return is_numeric($value) ? round((float) $value, 2) : null;
}

if (!is_admin()) {
    fail('Sessão expirada. Faça login de novo.', 401);
}

$action = (string) ($_GET['action'] ?? '');

/* ---------- Exportar (GET) ---------- */
if ($action === 'exportar') {
    header('Content-Disposition: attachment; filename="dimensiona-dados-' . date('Y-m-d') . '.json"');
    $all = admin_data();
    unset($all['painel']);
    echo json_encode($all, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES);
    exit;
}

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    fail('Método não permitido.', 405);
}
if (!csrf_check($_SERVER['HTTP_X_CSRF_TOKEN'] ?? null)) {
    fail('Token de segurança inválido. Recarregue a página.', 403);
}

/* ---------- Upload de imagem (multipart) ---------- */
if ($action === 'upload') {
    $file = $_FILES['imagem'] ?? null;
    if (!$file || $file['error'] !== UPLOAD_ERR_OK) {
        fail('Não foi possível receber a imagem.');
    }
    if ($file['size'] > 3 * 1024 * 1024) {
        fail('A imagem precisa ter até 3 MB.');
    }
    $types = ['image/jpeg' => 'jpg', 'image/png' => 'png', 'image/webp' => 'webp'];
    $mime  = (new finfo(FILEINFO_MIME_TYPE))->file($file['tmp_name']);
    if (!isset($types[$mime])) {
        fail('Use uma imagem JPG, PNG ou WEBP.');
    }
    $name = 'produto-' . date('YmdHis') . '-' . bin2hex(random_bytes(3)) . '.' . $types[$mime];
    $dir  = ROOT_DIR . '/assets/img/produtos';
    if (!is_dir($dir)) {
        mkdir($dir, 0755, true);
    }
    if (!move_uploaded_file($file['tmp_name'], "$dir/$name")) {
        fail('Não foi possível salvar a imagem no servidor.', 500);
    }
    reply(['img' => "assets/img/produtos/$name"]);
}

$in = json_decode((string) file_get_contents('php://input'), true) ?: [];

switch ($action) {

    /* ---------- Produtos ---------- */
    case 'produto.salvar':
        $produtos   = data_read('produtos');
        $categorias = array_column(data_read('categorias'), 'slug');

        $nome  = str($in, 'name', 120);
        $preco = num($in['price'] ?? null);
        $antigo = num($in['old_price'] ?? null);
        $cat   = str($in, 'category', 60);
        $img   = str($in, 'img', 255);

        if ($nome === '')                        fail('Informe o nome do produto.');
        if ($preco === null || $preco <= 0)      fail('Informe um preço maior que zero.');
        if ($antigo !== null && $antigo <= $preco) fail('O preço antigo precisa ser maior que o preço atual.');
        if (!in_array($cat, $categorias, true))  fail('Escolha uma categoria válida.');
        if (!preg_match('#^assets/img/[\w\-/]+\.(svg|png|jpe?g|webp)$#i', $img)) fail('Escolha uma imagem.');

        $badge = in_array($in['badge'] ?? null, ['novo', 'oferta'], true) ? $in['badge'] : null;
        $dados = [
            'name'      => $nome,
            'category'  => $cat,
            'price'     => $preco,
            'old_price' => $antigo,
            'badge'     => $badge,
            'img'       => $img,
            'desc'      => str($in, 'desc', 400),
            'status'    => ($in['status'] ?? 'ativo') === 'inativo' ? 'inativo' : 'ativo',
        ];

        $id = (int) ($in['id'] ?? 0);
        if ($id) {
            $found = false;
            foreach ($produtos as &$p) {
                if ((int) $p['id'] === $id) {
                    $p = array_merge($p, $dados);
                    $saved = $p;
                    $found = true;
                }
            }
            unset($p);
            if (!$found) fail('Produto não encontrado.', 404);
        } else {
            $ids   = array_column($produtos, 'id');
            $saved = ['id' => $ids ? max($ids) + 1 : 1] + $dados + [
                'rating' => 5.0, 'reviews' => 0,
                'launch' => ['Jan', 'Fev', 'Mar', 'Abr', 'Mai', 'Jun', 'Jul', 'Ago', 'Set', 'Out', 'Nov', 'Dez'][date('n') - 1] . ' ' . date('Y'),
            ];
            $produtos[] = $saved;
        }
        data_write('produtos', $produtos) || fail('Não foi possível gravar os dados. Verifique a permissão da pasta data/.', 500);
        reply($saved);

    case 'produto.excluir':
        $id = (int) ($in['id'] ?? 0);
        $produtos = array_values(array_filter(data_read('produtos'), fn($p) => (int) $p['id'] !== $id));
        data_write('produtos', $produtos) || fail('Não foi possível gravar os dados.', 500);
        reply(['id' => $id]);

    /* ---------- Categorias ---------- */
    case 'categoria.salvar':
        $categorias = data_read('categorias');
        $nome = str($in, 'name', 60);
        if ($nome === '') fail('Informe o nome da categoria.');

        $dados = [
            'name' => $nome,
            'desc' => str($in, 'desc', 160),
        ];

        $slug = str($in, 'slug', 60);
        if ($slug) {
            $found = false;
            foreach ($categorias as &$c) {
                if ($c['slug'] === $slug) {
                    $c = array_merge($c, $dados);
                    $saved = $c;
                    $found = true;
                }
            }
            unset($c);
            if (!$found) fail('Categoria não encontrada.', 404);
        } else {
            $base = slugify($nome);
            $slug = $base;
            $existing = array_column($categorias, 'slug');
            for ($i = 2; in_array($slug, $existing, true); $i++) {
                $slug = "$base-$i";
            }
            $saved = ['slug' => $slug] + $dados;
            $categorias[] = $saved;
        }
        data_write('categorias', $categorias) || fail('Não foi possível gravar os dados.', 500);
        reply($saved);

    case 'categoria.excluir':
        $slug = str($in, 'slug', 60);
        $emUso = array_filter(data_read('produtos'), fn($p) => $p['category'] === $slug);
        if ($emUso) {
            fail('Esta categoria tem ' . count($emUso) . ' produto(s). Mova ou exclua os produtos antes.');
        }
        $categorias = array_values(array_filter(data_read('categorias'), fn($c) => $c['slug'] !== $slug));
        data_write('categorias', $categorias) || fail('Não foi possível gravar os dados.', 500);
        reply(['slug' => $slug]);

    /* ---------- Pedidos ---------- */
    case 'pedido.status':
        $validos = ['confirmado', 'imprimindo', 'enviado', 'entregue', 'cancelado'];
        $status  = str($in, 'status', 20);
        if (!in_array($status, $validos, true)) fail('Status inválido.');
        $pedidos = data_read('pedidos');
        $saved = null;
        foreach ($pedidos as &$p) {
            if ($p['codigo'] === str($in, 'codigo', 30)) {
                $p['status'] = $status;
                $saved = $p;
            }
        }
        unset($p);
        if (!$saved) fail('Pedido não encontrado.', 404);
        data_write('pedidos', $pedidos) || fail('Não foi possível gravar os dados.', 500);
        reply($saved);

    /* ---------- Impressoras ---------- */
    case 'impressora.acao':
        $acoes = ['pausar' => 'pausada', 'retomar' => 'imprimindo', 'manutencao' => 'manutencao', 'liberar' => 'ociosa'];
        $acao  = str($in, 'acao', 20);
        if (!isset($acoes[$acao])) fail('Ação inválida.');
        $lista = data_read('impressoras');
        $saved = null;
        foreach ($lista as &$imp) {
            if ($imp['id'] === str($in, 'id', 20)) {
                if ($acao === 'retomar' && empty($imp['job'])) fail('Não há impressão para retomar.');
                $imp['status'] = $acoes[$acao];
                if ($acao === 'liberar') {
                    $imp['job'] = null;
                    $imp['progresso'] = 0;
                    $imp['restante'] = null;
                }
                $saved = $imp;
            }
        }
        unset($imp);
        if (!$saved) fail('Impressora não encontrada.', 404);
        data_write('impressoras', $lista) || fail('Não foi possível gravar os dados.', 500);
        reply($saved);

    /* ---------- Estoque ---------- */
    case 'material.salvar':
        $lista = data_read('materiais');
        $id = (int) ($in['id'] ?? 0);
        if ($id) {
            $qtd = num($in['repor'] ?? null);
            if ($qtd === null || $qtd <= 0) fail('Informe a quantidade a repor.');
            $saved = null;
            foreach ($lista as &$m) {
                if ((int) $m['id'] === $id) {
                    $m['kg'] = round($m['kg'] + $qtd, 2);
                    $m['capacidade'] = max($m['capacidade'], $m['kg']);
                    $saved = $m;
                }
            }
            unset($m);
            if (!$saved) fail('Material não encontrado.', 404);
        } else {
            $nome = str($in, 'nome', 80);
            $cap  = num($in['capacidade'] ?? null);
            $kg   = num($in['kg'] ?? null) ?? 0;
            if ($nome === '')              fail('Informe o nome do material.');
            if ($cap === null || $cap <= 0) fail('Informe a capacidade em kg.');
            $ids = array_column($lista, 'id');
            $saved = [
                'id' => $ids ? max($ids) + 1 : 1, 'nome' => $nome,
                'tipo' => in_array($in['tipo'] ?? '', ['FDM', 'SLA'], true) ? $in['tipo'] : 'FDM',
                'kg' => $kg, 'capacidade' => $cap, 'minimo' => round($cap * 0.2, 1),
                'preco_kg' => num($in['preco_kg'] ?? null) ?? 0,
            ];
            $lista[] = $saved;
        }
        data_write('materiais', $lista) || fail('Não foi possível gravar os dados.', 500);
        reply($saved);

    /* ---------- Configurações ---------- */
    case 'loja.salvar':
        $loja = data_read('loja');
        $sec  = str($in, 'section', 20);
        $v    = $in['values'] ?? [];

        if ($sec === 'info') {
            if (str($v, 'nome', 60) === '')                         fail('Informe o nome da loja.');
            if (!filter_var($v['email'] ?? '', FILTER_VALIDATE_EMAIL)) fail('Informe um e-mail válido.');
            $loja['nome']     = str($v, 'nome', 60);
            $loja['cnpj']     = str($v, 'cnpj', 20);
            $loja['email']    = str($v, 'email', 120);
            $loja['telefone'] = str($v, 'telefone', 30);
        } elseif ($sec === 'frete') {
            foreach (['frete_gratis_min', 'frete_padrao', 'prazo_entrega'] as $k) {
                if (num($v[$k] ?? null) === null || num($v[$k]) < 0) fail('Preencha os valores de frete com números.');
            }
            $loja['frete_gratis_min'] = num($v['frete_gratis_min']);
            $loja['frete_padrao']     = num($v['frete_padrao']);
            $loja['prazo_entrega']    = (int) $v['prazo_entrega'];
            $loja['cep_origem']       = str($v, 'cep_origem', 9);
        } elseif ($sec === 'cupom') {
            $codigo = strtoupper(preg_replace('/[^A-Za-z0-9]/', '', (string) ($v['codigo'] ?? '')));
            $desc   = num($v['desconto'] ?? null);
            if ($codigo !== '' && ($desc === null || $desc < 1 || $desc > 90)) fail('O desconto precisa estar entre 1% e 90%.');
            $loja['cupom'] = ['codigo' => $codigo, 'desconto' => $desc ?? 0, 'minimo' => num($v['minimo'] ?? null) ?? 0];
        } elseif ($sec === 'manutencao') {
            $loja['manutencao'] = !empty($v['ativo']);
        } else {
            fail('Seção inválida.');
        }
        data_write('loja', $loja) || fail('Não foi possível gravar os dados.', 500);
        reply($loja);

    default:
        fail('Ação desconhecida.', 404);
}
