<?php
require_once __DIR__ . '/includes/layout.php';
render_header('Home');
?>
<section class="hero">
  <h1>Benvenuto su <?= e(APP_NAME) ?></h1>
  <p>Piattaforma e-commerce con ruoli separati (utente e amministratore), carrello in sessione con Ajax e gestione ordini.</p>
  <div class="actions">
    <a class="btn" href="register.php">Registrati</a>
    <a class="btn outline" href="login.php">Accedi</a>
    <a class="btn outline" href="products.php">Vai ai prodotti</a>
  </div>
</section>
<?php render_footer(); ?>
