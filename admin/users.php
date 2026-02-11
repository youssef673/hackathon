<?php
require_once __DIR__ . '/../includes/layout.php';
require_role('amministratore');
$users = db()->query('SELECT u.id, u.name, u.email, r.name AS role_name, u.created_at FROM users u JOIN roles r ON r.id = u.role_id ORDER BY u.created_at DESC')->fetchAll();
render_header('Admin - Utenti');
?>
<h2>Gestione utenti</h2>
<table class="table">
  <thead><tr><th>ID</th><th>Nome</th><th>Email</th><th>Ruolo</th><th>Data creazione</th></tr></thead>
  <tbody>
  <?php foreach ($users as $user): ?>
    <tr>
      <td><?= (int) $user['id'] ?></td>
      <td><?= e($user['name']) ?></td>
      <td><?= e($user['email']) ?></td>
      <td><?= e($user['role_name']) ?></td>
      <td><?= e($user['created_at']) ?></td>
    </tr>
  <?php endforeach; ?>
  </tbody>
</table>
<?php render_footer(); ?>
