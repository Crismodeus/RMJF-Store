<?php // admin/especialidades/index.php ?>
<h2 class="mb-4">🩺 Gestión de Especialidades</h2>

<?php if (isset($_SESSION['success'])): ?>
  <div class="alert alert-success">
    <?= $_SESSION['success']; unset($_SESSION['success']); ?>
  </div>
<?php endif; ?>

<a href="<?= url('index.php?url=Especialidades/form') ?>"
   class="btn btn-primary mb-3">+ Nueva Especialidad</a>

<table class="table table-striped">
  <thead>
    <tr>
      <th>ID</th><th>Imagen</th><th>Nombre</th><th></th>
    </tr>
  </thead>
  <tbody>
    <?php foreach ($especialidades as $e): ?>
    <tr>
      <td><?= $e['id_especialidad'] ?></td>
      <td>
        <?php
          // Determinamos la fuente de la imagen
          if (!empty($e['foto_blob'])) {
              $src = 'data:image/png;base64,' . base64_encode($e['foto_blob']);
          } elseif ($e['foto_especialidad']!=='') {
              $src = htmlspecialchars($e['foto_especialidad']);
          } else {
              $src = null;
          }
        ?>
        <?php if ($src): ?>
          <img src="<?= $src ?>" style="max-height:50px;">
        <?php else: ?>
          <span class="text-muted">—</span>
        <?php endif; ?>
      </td>
      <td><?= htmlspecialchars($e['nombre_especialidad']) ?></td>
      <td class="text-end">
        <a href="<?= url('index.php?url=Especialidades/form/' . $e['id_especialidad']) ?>"
           class="btn btn-sm btn-warning">Editar</a>
        <a href="<?= url('index.php?url=Especialidades/eliminar/' . $e['id_especialidad']) ?>"
           class="btn btn-sm btn-danger"
           onclick="return confirm('Eliminar esta especialidad?');"
        >Eliminar</a>
      </td>
    </tr>
    <?php endforeach; ?>
  </tbody>
</table>
<br>
<button class="btn btn-secondary me-2" onclick="history.back(); return false;">Regresar</button>
