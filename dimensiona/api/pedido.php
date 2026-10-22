<?php
/**
 * API pública de rastreio: GET api/pedido.php?codigo=DIM-2026-4821
 * Retorna só o status do pedido (sem dados pessoais do cliente).
 * O status é atualizado pelo painel em Admin > Pedidos.
 */
require_once dirname(__DIR__) . '/includes/config.php';

header('Content-Type: application/json; charset=utf-8');
header('Cache-Control: no-store');

$codigo = strtoupper(trim((string) ($_GET['codigo'] ?? '')));
if (!preg_match('/^DIM-\d{4}-\d{3,6}$/', $codigo)) {
    http_response_code(400);
    echo json_encode(['ok' => false, 'erro' => 'formato']);
    exit;
}

foreach (data_read('pedidos') as $pedido) {
    if ($pedido['codigo'] === $codigo) {
        echo json_encode([
            'ok'     => true,
            'codigo' => $pedido['codigo'],
            'data'   => $pedido['data'],
            'status' => $pedido['status'],
        ], JSON_UNESCAPED_UNICODE);
        exit;
    }
}

http_response_code(404);
echo json_encode(['ok' => false, 'erro' => 'nao_encontrado']);
