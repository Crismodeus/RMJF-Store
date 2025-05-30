<h1>🕒 Pedidos Pendientes</h1>
<?php if(!empty($_SESSION['success'])): ?>
  <div class="alert alert-success"><?= $_SESSION['success'];unset($_SESSION['success']);?></div>
<?php endif;?>
<?php if(!empty($_SESSION['error'])): ?>
  <div class="alert alert-danger"><?= $_SESSION['error'];unset($_SESSION['error']);?></div>
<?php endif;?>

<table class="table">
  <thead>
    <tr>
      <th>ID</th><th>Cliente</th><th>Total</th><th>Fecha</th><th>Acciones</th>
    </tr>
  </thead>
  <tbody>
  <?php foreach($pedidos as $p): ?>
    <tr>
      <td><?= $p['id_pedido'] ?></td>
      <td><?= htmlspecialchars($p['cliente']) ?></td>
      <td>$<?= number_format($p['total_pedido'],2) ?></td>
      <td><?= $p['fecha_pedido'] ?></td>
      <td>
        <form action="<?= url('index.php?url=PedidosPendientes/actualizar/'.$p['id_pedido']) ?>"
              method="POST" class="d-inline">
          <select name="estado" class="form-select d-inline w-auto">
            <option value="Pagado">Pagado</option>
            <option value="Rechazado">Rechazado</option>
          </select>
          <button class="btn btn-sm btn-primary">Guardar</button>
        </form>
      </td>
    </tr>
  <?php endforeach;?>
  </tbody>
</table>

<br>
<button class="btn btn-secondary me-2" onclick="history.back(); return false;">Regresar</button>

