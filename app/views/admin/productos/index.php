<h2 class="mb-4">📦 Gestión de Productos</h2>

<?php if (isset($_SESSION['success'])): ?>
  <div class="alert alert-success"><?= $_SESSION['success']; unset($_SESSION['success']); ?></div>
<?php endif; ?>

<a href="<?= url('index.php?url=Productos/form') ?>" class="btn btn-primary mb-3">
  + Nuevo Producto
</a>

<table class="table table-striped">
  <thead>
    <tr>
      <th>Imagen</th><th>ID</th><th>Nombre</th><th>Marca</th><th></th>
    </tr>
  </thead>
  <tbody>
    <?php foreach ($productos as $p): ?>
    <?php
        $src = '';
        if (! empty($p['imagen_blob'])) {
            $src = 'data:image/jpeg;base64,' . base64_encode($p['imagen_blob']);
        } elseif (! empty($p['imagen_producto'])) {
            $src = htmlspecialchars($p['imagen_producto']);
        }
    ?>
    <tr>
      <td>
        <?php if ($src): ?>
          <img src="<?= $src ?>" alt="Imagen <?= htmlspecialchars($p['nombre_producto']) ?>" style="max-height:50px;">
        <?php else: ?>
          <span class="text-muted">Sin imagen</span>
        <?php endif; ?>
      </td>
      <td><?= $p['id_producto'] ?></td>
      <td><?= htmlspecialchars($p['nombre_producto']) ?></td>
      <td><?= htmlspecialchars($p['nombre_marca']) ?></td>
      <td class="text-end">
        <a href="<?= url('index.php?url=Productos/form/' . $p['id_producto']) ?>" class="btn btn-sm btn-warning">Editar</a>
        <a href="<?= url('index.php?url=Productos/eliminar/' . $p['id_producto']) ?>" class="btn btn-sm btn-danger" onclick="return confirm('¿Eliminar este producto?');">Eliminar</a>
      </td>
    </tr>
    <?php endforeach; ?>
  </tbody>
</table>

<button class="btn btn-secondary me-2" onclick="window.location.href='<?= url('index.php?url=Dashboard/index') ?>'; return false;">Regresar</button>