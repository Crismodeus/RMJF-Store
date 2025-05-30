<h1>Panel de Vendedor</h1>
<p>Hola, <?= htmlspecialchars($_SESSION['usuario']['nombre_usuario']) ?></p>

<div class="row">
  <div class="col">
    <a class="btn btn-success" href="<?= url('index.php?url=Pedidos/nuevo') ?>">
      🛒 Realizar Venta
    </a>
  </div>
  <div class="col">
    <a class="btn btn-info" href="<?= url('index.php?url=Reportes/vendedor') ?>">
      📊 Mis Ventas
    </a>
  </div>
</div>

<h2 class="mt-4">Últimas Ventas (Mes Actual)</h2>
<table class="table">
  <thead><tr>
    <th>ID</th><th>Cliente</th><th>Total</th><th>Fecha</th>
  </tr></thead>
  <tbody>
    <?php foreach($ventas as $v): ?>
    <tr>
      <td><?= $v['id_pedido'] ?></td>
      <td><?= htmlspecialchars($v['cliente']) ?></td>
      <td>$<?= number_format($v['total_pedido'],2) ?></td>
      <td><?= $v['fecha_pedido'] ?></td>
    </tr>
    <?php endforeach; ?>
  </tbody>
</table>
