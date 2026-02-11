<?php
require_once __DIR__ . '/includes/layout.php';
require_login();
$search = trim($_GET['q'] ?? '');
$params = [];
$sql = 'SELECT p.id, p.name, p.description, p.price, c.code AS currency_code FROM products p JOIN currencies c ON c.id = p.currency_id';
if ($search !== '') {
    $sql .= ' WHERE p.name LIKE ? OR p.description LIKE ?';
    $params = ["%$search%", "%$search%"];
}
$sql .= ' ORDER BY p.created_at DESC';
$stmt = db()->prepare($sql);
$stmt->execute($params);
$products = $stmt->fetchAll();
render_header('Prodotti');
?>
<h2>Catalogo prodotti</h2>
<form method="get" class="inline-form">
  <input type="search" name="q" placeholder="Cerca prodotto" value="<?= e($search) ?>">
  <button class="btn" type="submit">Cerca</button>
</form>
<div class="grid">
  <?php foreach ($products as $product): ?>
    <article class="card">
      <h3><?= e($product['name']) ?></h3>
      <p><?= e($product['description']) ?></p>
      <strong><?= number_format((float) $product['price'], 2, ',', '.') . ' ' . e($product['currency_code']) ?></strong>
      <div class="inline-form">
        <input type="number" value="1" min="1" class="qty" id="qty-<?= (int) $product['id'] ?>">
        <button class="btn add-to-cart" data-product-id="<?= (int) $product['id'] ?>">Aggiungi al carrello</button>
      </div>
    </article>
  <?php endforeach; ?>
</div>
<?php render_footer(); ?>
