<?php
// app/views/pago/paypal.php
$rol = $_SESSION['usuario']['id_rol'] ?? 0;
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
        <p>Pedido #<?= htmlspecialchars($idPedido) ?> — Total: <strong>$<?= number_format($total,2) ?></strong></p>
        <div id="paypal-button-container"></div>
        <?php if(in_array($rol,[1,2],true)): ?>
          <button id="btnTransferencia" class="btn btn-secondary mt-3">
            Pago con Transferencia
          </button>
        <?php endif; ?>
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
          <input type="hidden" name="pedidoId" value="<?= $idPedido ?>">
          <div class="mb-3">
            <label class="form-label">Banco o Cooperativa</label>
            <select name="banco" class="form-select" required>
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
            <label class="form-label">Número de comprobante</label>
            <input type="text" name="comprobante" class="form-control" required>
          </div>
          <div class="mb-3">
            <label class="form-label">Monto transferido</label>
            <input type="number" name="monto" class="form-control" step="0.01"
                   value="<?= number_format($total,2,'.','') ?>" required>
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

<!-- SDK PayPal -->
<script src="https://www.paypal.com/sdk/js?client-id=AQ_SpQXaDTVRItjJMaMN7ZqJetNKjwfmekXfReWED7MFpGBhe3dErEcWmeosrjQowNi_M5D71GCmPNL6&currency=USD"></script>
<script>
document.addEventListener('DOMContentLoaded', () => {
  const paypalModalEl   = document.getElementById('paypalModal');
  const paypalModal     = new bootstrap.Modal(paypalModalEl);
  const transferModalEl = document.getElementById('transferModal');
  const transferModal   = new bootstrap.Modal(transferModalEl);

  // Si cierran el modal de PayPal, vuelven a Home
  paypalModalEl.addEventListener('hidden.bs.modal', () => {
    window.location.href = "<?= url('index.php?url=Home/index') ?>";
  });

  // Mostrar modal de PayPal
  paypalModal.show();

  // Renderizar botón PayPal (solo para clientes)
  if (<?= $rol ?> === 3) {
    paypal.Buttons({
      createOrder: (d,a) => a.order.create({
        purchase_units: [{ reference_id: '<?= $idPedido ?>',
                           amount: { value: '<?= number_format($total,2,'.','') ?>' } }]
      }),
      onApprove: (data,actions) => actions.order.capture()
        .then(() => fetch('<?= url("index.php?url=Pago/confirmar") ?>', {
          method:'POST',
          headers:{'Content-Type':'application/json'},
          body: JSON.stringify({ pedidoId: <?= $idPedido ?> })
        }))
        .then(r=>r.json())
        .then(js => {
          if (js.success) {
            paypalModal.hide();
            window.location.href = "<?= url('index.php?url=Pedido/misPedidos') ?>";
          } else {
            alert('Error: '+js.error);
          }
        }),
      onError: err => alert('Error PayPal: '+err)
    }).render('#paypal-button-container');
  }

  // Botón “Pago con Transferencia” (solo admin/vendedor)
    <?php if($rol === 3): // sólo cliente puede pagar por transferencia ?>
      <button id="btnTransferencia" class="btn btn-secondary mt-3">
        Pago con Transferencia
      </button>
    <?php endif; ?>

    document.getElementById('saveTransfer').addEventListener('click', () => {
      const formData = Object.fromEntries(new FormData(
        document.getElementById('formTransfer')
      ).entries());
      fetch('<?= url("index.php?url=Pago/transferencia") ?>', {
        method:'POST',
        headers:{'Content-Type':'application/json'},
        body: JSON.stringify(formData)
      })
      .then(r=>r.json())
      .then(js => {
        if (js.success) {
          alert('✅ Transferencia registrada y correo enviado.');
          transferModal.hide();
          window.location.href = "<?= url('index.php?url=Dashboard/index') ?>";
        } else {
          alert('❌ '+js.error);
        }
      })
      .catch(_=> alert('❌ Error de red al enviar transferencia.'));
    });
  <?php endif; ?>
});
</script>
