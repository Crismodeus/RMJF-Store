<?php 
  $isEdit = !empty($asoc);
?>
<h2 class="mb-4">
  <?= $isEdit ? '✏️ Editar Asociación' : '➕ Nueva Asociación' ?>
</h2>

<form action="<?= url('index.php?url=ProductosEspecialidades/guardar')?>" method="POST">
  <?php if($isEdit): ?>
    <input type="hidden" name="id" value="<?= $asoc['id_producto_especialidad'] ?>">
    <input type="hidden" name="producto" value="<?= $asoc['id_producto'] ?>">

    <div class="mb-3">
      <label class="form-label">Producto</label>
      <input type="text" readonly class="form-control-plaintext"
             value="<?= htmlspecialchars($asoc['nombre_producto'] ?? '') ?>">
    </div>
  <?php else: ?>
    <div class="mb-3">
      <label class="form-label">Producto</label>
      <select name="producto" class="form-select" required>
        <option value="">-- Selecciona producto --</option>
        <?php foreach($productos as $p): ?>
        <option value="<?= $p['id_producto'] ?>"
          <?= (($asoc['id_producto'] ?? '') == $p['id_producto']) ? 'selected':'' ?>>
          <?= htmlspecialchars($p['nombre_producto']) ?>
        </option>
        <?php endforeach;?>
      </select>
    </div>
  <?php endif; ?>

  <div class="mb-3">
    <label class="form-label">Especialidad</label>
    <select name="especialidad" class="form-select" required>
      <option value="">-- Selecciona especialidad --</option>
      <?php foreach($especialidades as $e): ?>
      <option value="<?= $e['id_especialidad'] ?>"
        <?= (($asoc['id_especialidad'] ?? '') == $e['id_especialidad']) ? 'selected':'' ?>>
        <?= htmlspecialchars($e['nombre_especialidad']) ?>
      </option>
      <?php endforeach;?>
    </select>
  </div>

  <div class="d-flex justify-content-end">
    <button class="btn btn-secondary me-2"
            onclick="history.back();return false;">
      Cancelar
    </button>
    <button type="submit" class="btn btn-success">
      <?= $isEdit? 'Actualizar':'Crear' ?>
    </button>
  </div>
</form>
