<?php
$isEdit = isset($editId);
?>
<h2 class="mb-4">
  <?= $isEdit ? '✏️ Editar PEM' : '➕ Nuevo PEM' ?>
</h2>

<form action="<?= url('index.php?url=ProductoEspecialidadMedidas/guardar') ?>"
      method="POST">

  <div class="mb-3">
    <label class="form-label">Prod ↔ Esp</label>
    <select name="pe" class="form-select" required>
      <option value="">-- Elige asociación --</option>
      <?php foreach($pes as $pe): ?>
      <option value="<?= $pe['id_producto_especialidad'] ?>"
        <?= $isEdit && $pe['id_producto_especialidad']==$editId?'selected':''?>>
        <?= htmlspecialchars($pe['nombre_producto'].' ↔ '.$pe['nombre_especialidad'])?>
      </option>
      <?php endforeach;?>
    </select>
  </div>

  <div class="mb-3">
    <label class="form-label">Medidas (Ctrl+click multis)</label>
    <select name="medidas[]" class="form-select" multiple size="6" required>
      <?php
        $selected = array_column($pem,'id_producto_medida');
        foreach($pms as $pm):
      ?>
      <option value="<?= $pm['id_producto_medida'] ?>"
        <?= in_array($pm['id_producto_medida'],$selected)?'selected':''?>>
        <?= htmlspecialchars($pm['nombre_producto'].' → '.$pm['nombre_medida']) ?>
      </option>
      <?php endforeach;?>
    </select>
  </div>

  <?php if($isEdit): ?>
    <input type="hidden" name="editId" value="<?= $editId ?>">
  <?php endif; ?>

  <div class="d-flex justify-content-end">
    <button class="btn btn-secondary me-2" onclick="history.back();return false;">
      Cancelar
    </button>
    <button type="submit" class="btn btn-success">
      Guardar
    </button>
  </div>
</form>
