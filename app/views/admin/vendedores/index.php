<?php // admin/vendedores/index.php ?>
<h2 class="mb-4">🛒 Gestión de Vendedores</h2>

<?php if (isset($_SESSION['success'])): ?>
  <div class="alert alert-success">
    <?= $_SESSION['success']; unset($_SESSION['success']); ?>
  </div>
<?php endif; ?>

<a href="<?= url('index.php?url=Vendedores/form') ?>"
   class="btn btn-primary mb-3">+ Nuevo Vendedor</a>

<table class="table table-striped">
  <thead>
    <tr>
      <th>ID</th>
      <th>Nombre</th>
      <th>Email</th>
      <th>Cédula</th>
      <th></th>
    </tr>
  </thead>
  <tbody>
    <?php foreach ($vendedores as $v): ?>
    <tr>
      <td><?= $v['id_usuario'] ?></td>
      <td><?= htmlspecialchars($v['nombre_usuario']) ?></td>
      <td><?= htmlspecialchars($v['email_usuario']) ?></td>
      <td><?= htmlspecialchars($v['cedula_usuario']) ?></td>
      <td class="text-end">
        <a href="<?= url('index.php?url=Vendedores/form/' . $v['id_usuario']) ?>"
           class="btn btn-sm btn-warning">Editar</a>
        <a href="<?= url('index.php?url=Vendedores/eliminar/' . $v['id_usuario']) ?>"
           class="btn btn-sm btn-danger"
           onclick="return confirm('¿Eliminar este vendedor?');"
        >Eliminar</a>
      </td>
    </tr>
    <?php endforeach; ?>
  </tbody>
</table>
<br>
<button class="btn btn-secondary me-2" onclick="window.location.href='<?= url('index.php?url=Dashboard/index') ?>'; return false;">Regresar</button>