<?php
require_once __DIR__ . '/../includes/layout.php';
require_role('amministratore');
$pdo = db();
$errors = [];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    verify_csrf();
    $action = $_POST['action'] ?? '';
    $id = (int) ($_POST['id'] ?? 0);
    if ($action === 'delete' && $id) {
        $stmt = $pdo->prepare('DELETE FROM products WHERE id = ?');
        $stmt->execute([$id]);
        redirect('admin/products.php?ok=1');
        exit;
    }

    $name = trim($_POST['name'] ?? '');
    $description = trim($_POST['description'] ?? '');
    $price = (float) ($_POST['price'] ?? 0);
    $currencyId = (int) ($_POST['currency_id'] ?? 0);

    if ($name === '') $errors[] = 'Nome prodotto obbligatorio.';
    if ($price <= 0) $errors[] = 'Prezzo non valido.';

    if (!$errors) {
        if ($action === 'create') {
            $stmt = $pdo->prepare('INSERT INTO products (name, description, price, currency_id, created_by) VALUES (?, ?, ?, ?, ?)');
            $stmt->execute([$name, $description, $price, $currencyId, current_user()['id']]);
        }
        if ($action === 'update' && $id) {
            $stmt = $pdo->prepare('UPDATE products SET name = ?, description = ?, price = ?, currency_id = ? WHERE id = ?');
            $stmt->execute([$name, $description, $price, $currencyId, $id]);
        }
        redirect('admin/products.php?ok=1');
        exit;
    }
}

$currencies = $pdo->query('SELECT id, code FROM currencies ORDER BY code')->fetchAll();
$products = $pdo->query('SELECT p.id, p.name, p.description, p.price, c.code AS currency_code, p.currency_id FROM products p JOIN currencies c ON c.id = p.currency_id ORDER BY p.created_at DESC')->fetchAll();
render_header('Admin - Prodotti');
?>
<h2>Gestione prodotti (CRUD)</h2>
<?php if (isset($_GET['ok'])): ?><p class="alert success">Operazione completata.</p><?php endif; ?>
<?php foreach ($errors as $error): ?><p class="alert error"><?= e($error) ?></p><?php endforeach; ?>
<section class="card">
  <h3>Inserisci nuovo prodotto</h3>
  <form method="post" class="form-grid">
    <input type="hidden" name="csrf_token" value="<?= e(csrf_token()) ?>">
    <input type="hidden" name="action" value="create">
    <label>Nome<input type="text" name="name" required></label>
    <label>Descrizione<textarea name="description" rows="3"></textarea></label>
    <label>Prezzo<input type="number" name="price" min="0.01" step="0.01" required></label>
    <label>Valuta
      <select name="currency_id" required>
      <?php foreach ($currencies as $currency): ?>
        <option value="<?= (int) $currency['id'] ?>"><?= e($currency['code']) ?></option>
      <?php endforeach; ?>
      </select>
    </label>
    <button class="btn" type="submit">Salva</button>
  </form>
</section>

<h3>Elenco prodotti</h3>
<?php foreach ($products as $product): ?>
  <form method="post" class="card form-grid compact">
    <input type="hidden" name="csrf_token" value="<?= e(csrf_token()) ?>">
    <input type="hidden" name="id" value="<?= (int) $product['id'] ?>">
    <input type="hidden" name="action" value="update">
    <label>Nome<input type="text" name="name" value="<?= e($product['name']) ?>"></label>
    <label>Descrizione<textarea name="description" rows="2"><?= e($product['description']) ?></textarea></label>
    <label>Prezzo<input type="number" name="price" min="0.01" step="0.01" value="<?= e((string) $product['price']) ?>"></label>
    <label>Valuta
      <select name="currency_id">
      <?php foreach ($currencies as $currency): ?>
        <option value="<?= (int) $currency['id'] ?>" <?= (int) $currency['id'] === (int) $product['currency_id'] ? 'selected' : '' ?>><?= e($currency['code']) ?></option>
      <?php endforeach; ?>
      </select>
    </label>
    <div class="inline-form">
      <button class="btn" type="submit">Aggiorna</button>
  </form>
      <form method="post" onsubmit="return confirm('Eliminare prodotto?')">
        <input type="hidden" name="csrf_token" value="<?= e(csrf_token()) ?>">
        <input type="hidden" name="id" value="<?= (int) $product['id'] ?>">
        <input type="hidden" name="action" value="delete">
        <button class="btn danger" type="submit">Elimina</button>
      </form>
    </div>
<?php endforeach; ?>
<?php render_footer(); ?>
