<?php
require_once __DIR__ . '/includes/layout.php';
require_role('utente');
$stmt = db()->prepare('SELECT o.id, o.total_amount, o.created_at, pm.name AS payment_method FROM orders o JOIN payment_methods pm ON pm.id = o.payment_method_id WHERE o.user_id = ? ORDER BY o.created_at DESC');
$stmt->execute([current_user()['id']]);
$orders = $stmt->fetchAll();
render_header('Dashboard Utente');
?>
<h2>Ciao <?= e(current_user()['name']) ?>, questa è la tua area</h2>
<?php if (isset($_GET['ordered'])): ?><p class="alert success">Ordine completato con successo.</p><?php endif; ?>
<p><a class="btn" href="products.php">Cerca prodotti</a> <a class="btn outline" href="cart.php">Apri carrello</a></p>
<h3>I tuoi ordini</h3>
<?php if (!$orders): ?>
<p>Nessun ordine effettuato.</p>
<?php else: ?>
<table class="table">
  <thead><tr><th>ID</th><th>Totale</th><th>Pagamento</th><th>Data</th></tr></thead>
  <tbody>
  <?php foreach ($orders as $order): ?>
  <tr>
    <td>#<?= (int) $order['id'] ?></td>
    <td><?= number_format((float) $order['total_amount'], 2, ',', '.') ?> EUR</td>
    <td><?= e($order['payment_method']) ?></td>
    <td><?= e($order['created_at']) ?></td>
  </tr>
  <?php endforeach; ?>
  </tbody>
</table>
<?php endif; ?>
<?php render_footer(); ?>
