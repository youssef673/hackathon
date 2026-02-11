<?php
require_once __DIR__ . '/config.php';
require_role('utente');
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    redirect('cart.php');
    exit;
}
verify_csrf();
$methodId = (int) ($_POST['payment_method_id'] ?? 0);
$cart = $_SESSION['cart'] ?? [];
if (!$cart || !$methodId) {
    redirect('cart.php');
    exit;
}

$pdo = db();
$pdo->beginTransaction();
try {
    $ids = array_keys($cart);
    $in = implode(',', array_fill(0, count($ids), '?'));
    $stmt = $pdo->prepare("SELECT id, price FROM products WHERE id IN ($in)");
    $stmt->execute($ids);
    $products = $stmt->fetchAll();
    $priceMap = [];
    $total = 0;
    foreach ($products as $p) {
        $priceMap[$p['id']] = (float) $p['price'];
        $total += ((int) $cart[$p['id']]) * (float) $p['price'];
    }

    $orderStmt = $pdo->prepare('INSERT INTO orders (user_id, payment_method_id, total_amount) VALUES (?, ?, ?)');
    $orderStmt->execute([current_user()['id'], $methodId, $total]);
    $orderId = (int) $pdo->lastInsertId();

    $itemStmt = $pdo->prepare('INSERT INTO order_items (order_id, product_id, quantity, unit_price) VALUES (?, ?, ?, ?)');
    foreach ($cart as $productId => $qty) {
        if (!isset($priceMap[$productId])) continue;
        $itemStmt->execute([$orderId, (int) $productId, (int) $qty, $priceMap[$productId]]);
    }

    $pdo->commit();
    $_SESSION['cart'] = [];
    redirect('dashboard_user.php?ordered=1');
} catch (Throwable $e) {
    $pdo->rollBack();
    http_response_code(500);
    echo 'Errore durante checkout.';
}
