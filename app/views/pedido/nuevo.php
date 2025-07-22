<h1>Iniciar Nueva Venta</h1>

<?php if (!empty($_SESSION['error'])): ?>
  <div class="alert alert-danger">
    <?= $_SESSION['error']; unset($_SESSION['error']); ?>
  </div>
<?php endif; ?>

<form action="<?= url('index.php?url=Pedido/iniciar') ?>" method="POST" class="w-50">
  <div class="mb-3">
    <label class="form-label">Selecciona Cliente</label>
    <select name="cliente" class="form-select" required>
      <option value="">-- Elige cliente --</option>
      <?php foreach ($clientes as $c): ?>
        <option value="<?= $c['id_usuario'] ?>">
          <?= htmlspecialchars($c['nombre_usuario']) ?> (<?= htmlspecialchars($c['email_usuario']) ?>)
        </option>
      <?php endforeach; ?>
    </select>
  </div>
  <button type="submit" class="btn btn-success">Comenzar Venta</button>
  <a href="<?= url('index.php?url=Dashboard/index') ?>" class="btn btn-secondary">Cancelar</a>
</form>
