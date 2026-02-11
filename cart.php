<?php
require_once __DIR__ . '/includes/layout.php';
require_role('utente');
$cart = $_SESSION['cart'] ?? [];
$items = [];
$total = 0;
if ($cart) {
    $ids = array_keys($cart);
    $in = implode(',', array_fill(0, count($ids), '?'));
    $stmt = db()->prepare("SELECT p.id, p.name, p.price, c.code AS currency_code FROM products p JOIN currencies c ON c.id = p.currency_id WHERE p.id IN ($in)");
    $stmt->execute($ids);
    foreach ($stmt->fetchAll() as $product) {
        $qty = (int) ($cart[$product['id']] ?? 0);
        $line = $qty * (float) $product['price'];
        $total += $line;
        $product['qty'] = $qty;
        $product['line_total'] = $line;
        $items[] = $product;
    }
}
$methods = db()->query('SELECT id, name FROM payment_methods ORDER BY id')->fetchAll();
render_header('Carrello');
?>
<h2>Carrello</h2>
<div id="cart-feedback"></div>
<?php if (!$items): ?>
  <p>Carrello vuoto.</p>
<?php else: ?>
  <table class="table">
    <thead><tr><th>Prodotto</th><th>Qtà</th><th>Prezzo</th><th>Totale</th><th>Azioni</th></tr></thead>
    <tbody>
      <?php foreach ($items as $item): ?>
      <tr id="row-<?= (int) $item['id'] ?>">
        <td><?= e($item['name']) ?></td>
        <td><?= (int) $item['qty'] ?></td>
        <td><?= number_format((float) $item['price'], 2, ',', '.') . ' ' . e($item['currency_code']) ?></td>
        <td><?= number_format((float) $item['line_total'], 2, ',', '.') . ' ' . e($item['currency_code']) ?></td>
        <td><button class="btn danger remove-from-cart" data-product-id="<?= (int) $item['id'] ?>">Elimina</button></td>
      </tr>
      <?php endforeach; ?>
    </tbody>
  </table>
  <p><strong>Totale ordine: <?= number_format($total, 2, ',', '.') ?> EUR</strong></p>
  <form method="post" action="place_order.php" class="card inline-form">
    <input type="hidden" name="csrf_token" value="<?= e(csrf_token()) ?>">
    <label>Metodo pagamento
      <select name="payment_method_id" required>
        <option value="">--Scegli--</option>
        <?php foreach ($methods as $method): ?>
          <option value="<?= (int) $method['id'] ?>"><?= e($method['name']) ?></option>
        <?php endforeach; ?>
      </select>
    </label>
    <button class="btn" type="submit">Checkout</button>
  </form>
<?php endif; ?>
<?php render_footer(); ?>
