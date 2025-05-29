<?php
// pago/paypal.php
?>
<!-- Modal -->
<div 
  class="modal fade" 
  id="paypalModal" 
  tabindex="-1" 
  aria-labelledby="paypalModalLabel" 
  aria-hidden="true"
>
  <div class="modal-dialog modal-dialog-centered">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="paypalModalLabel">💳 Pago Seguro con PayPal</h5>
        <button 
          type="button" 
          class="btn-close" 
          data-bs-dismiss="modal" 
          aria-label="Cerrar"
        ></button>
      </div>
      <div class="modal-body">
        <p>Pedido #<?= htmlspecialchars($idPedido) ?> — Total a pagar: <strong>$<?= number_format($total,2) ?></strong></p>
        <div id="paypal-button-container"></div>
      </div>
    </div>
  </div>
</div>

<script src="https://www.paypal.com/sdk/js?client-id=AQ_SpQXaDTVRItjJMaMN7ZqJetNKjwfmekXfReWED7MFpGBhe3dErEcWmeosrjQowNi_M5D71GCmPNL6&currency=USD"></script>
<script>
    document.addEventListener('DOMContentLoaded', function() {
    const modalEl = document.getElementById('paypalModal');
    const paypalModal = new bootstrap.Modal(modalEl);

    // 1) Al cerrar el modal, vamos a Home
    modalEl.addEventListener('hidden.bs.modal', function() {
        window.location.href = "<?= url('index.php?url=Home/index') ?>";
    });

    // 2) Mostramos el modal y renderizamos PayPal
    paypalModal.show();
    paypal.Buttons({ … }).render('#paypal-button-container');
    });
</script>


<script>
  // 1) Al cargar la vista, abrimos el modal
  document.addEventListener('DOMContentLoaded', function() {
    const modalEl = document.getElementById('paypalModal');
    const paypalModal = new bootstrap.Modal(modalEl);
    paypalModal.show();

    // 2) Renderizamos el botón PayPal DENTRO del modal
    paypal.Buttons({
      createOrder(data, actions) {
        return actions.order.create({
          purchase_units: [{
            reference_id: '<?= $idPedido ?>',
            amount: { value: '<?= number_format($total,2) ?>' }
          }]
        });
      },
      onApprove(data, actions) {
        return actions.order.capture().then(function(details) {
            window.location.href = "<?= url('index.php?url=Pago/exito&orderID=') ?>" + data.orderID;
          // Puedes actualizar BD en Pago/exito si lo necesitas...
            window.location.href = "<?= url('index.php?url=Home/index') ?>";
        });
      }
      onApprove: function(data, actions) {
        return actions.order.capture().then(function(details) {
            // redirige incluyendo idPedido y orderID
            window.location.href = "<?= url('index.php?url=Pago/exito&id=' . $idPedido) ?>&orderID=" + data.orderID;
        });
      }
      
    }).render('#paypal-button-container');
  });
</script>
