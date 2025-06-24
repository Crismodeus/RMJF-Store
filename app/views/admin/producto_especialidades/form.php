<?php
$isEdit = !empty($asoc);
?>
<h2 class="mb-4">
  <?= $isEdit ? '✏️ Editar Asociación' : '➕ Nueva Asociación' ?>
</h2>

<?php if(isset($_SESSION['success'])): ?>
  <div class="alert alert-info"><?= $_SESSION['success']; unset($_SESSION['success']); ?></div>
<?php endif; ?>

<form action="<?= url('index.php?url=ProductosEspecialidades/guardar') ?>"
      method="POST">
  <?php if ($isEdit): ?>
    <input type="hidden" name="id" value="<?= $asoc['id_producto_especialidad'] ?>">
  <?php endif; ?>

  <div class="mb-3">
    <label class="form-label">Producto</label>
    <select name="producto" class="form-select" required>
      <option value="">-- Seleccionar --</option>
      <?php foreach($productos as $p): ?>
      <option value="<?= $p['id_producto'] ?>"
        <?= ($isEdit && $asoc['id_producto']==$p['id_producto']) ? 'selected' : '' ?>>
        <?= htmlspecialchars($p['nombre_producto']) ?>
      </option>
      <?php endforeach; ?>
    </select>
  </div>

  <div class="mb-3">
    <label class="form-label">Especialidad</label>
    <select name="especialidad" class="form-select" required>
      <option value="">-- Seleccionar --</option>
      <?php foreach($especialidades as $e): ?>
      <option value="<?= $e['id_especialidad'] ?>"
        <?= ($isEdit && $asoc['id_especialidad']==$e['id_especialidad']) ? 'selected' : '' ?>>
        <?= htmlspecialchars($e['nombre_especialidad']) ?>
      </option>
      <?php endforeach; ?>
    </select>
  </div>

  <div class="d-flex justify-content-end">
    <button 
      class="btn btn-secondary me-2" 
      onclick="history.back(); return false;"
    >Cancelar</button>
    <button type="submit" class="btn btn-success">
      <?= $isEdit ? 'Actualizar' : 'Crear' ?>
    </button>
  </div>
</form>
