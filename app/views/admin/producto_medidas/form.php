<?php 
  $isEdit = !empty($medida);
?>
<h2 class="mb-4"><?= $isEdit? '✏️ Editar Medida':'➕ Nueva Medida' ?></h2>

<form action="<?= url('index.php?url=ProductoMedidas/guardar')?>" method="POST">
  <?php if($isEdit): ?>
    <input type="hidden" name="id" value="<?= $medida['id_producto_medida'] ?>">
    <input type="hidden" name="producto" value="<?= $medida['id_producto'] ?>">

    <div class="mb-3">
      <label class="form-label">Producto</label>
      <input type="text" readonly class="form-control-plaintext"
             value="<?= htmlspecialchars($medida['nombre_producto'] ?? '') ?>">
    </div>
  <?php else: ?>
    <div class="mb-3">
      <label class="form-label">Producto</label>
      <select name="producto" class="form-select" required>
        <option value="">-- Selecciona producto --</option>
        <?php foreach($productos as $p): ?>
        <option value="<?= $p['id_producto'] ?>"
          <?= (($medida['id_producto'] ?? '') == $p['id_producto']) ? 'selected':'' ?>>
          <?= htmlspecialchars($p['nombre_producto']) ?>
        </option>
        <?php endforeach;?>
      </select>
    </div>
  <?php endif; ?>

  <div class="mb-3">
    <label class="form-label">Medida</label>
    <input type="text" name="medida"
           class="form-control" required
           value="<?= htmlspecialchars($medida['nombre_medida'] ?? '') ?>">
  </div>

  <div class="mb-3">
    <label class="form-label">Costo</label>
    <input type="number" step="0.01" name="costo"
           class="form-control" required
           value="<?= htmlspecialchars($medida['costo_producto'] ?? '') ?>">
  </div>

   <div class="mb-3">
    <label class="form-label"><U></U>Unidades del producto</label>
    <input type="number" step="1" name="unidades"
           class="form-control" required
           value="<?= htmlspecialchars($medida['unidades_producto'] ?? '') ?>">
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
