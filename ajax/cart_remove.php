<?php
require_once __DIR__ . '/../config.php';
header('Content-Type: application/json');
require_role('utente');
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    http_response_code(405);
    echo json_encode(['ok' => false, 'message' => 'Metodo non consentito']);
    exit;
}
$data = json_decode(file_get_contents('php://input'), true);
$productId = (int) ($data['product_id'] ?? 0);
if (isset($_SESSION['cart'][$productId])) {
    unset($_SESSION['cart'][$productId]);
}
echo json_encode(['ok' => true, 'message' => 'Prodotto rimosso dal carrello']);
