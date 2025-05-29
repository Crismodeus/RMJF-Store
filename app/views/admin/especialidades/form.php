<?php // admin/especialidades/form.php 
$isEdit = !empty($especialidad);
?>
<h2 class="mb-4">
  <?= $isEdit ? '✏️ Editar Especialidad' : '➕ Nueva Especialidad' ?>
</h2>

<form action="<?= url('index.php?url=Especialidades/guardar') ?>"
      method="POST" enctype="multipart/form-data">

  <?php if ($isEdit): ?>
    <input type="hidden" name="id" 
           value="<?= $especialidad['id_especialidad'] ?>">
  <?php endif; ?>

  <div class="mb-3">
    <label class="form-label">Nombre</label>
    <input type="text" name="nombre" class="form-control" required
      value="<?= $especialidad['nombre_especialidad'] ?? '' ?>">
  </div>

  <div class="mb-3">
    <label class="form-label">Imagen (archivo)</label>
    <input type="file" name="foto_file" class="form-control" accept="image/*">
  </div>

  <div class="mb-3">
    <label class="form-label">O Imagen (URL)</label>
    <input type="text" name="foto_url" class="form-control"
      placeholder="https://ejemplo.com/imagen.png"
      value="<?= $especialidad['foto_especialidad'] ?? '' ?>">
  </div>

  <?php if ($isEdit && ($especialidad['foto_especialidad']!=='' || !empty($especialidad['foto_blob']))): ?>
  <div class="mb-3">
    <label class="form-label">Vista previa:</label><br>
    <?php
      if (!empty($especialidad['foto_blob'])) {
        $src = 'data:image/png;base64,' . base64_encode($especialidad['foto_blob']);
      } else {
        $src = htmlspecialchars($especialidad['foto_especialidad']);
      }
    ?>
    <img src="<?= $src ?>" style="max-height:100px;">
  </div>
  <?php endif; ?>

  <div class="d-flex justify-content-end">
    <button class="btn btn-secondary me-2" onclick="history.back();return false;">
      Cancelar
    </button>
    <button type="submit" class="btn btn-success">
      <?= $isEdit ? 'Actualizar' : 'Crear' ?>
    </button>
  </div>
</form>
