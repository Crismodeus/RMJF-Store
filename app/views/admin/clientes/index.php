<?php // admin/clientes/index.php ?>
<h2 class="mb-4">👥 Gestión de Clientes</h2>

<?php if (isset($_SESSION['success'])): ?>
  <div class="alert alert-success">
    <?= $_SESSION['success']; unset($_SESSION['success']); ?>
  </div>
<?php endif; ?>

<a href="<?= url('index.php?url=Clientes/form') ?>"
   class="btn btn-primary mb-3">+ Nuevo Cliente</a>

<table class="table table-striped">
  <thead>
    <tr>
      <th>ID</th><th>Nombre</th><th>Email</th><th>Cédula</th><th></th>
    </tr>
  </thead>
  <tbody>
    <?php foreach ($clientes as $c): ?>
    <tr>
      <td><?= $c['id_usuario'] ?></td>
      <td><?= htmlspecialchars($c['nombre_usuario']) ?></td>
      <td><?= htmlspecialchars($c['email_usuario']) ?></td>
      <td><?= htmlspecialchars($c['cedula_usuario']) ?></td>
      <td class="text-end">
        <a href="<?= url('index.php?url=Clientes/form/' . $c['id_usuario']) ?>"
           class="btn btn-sm btn-warning">Editar</a>
        <a href="<?= url('index.php?url=Clientes/eliminar/' . $c['id_usuario']) ?>"
           class="btn btn-sm btn-danger"
           onclick="return confirm('¿Eliminar este cliente?');"
        >Eliminar</a>
      </td>
    </tr>
    <?php endforeach; ?>
  </tbody>
</table>

<br>
<button class="btn btn-secondary me-2" onclick="history.back(); return false;">Regresar</button>

