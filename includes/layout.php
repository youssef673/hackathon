<?php
require_once __DIR__ . '/../config.php';

function render_header(string $title = ''): void
{
    $user = current_user();
    ?>
<!doctype html>
<html lang="it">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title><?= e($title ? $title . ' - ' . APP_NAME : APP_NAME) ?></title>
  <link rel="stylesheet" href="assets/app.css">
</head>
<body>
<header class="site-header">
  <div class="container nav">
    <a class="logo" href="index.php"><?= e(APP_NAME) ?></a>
    <nav>
      <a href="products.php">Prodotti</a>
      <?php if ($user): ?>
        <?php if (($user['role_name'] ?? '') === 'amministratore'): ?>
          <a href="dashboard_admin.php">Dashboard Admin</a>
        <?php else: ?>
          <a href="dashboard_user.php">Area Utente</a>
        <?php endif; ?>
        <a href="cart.php">Carrello</a>
        <a href="logout.php">Logout</a>
      <?php else: ?>
        <a href="register.php">Registrati</a>
        <a href="login.php">Login</a>
      <?php endif; ?>
    </nav>
  </div>
</header>
<main class="container">
<?php
}

function render_footer(): void
{
    ?>
</main>
<footer class="site-footer">
  <div class="container">&copy; <?= date('Y') ?> <?= e(APP_NAME) ?> - Demo e-commerce con ruoli.</div>
</footer>
<script src="assets/app.js"></script>
</body>
</html>
<?php
}
