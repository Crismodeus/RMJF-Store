<h2 class="mb-4">⚙️ Medidas de Productos</h2>
<?php if (isset($_SESSION['success'])): ?>
  <div class="alert alert-success"><?= $_SESSION['success']; unset($_SESSION['success']); ?></div>
<?php endif; ?>

<a href="<?= url('index.php?url=ProductoMedidas/form') ?>"
   class="btn btn-primary mb-3">+ Nueva Medida</a>

<table class="table table-striped">
  <thead>
    <tr>
      <th>ID</th><th>Producto</th><th>Medida</th><th>Costo</th><th></th>
    </tr>
  </thead>
  <tbody>
  <?php foreach($medidas as $m): ?>
    <tr>
      <td><?= $m['id_producto_medida'] ?></td>
      <td><?= htmlspecialchars($m['nombre_producto']) ?></td>
      <td><?= htmlspecialchars($m['nombre_medida']) ?></td>
      <td>$<?= number_format($m['costo_producto'],2) ?></td>
      <td class="text-end">
        <a href="<?= url('index.php?url=ProductoMedidas/form/'.$m['id_producto_medida']) ?>"
           class="btn btn-sm btn-warning">Editar</a>
        <a href="<?= url('index.php?url=ProductoMedidas/eliminar/'.$m['id_producto_medida']) ?>"
           class="btn btn-sm btn-danger"
           onclick="return confirm('¿Eliminar medida?');"
        >Eliminar</a>
      </td>
    </tr>
  <?php endforeach; ?>
  </tbody>
</table>
