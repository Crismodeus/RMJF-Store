<?php
// admin/productos/form.php
$isEdit = !empty($producto);
?>
<h2 class="mb-4">
  <?= $isEdit ? '✏️ Editar Producto' : '➕ Nuevo Producto' ?>
</h2>

<form action="<?= url('index.php?url=Productos/guardar') ?>" method="POST" enctype="multipart/form-data">
  <?php if ($isEdit): ?>
    <input type="hidden" name="id" value="<?= $producto['id_producto'] ?>">
  <?php endif; ?>

  <div class="mb-3">
    <label class="form-label">Nombre</label>
    <input 
      type="text" 
      name="nombre" 
      class="form-control" 
      required 
      value="<?= $producto['nombre_producto'] ?? '' ?>"
    >
  </div>

  <div class="mb-3">
    <label class="form-label">Descripción</label>
    <textarea 
      name="descripcion" 
      class="form-control" 
      rows="3"
    ><?= $producto['descripcion_producto'] ?? '' ?></textarea>
  </div>

  <div class="mb-3">
    <label class="form-label">Marca</label>
    <select name="marca" class="form-select" required>
      <option value="">-- Seleccionar marca --</option>
      <?php foreach ($marcas as $m): ?>
      <option 
        value="<?= $m['id_marca'] ?>"
        <?= isset($producto['id_marca']) && $producto['id_marca']==$m['id_marca'] ? 'selected' : '' ?>
      >
        <?= htmlspecialchars($m['nombre_marca']) ?>
      </option>
      <?php endforeach; ?>
    </select>
  </div>

  <div class="mb-3">
    <label class="form-label">Imagen (archivo local)</label>
    <input 
      type="file" 
      name="imagen_file" 
      class="form-control"
      accept="image/*"
    >
  </div>

  <div class="mb-3">
    <label class="form-label">Imagen (URL o ruta)</label>
    <input 
      type="text" 
      name="imagen" 
      class="form-control" 
      value="<?= $producto['imagen_producto'] ?? '' ?>"
    >
    <?php if ($isEdit && !empty($producto['imagen_producto'])): ?>
    <img 
        src="<?= htmlspecialchars($producto['imagen_producto']) ?>" 
        style="max-height:100px; margin-top:8px;"
    >
    <?php endif; ?>

  <div class="d-flex justify-content-end">
    <button class="btn btn-secondary me-2" 
      onclick="history.back(); return false;">Cancelar</button>
    <button type="submit" class="btn btn-success">Guardar</button>
  </div>
</form>
