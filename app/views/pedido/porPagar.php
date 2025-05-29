<?php
// app/views/pedido/porPagar.php
?>
<h2 class="mb-4">⌛ Mis Pedidos Pendientes de Pago</h2>

<?php if (empty($pedidos)): ?>
  <div class="alert alert-info">No tienes pedidos pendientes.</div>
<?php else: ?>
  <?php foreach ($pedidos as $p): ?>
    <div class="card mb-4 position-relative">
      <div class="card-header">
        Pedido #<?= $p['id_pedido'] ?> — <?= $p['fecha_pedido'] ?>
      </div>
      <div class="card-body">
        <table class="table table-sm">
          <thead><tr>
            <th>Producto</th><th>Medida</th><th>Cant.</th><th>Precio</th><th>Subtotal</th>
          </tr></thead>
          <tbody>
            <?php foreach ($p['detalles'] as $d): ?>
            <tr>
              <td><?= htmlspecialchars($d['producto']) ?></td>
              <td><?= htmlspecialchars($d['medida']) ?></td>
              <td><?= $d['cantidad'] ?></td>
              <td>$<?= number_format($d['precio'],2) ?></td>
              <td>$<?= number_format($d['precio']*$d['cantidad'],2) ?></td>
            </tr>
            <?php endforeach; ?>
            <tr>
              <td colspan="4" class="text-end"><strong>Total:</strong></td>
              <td><strong>$<?= number_format($p['total_pedido'],2) ?></strong></td>
            </tr>
          </tbody>
        </table>
      </div>
      <div class="card-footer text-end">
        <a 
          href="<?= url("index.php?url=Pago/paypal&pedido={$p['id_pedido']}") ?>" 
          class="btn btn-warning"
        >
          Pagar
        </a>
      </div>
    </div>
  <?php endforeach; ?>
<?php endif; ?>
