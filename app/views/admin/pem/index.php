<h2 class="mb-4">⚙️ Medidas por Especialidad</h2>
<?php if(isset($_SESSION['success'])): ?>
  <div class="alert alert-success"><?= $_SESSION['success']; unset($_SESSION['success']); ?></div>
<?php endif; ?>

<a href="<?= url('index.php?url=ProductoEspecialidadMedidas/form') ?>"
   class="btn btn-primary mb-3">+ Nueva Asociación PEM</a>

<table class="table table-striped">
  <thead><tr>
    <th>ID</th><th>Producto</th><th>Especialidad</th><th>Medida</th><th></th>
  </tr></thead>
  <tbody>
  <?php foreach($list as $r): ?>
    <tr>
      <td><?= $r['id_pem'] ?></td>
      <td><?= htmlspecialchars($r['nombre_producto']) ?></td>
      <td><?= htmlspecialchars($r['nombre_especialidad']) ?></td>
      <td><?= htmlspecialchars($r['nombre_medida']) ?></td>
      <td class="text-end">
        <a href="<?= url('index.php?url=ProductoEspecialidadMedidas/eliminar/'.$r['id_pem']) ?>"
           class="btn btn-sm btn-danger"
           onclick="return confirm('Eliminar?');"
        >Eliminar</a>
      </td>
    </tr>
  <?php endforeach; ?>
  </tbody>
</table>
<br>
<button class="btn btn-secondary me-2" onclick="history.back(); return false;">Regresar</button>
