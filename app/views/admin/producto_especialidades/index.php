<h2 class="mb-4">🔗 Productos ↔ Especialidades</h2>
<?php if(isset($_SESSION['success'])): ?>
  <div class="alert alert-success"><?= $_SESSION['success']; unset($_SESSION['success']); ?></div>
<?php endif; ?>

<a href="<?= url('index.php?url=ProductosEspecialidades/form') ?>"
   class="btn btn-primary mb-3">+ Nueva Asociación</a>

<table class="table table-striped">
  <thead><tr>
    <th>ID</th><th>Producto</th><th>Especialidad</th><th></th>
  </tr></thead>
  <tbody>
  <?php foreach($list as $r): ?>
    <tr>
      <td><?= $r['id_producto_especialidad'] ?></td>
      <td><?= htmlspecialchars($r['nombre_producto']) ?></td>
      <td><?= htmlspecialchars($r['nombre_especialidad']) ?></td>
      <td class="text-end">
        <a href="<?= url('index.php?url=ProductosEspecialidades/form/'.$r['id_producto_especialidad']) ?>"
           class="btn btn-sm btn-warning">Editar</a>
        <a href="<?= url('index.php?url=ProductosEspecialidades/eliminar/'.$r['id_producto_especialidad']) ?>"
           class="btn btn-sm btn-danger"
           onclick="return confirm('¿Eliminar asociación?');"
        >Eliminar</a>
      </td>
    </tr>
  <?php endforeach; ?>
  </tbody>
</table>
