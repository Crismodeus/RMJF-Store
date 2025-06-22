<?php // admin/marcas/form.php 
$isEdit = !empty($marca);
?>
<h2 class="mb-4">
  <?= $isEdit ? '✏️ Editar Marca' : '➕ Nueva Marca' ?>
</h2>

<form action="<?= url('index.php?url=Marcas/guardar') ?>"
      method="POST" enctype="multipart/form-data">

  <?php if ($isEdit): ?>
    <input type="hidden" name="id" value="<?= $marca['id_marca'] ?>">
  <?php endif; ?>

  <div class="mb-3">
    <label class="form-label">Nombre de la Marca</label>
    <input type="text" name="nombre" class="form-control" required
      value="<?= $marca['nombre_marca'] ?? '' ?>">
  </div>

  <div class="mb-3">
    <label class="form-label">Logo (archivo local)</label>
    <input type="file" name="logo_file" class="form-control" accept="image/*"
    value="<?= $marca['imagen_blob'] ?? '' ?>">
  </div>

  <div class="mb-3">
    <label class="form-label">O Logo (URL)</label>
    <input type="text" name="logo_url" class="form-control"
      placeholder="https://…/logo.png"
      value="<?= $marca['imagen_marca'] ?? '' ?>">
  </div>

  <?php if ($isEdit && (!empty($marca['imagen_marca']) || !empty($marca['imagen_blob']))): ?>
  <div class="mb-3">
    <label class="form-label">Vista previa:</label><br>
    <?php
      if (!empty($marca['imagen_blob'])) {
        $src = 'data:image/png;base64,' . base64_encode($marca['imagen_blob']);
      } else {
        $src = htmlspecialchars($marca['imagen_marca']);
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
