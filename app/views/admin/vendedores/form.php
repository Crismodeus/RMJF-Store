<?php
$isEdit = isset($editId);
?>
<h2 class="mb-4"><?= !empty($usuario['id_usuario']) ? '✏️ Editar Vendedor' : '➕ Nuevo Vendedor' ?></h2>
<?php if(!empty($errors)): ?>
  <div class="alert alert-danger">
    <ul class="mb-0">
    <?php foreach($errors as $e): ?>
      <li><?= htmlspecialchars($e) ?></li>
    <?php endforeach; ?>
    </ul>
  </div>
<?php endif; ?>
<form action="<?= url('index.php?url=Vendedores/guardar') ?>" method="POST">
  <?php if(!empty($usuario['id_usuario'])): ?>
    <input type="hidden" name="id" value="<?= $usuario['id_usuario'] ?>">
  <?php endif; ?>
  <div class="mb-3">
    <label>Nombre</label>
    <input type="text" name="nombre" class="form-control" required value="<?= htmlspecialchars($usuario['nombre_usuario'] ?? '') ?>">
  </div>
  <div class="mb-3">
    <label>Email</label>
    <input type="email" name="email" class="form-control" required value="<?= htmlspecialchars($usuario['email_usuario'] ?? '') ?>">
  </div>
  <div class="mb-3">
    <label>Cédula</label>
    <input type="text" name="cedula" class="form-control" required value="<?= htmlspecialchars($usuario['cedula_usuario'] ?? '') ?>">
  </div>
  <div class="mb-3">
    <label><?= empty($usuario['id_usuario']) ? 'Contraseña' : 'Nueva Contraseña (opcional)' ?></label>
    <input type="password" name="password" class="form-control" <?= empty($usuario['id_usuario']) ? 'required' : '' ?>>
  </div>
  <button type="submit" class="btn btn-success">Guardar</button>
  <a href="<?= url('index.php?url=Vendedores/index') ?>" class="btn btn-secondary ms-2">Cancelar</a>
</form>
