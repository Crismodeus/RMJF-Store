<?php
// admin/productos/index.php
?>
<h2 class="mb-4">📦 Gestión de Productos</h2>

<?php if (isset($_SESSION['success'])): ?>
  <div class="alert alert-success"><?= $_SESSION['success']; unset($_SESSION['success']); ?></div>
<?php endif; ?>

<a href="<?= url('index.php?url=Productos/form') ?>" class="btn btn-primary mb-3">
  + Nuevo Producto
</a>

<table class="table table-striped">
  <thead>
    <tr>
      <th>ID</th><th>Nombre</th><th>Marca</th><th></th>
    </tr>
  </thead>
  <tbody>
    <?php foreach ($productos as $p): ?>
    <tr>
        <td>
        <?php
            // Determino la URL real a usar
            $src = '';
            if (! empty($p['imagen_blob'])) {
                // Si guardaste el BLOB
                $src = 'data:image/jpeg;base64,' . base64_encode($p['imagen_blob']);
            } elseif (! empty($p['imagen_producto'])) {
                // Si tienes una URL almacenada
                $src = htmlspecialchars($p['imagen_producto']);
            }
        ?>
        <?php if ($src): ?>
            <img
            src="<?= $src ?>"
            alt="Imagen <?= htmlspecialchars($p['nombre_producto']) ?>"
            style="max-height:50px;"
            >
        <?php else: ?>
            <span class="text-muted">Sin imagen</span>
        <?php endif; ?>
        </td>
        <td><?= $p['id_producto'] ?></td>
        <td><?= htmlspecialchars($p['nombre_producto']) ?></td>
        <td><?= htmlspecialchars($p['nombre_marca']) ?></td>
        <td class="text-end">
            <a 
            href="<?= url('index.php?url=Productos/form/' . $p['id_producto']) ?>" 
            class="btn btn-sm btn-warning"
            >Editar</a>
            <a 
            href="<?= url('index.php?url=Productos/eliminar/' . $p['id_producto']) ?>" 
            class="btn btn-sm btn-danger"
            onclick="return confirm('¿Eliminar este producto?');"
            >Eliminar</a>
        </td>
    </tr>
    <?php endforeach; ?>
  </tbody>
</table>
<br>
<button class="btn btn-secondary me-2" onclick="history.back(); return false;">Regresar</button>
