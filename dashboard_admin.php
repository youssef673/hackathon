<?php
require_once __DIR__ . '/includes/layout.php';
require_role('amministratore');
render_header('Dashboard Admin');
?>
<h2>Dashboard amministratore</h2>
<p>Da qui puoi gestire utenti e prodotti.</p>
<div class="actions">
  <a class="btn" href="admin/products.php">CRUD Prodotti</a>
  <a class="btn outline" href="admin/users.php">Gestione utenti</a>
</div>
<?php render_footer(); ?>
