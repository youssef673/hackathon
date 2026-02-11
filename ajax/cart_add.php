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
$qty = max(1, (int) ($data['quantity'] ?? 1));

$stmt = db()->prepare('SELECT id FROM products WHERE id = ?');
$stmt->execute([$productId]);
if (!$stmt->fetch()) {
    echo json_encode(['ok' => false, 'message' => 'Prodotto non trovato']);
    exit;
}

$_SESSION['cart'] = $_SESSION['cart'] ?? [];
$_SESSION['cart'][$productId] = ($_SESSION['cart'][$productId] ?? 0) + $qty;

echo json_encode(['ok' => true, 'message' => 'Prodotto aggiunto al carrello']);
