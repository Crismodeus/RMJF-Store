<?php
// app/views/pago/paypal.php
// Asegúrate de definir PAYPAL_CLIENT_ID en config/config.php
$rol      = $_SESSION['usuario']['id_rol'] ?? 0;
$pedidoId = (int)$idPedido;
$totalEsc = number_format($total, 2, '.', '');
?>
<!-- Modal PayPal -->
<div class="modal fade" id="paypalModal" tabindex="-1" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title">💳 Pago Seguro con PayPal</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
      </div>
      <div class="modal-body text-center">
        <p>Pedido #<?= htmlspecialchars($pedidoId) ?> — Total: <strong>$<?= $totalEsc ?></strong></p>
        <div id="paypal-button-container"></div>
        <?php if ($rol === 3): // Solo cliente ?>
          <button id="btnTransferencia" class="btn btn-secondary mt-3">
            Pago con Transferencia
          </button>
        <?php endif ?>
      </div>
    </div>
  </div>
</div>

<!-- Modal Transferencia -->
<div class="modal fade" id="transferModal" tabindex="-1" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title">Registro de Transferencia</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
      </div>
      <div class="modal-body">
        <form id="formTransfer">
          <input type="hidden" name="pedidoId" value="<?= $pedidoId ?>">
          <div class="mb-3">
            <label for="banco" class="form-label">Banco o Cooperativa</label>
            <select id="banco" name="banco" class="form-select" required>
              <option value="">-- Selecciona --</option>
              <option>Banco Pichincha</option>
              <option>Banco de Guayaquil</option>
              <option>Banco del Pacífico</option>
              <option>Banco Bolivariano</option>
              <option>Cooperativa 29 de Octubre</option>
              <option>Cooperativa JEP</option>
              <option>Cooperativa San José</option>
            </select>
          </div>
          <div class="mb-3">
            <label for="comprobante" class="form-label">Número de comprobante</label>
            <input id="comprobante" type="text" name="comprobante" class="form-control" required>
          </div>
          <div class="mb-3">
            <label for="monto" class="form-label">Monto transferido</label>
            <input id="monto" type="number" name="monto" class="form-control" step="0.01"
                   value="<?= $totalEsc ?>" required>
          </div>
        </form>
      </div>
      <div class="modal-footer">
        <button class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
        <button id="saveTransfer" class="btn btn-primary">Guardar</button>
      </div>
    </div>
  </div>
</div>

<!-- SDK PayPal: deshabilita funding de tarjeta -->
<script src="https://www.paypal.com/sdk/js?client-id=AQ_SpQXaDTVRItjJMaMN7ZqJetNKjwfmekXfReWED7MFpGBhe3dErEcWmeosrjQowNi_M5D71GCmPNL6&currency=USD"></script>

<script>
document.addEventListener('DOMContentLoaded', () => {
  const paypalModalEl   = document.getElementById('paypalModal');
  const transferModalEl = document.getElementById('transferModal');
  const paypalModal     = new bootstrap.Modal(paypalModalEl);
  const transferModal   = new bootstrap.Modal(transferModalEl);

  // Abrir PayPal al cargar
  paypalModal.show();

  // Renderizar botones de PayPal (solo para cliente)
  if (<?= $rol ?> === 3) {
    paypal.Buttons({
      createOrder: (data, actions) => {
        return actions.order.create({
          purchase_units: [{
            reference_id: '<?= $idPedido ?>',
            amount: { value: '<?= number_format($total,2,'.','') ?>' }
          }]
        });
      },
      onApprove: (data, actions) => {
        return actions.order.capture()
          .then(() => {
            // confirmamos el pago
            return fetch('<?= url("index.php?url=Pago/confirmar") ?>', {
              method: 'POST',
              headers: { 'Content-Type': 'application/json' },
              body: JSON.stringify({ pedidoId: <?= $idPedido ?> })
            });
          })
          .then(response => {
            // si hubo problema HTTP, lo ignoramos
            if (!response.ok) console.warn('HTTP error', response.status);
            return response.json().catch(() => ({}));
          })
          .then(js => {
            // ocultamos modal y redirigimos de todas formas
            paypalModal.hide();
            window.location.href = "<?= url('index.php?url=Pedido/misPedidos') ?>";
          })
          .catch(err => {
            // en caso de fallo de red, también ocultamos y redirigimos
            console.error('Error confirmando pago:', err);
            paypalModal.hide();
            window.location.href = "<?= url('index.php?url=Pedido/misPedidos') ?>";
          });
      },
      onError: err => {
        console.error('Error PayPal:', err);
        paypalModal.hide();
        window.location.href = "<?= url('index.php?url=Home/index') ?>";
      }
    }).render('#paypal-button-container');
    paypalModal.show();
  }

  // Cuando abren transferencia desde cliente (rol=3)
  <?php if ($rol === 3): ?>
  document.getElementById('btnTransferencia').addEventListener('click', () => {
      // Mantenemos abierto paypalModal, pero llevamos al front el transferModal
      transferModal.show();
    });

    document.getElementById('saveTransfer').addEventListener('click', () => {
    const formData = Object.fromEntries(new FormData(
      document.getElementById('formTransfer')
    ).entries());

    fetch('<?= url("index.php?url=Pago/transferencia") ?>', {
      method: 'POST',
      headers: { 'Content-Type': 'application/json' },
      body: JSON.stringify(formData)
    })
    .then(res => {
      if (!res.ok) throw new Error('HTTP ' + res.status);
      return res.json();
    })
    .then(js => {
      if (js.success) {
        alert('✅ Transferencia registrada.');
        transferModal.hide();
        paypalModal.hide();
        window.location.href = "<?= url('index.php?url=Pedido/porPagar') ?>";
      } else {
        alert('❌ ' + js.error);
      }
    })
    .catch(err => {
      console.error('Transferencia AJAX error:', err);
      alert('Error de red al enviar transferencia.');
    });
  });
  <?php endif ?>

  // Si el usuario cierra el modal de PayPal sin pagar, lo mandamos a Home
  paypalModalEl.addEventListener('hidden.bs.modal', () => {
    window.location.href = "<?= url('index.php?url=Home/index') ?>";
  });
});
</script>

