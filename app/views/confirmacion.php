<?php
// app/views/confirmacion.php
?>
<h2 class="mb-4">📝 Resumen de tu pedido</h2>

<table class="table table-bordered">
  <thead>
    <tr>
      <th>Producto</th>
      <th>Medida</th>
      <th>Cantidad</th>
      <th>Precio unidad</th>
      <th>Subtotal</th>
    </tr>
  </thead>
  <tbody>
    <?php foreach ($carrito as $item): ?>
      <tr>
        <td><?= htmlspecialchars($item['producto']) ?></td>
        <td><?= htmlspecialchars($item['medida']) ?></td>
        <td><?= $item['cantidad'] ?></td>
        <td>$<?= number_format($item['precio'],2) ?></td>
        <td>$<?= number_format($item['precio'] * $item['cantidad'],2) ?></td>
      </tr>
    <?php endforeach; ?>
    <tr>
      <td colspan="4" class="text-end"><strong>Total:</strong></td>
      <td><strong>$<?= number_format($total,2) ?></strong></td>
    </tr>
  </tbody>
</table>

<form action="<?= url('index.php?url=Pedido/procesar') ?>" method="POST">
  <input type="hidden" name="total" value="<?= $total ?>">
  <div class="d-flex justify-content-between">
    <a href="<?= url('index.php?url=Carrito/index') ?>" class="btn btn-secondary">
      ← Volver al Carrito
    </a>
    <button type="submit" class="btn btn-success">
      Confirmar y Pagar
    </button>
  </div>
</form>
