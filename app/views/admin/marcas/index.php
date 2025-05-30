<?php // admin/marcas/index.php ?>
<h2 class="mb-4">🏷️ Gestión de Marcas</h2>

<?php if (isset($_SESSION['success'])): ?>
  <div class="alert alert-success"><?= $_SESSION['success']; unset($_SESSION['success']); ?></div>
<?php endif; ?>

<a href="<?= url('index.php?url=Marcas/form') ?>" class="btn btn-primary mb-3">
  + Nueva Marca
</a>

<table class="table table-striped">
  <thead>
    <tr><th>ID</th><th>Logo</th><th>Nombre</th><th></th></tr>
  </thead>
  <tbody>
    <?php foreach ($marcas as $m): ?>
    <tr>
      <td><?= $m['id_marca'] ?></td>
      <td>
        <?php
          if (!empty($m['imagen_blob'])) {
            $src = 'data:image/png;base64,' . base64_encode($m['imagen_blob']);
          } elseif (!empty($m['imagen_marca'])) {
            $src = htmlspecialchars($m['imagen_marca']);
          } else {
            $src = null;
          }
        ?>
        <?php if ($src): ?>
          <img src="<?= $src ?>" style="max-height:40px;">
        <?php else: ?>
          <span class="text-muted">—</span>
        <?php endif; ?>
      </td>
      <td><?= htmlspecialchars($m['nombre_marca']) ?></td>
      <td class="text-end">
        <a href="<?= url('index.php?url=Marcas/form/' . $m['id_marca']) ?>"
           class="btn btn-sm btn-warning">Editar</a>
        <a href="<?= url('index.php?url=Marcas/eliminar/' . $m['id_marca']) ?>"
           class="btn btn-sm btn-danger"
           onclick="return confirm('Eliminar esta marca?');"
        >Eliminar</a>
      </td>
    </tr>
    <?php endforeach; ?>
  </tbody>
</table>
<br>
<button class="btn btn-secondary me-2" onclick="history.back(); return false;">Regresar</button>

