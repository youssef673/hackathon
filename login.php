<?php
require_once __DIR__ . '/includes/layout.php';
$errors = [];
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    verify_csrf();
    $email = trim($_POST['email'] ?? '');
    $password = $_POST['password'] ?? '';

    $stmt = db()->prepare('SELECT u.id, u.name, u.email, u.password_hash, r.name AS role_name FROM users u JOIN roles r ON r.id = u.role_id WHERE u.email = ?');
    $stmt->execute([$email]);
    $user = $stmt->fetch();

    if (!$user || !password_verify($password, $user['password_hash'])) {
        $errors[] = 'Credenziali non valide.';
    } else {
        unset($user['password_hash']);
        $_SESSION['user'] = $user;
        $_SESSION['cart'] = $_SESSION['cart'] ?? [];
        if ($user['role_name'] === 'amministratore') {
            redirect('dashboard_admin.php');
        } else {
            redirect('dashboard_user.php');
        }
        exit;
    }
}
render_header('Login');
?>
<h2>Login</h2>
<?php if (isset($_GET['registered'])): ?><p class="alert success">Registrazione completata.</p><?php endif; ?>
<?php foreach ($errors as $error): ?><p class="alert error"><?= e($error) ?></p><?php endforeach; ?>
<form method="post" class="card form-grid">
  <input type="hidden" name="csrf_token" value="<?= e(csrf_token()) ?>">
  <label>Email<input type="email" name="email" required></label>
  <label>Password<input type="password" name="password" required></label>
  <button class="btn" type="submit">Accedi</button>
</form>
<?php render_footer(); ?>
