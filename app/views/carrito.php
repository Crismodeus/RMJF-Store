<?php
// app/views/carrito.php
?>
<?php $rol = $_SESSION['usuario']['id_rol'] ?? 0; ?>

<!-- … listado de ítems … -->
<div class="d-flex justify-content-end mt-4">
  <?php if (in_array($rol, [1,2], true)): ?>
    <!-- Admin/Vendedor: botón Registrar Orden -->
    <button id="btnRegistrarOrden" class="btn btn-success">
      Registrar Orden
    </button>
  <?php else: ?>
    <!-- Cliente: flujo PayPal -->
    <div id="paypal-button-container"></div>
  <?php endif; ?>
</div>

<?php if (in_array($rol, [1,2], true)): ?>
<script>
document.getElementById('btnRegistrarOrden').addEventListener('click', function(e) {
  e.target.disabled = true;
  fetch('index.php?url=Carrito/registrar', { method: 'POST' })
    .then(res => res.json())
    .then(data => {
      if (data.success) {
        alert('✅ Pedido registrado con éxito (ID ' + data.idPedido + ')');
        window.location.href = 'index.php?url=Dashboard/index';
      } else {
        alert('❌ ' + data.error);
        e.target.disabled = false;
      }
    })
    .catch(err => {
      console.error(err);
      alert('❌ Error de red. Intenta de nuevo.');
      e.target.disabled = false;
    });
});
</script>
<?php else: ?>
<!-- Aquí carga tu SDK de PayPal y el script paypal.Buttons(...) como antes -->
<script src="https://www.paypal.com/sdk/js?client-id=TU_CLIENT_ID&currency=USD"></script>
<script>
  paypal.Buttons({ /* createOrder, onApprove, onError */ })
        .render('#paypal-button-container');
</script>
<?php endif; ?>

<h2 class="mb-4">🛒 Tu Carrito</h2>

<?php if (isset($_SESSION['success'])): ?>
  <div class="alert alert-success">
    <?= htmlspecialchars($_SESSION['success']); unset($_SESSION['success']); ?>
  </div>
<?php endif; ?>

<?php if (empty($carrito)): ?>
  <div class="alert alert-info">Tu carrito está vacío.</div>
  <a href="<?= url('index.php?url=Catalogo/index') ?>" class="btn btn-primary">Volver al Catálogo</a>
<?php else: ?>
  <table class="table table-striped align-middle">
    <thead>
      <tr>
        <th>Producto</th>
        <th>Medida</th>
        <th>Cantidad</th>
        <th>Precio unidad</th>
        <th>Subtotal</th>
        <th></th>
      </tr>
    </thead>
    <tbody>
      <?php foreach ($carrito as $item): ?>
        <tr>
          <td><?= htmlspecialchars($item['producto']) ?></td>
          <td><?= htmlspecialchars($item['medida']) ?></td>
          <td><?= $item['cantidad'] ?></td>
          <td>$<?= number_format($item['precio'], 2) ?></td>
          <td>$<?= number_format($item['precio'] * $item['cantidad'], 2) ?></td>
          <td>
            <a href="<?= url('index.php?url=Carrito/eliminar/' . $item['id']) ?>"
               class="btn btn-sm btn-danger"
               onclick="return confirm('¿Eliminar este artículo?');"
            >×</a>
          </td>
        </tr>
      <?php endforeach; ?>
      <tr>
        <td colspan="4" class="text-end"><strong>Total:</strong></td>
        <td colspan="2"><strong>$<?= number_format($total, 2) ?></strong></td>
      </tr>
    </tbody>
  </table>

  <div class="d-flex justify-content-between">
    <a href="<?= url('index.php?url=Catalogo/index') ?>" class="btn btn-secondary">
      ← Seguir comprando
    </a>
    <a href="<?= url('index.php?url=Pago/paypal') ?>" class="btn btn-success" id="btnProcederPago">
        Proceder al Pago →
    </a>
  </div>
<?php endif; ?>
