<?php // admin/vendedores/form.php
$isEdit = !empty($vendedor);
?>
<h2 class="mb-4">
  <?= $isEdit ? '✏️ Editar Vendedor' : '➕ Nuevo Vendedor' ?>
</h2>

<form action="<?= url('index.php?url=Vendedores/guardar') ?>" method="POST">
  <?php if ($isEdit): ?>
    <input type="hidden" name="id" value="<?= $vendedor['id_usuario'] ?>">
  <?php endif; ?>

  <div class="mb-3">
    <label class="form-label">Nombre</label>
    <input type="text" name="nombre" class="form-control" required
      value="<?= $vendedor['nombre_usuario'] ?? '' ?>">
  </div>

  <div class="mb-3">
    <label class="form-label">Email</label>
    <input type="email" name="email" class="form-control" required
      value="<?= $vendedor['email_usuario'] ?? '' ?>">
  </div>

  <div class="mb-3">
    <label class="form-label">Cédula</label>
    <input type="text" name="cedula" class="form-control" required
      value="<?= $vendedor['cedula_usuario'] ?? '' ?>">
  </div>

  <div class="mb-3">
    <label class="form-label">
      <?= $isEdit ? 'Cambiar contraseña (opcional)' : 'Contraseña' ?>
    </label>
    <input type="password" name="password" class="form-control"
      <?= $isEdit ? '' : 'required' ?>>
  </div>

  <div class="d-flex justify-content-end">
    <button class="btn btn-secondary me-2"
      onclick="history.back();return false;">Cancelar</button>
    <button type="submit" class="btn btn-success">
      <?= $isEdit ? 'Actualizar' : 'Crear' ?>
    </button>
  </div>
</form>
