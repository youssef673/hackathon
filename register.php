<?php
require_once __DIR__ . '/includes/layout.php';
$errors = [];
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    verify_csrf();
    $name = trim($_POST['name'] ?? '');
    $email = trim($_POST['email'] ?? '');
    $password = $_POST['password'] ?? '';

    if ($name === '') $errors[] = 'Nome obbligatorio.';
    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) $errors[] = 'Email non valida.';
    if (strlen($password) < 8) $errors[] = 'Password minima 8 caratteri.';

    $defaultRole = db()->prepare('SELECT id FROM roles WHERE name = ? LIMIT 1');
    $defaultRole->execute(['utente']);
    $role = $defaultRole->fetch();
    if (!$role) $errors[] = 'Ruolo predefinito non configurato.';

    if (!$errors) {
        $exists = db()->prepare('SELECT id FROM users WHERE email = ?');
        $exists->execute([$email]);
        if ($exists->fetch()) {
            $errors[] = 'Email già registrata.';
        } else {
            $hash = password_hash($password, PASSWORD_DEFAULT);
            $stmt = db()->prepare('INSERT INTO users (name, email, password_hash, role_id) VALUES (?, ?, ?, ?)');
            $stmt->execute([$name, $email, $hash, (int) $role['id']]);
            redirect('login.php?registered=1');
            exit;
        }
    }
}
render_header('Registrazione');
?>
<h2>Registrazione</h2>
<?php foreach ($errors as $error): ?><p class="alert error"><?= e($error) ?></p><?php endforeach; ?>
<form method="post" class="card form-grid">
  <input type="hidden" name="csrf_token" value="<?= e(csrf_token()) ?>">
  <label>Nome<input type="text" name="name" required></label>
  <label>Email<input type="email" name="email" required></label>
  <label>Password<input type="password" name="password" required minlength="8"></label>
  <button class="btn" type="submit">Crea account</button>
</form>
<?php render_footer(); ?>
