<h2>✅ Mis Pedidos Pagados</h2>
<?php if (empty($pedidos)): ?>
  <p>No tienes pedidos pagados.</p>
<?php else: ?>
  <?php foreach ($pedidos as $p): ?>
    <div class="card mb-3">
      <div class="card-header">
        Pedido #<?= $p['id_pedido'] ?> — <?= $p['fecha_pedido'] ?>
      </div>
      <div class="card-body">
        <p><strong>Total: </strong>$<?= number_format($p['total_pedido'],2) ?></p>
        <a href="<?= url("index.php?url=Pedido/verDetalles/{$p['id_pedido']}") ?>"
           class="btn btn-sm btn-primary">Ver Detalle</a>
      </div>
    </div>
  <?php endforeach; ?>
<?php endif; ?>
