<h2>Detalle Pedido #<?= $idPedido ?></h2>
<table class="table">
  <thead><tr>
    <th>Producto</th><th>Medida</th><th>Cantidad</th><th>Precio</th>
  </tr></thead>
  <tbody>
    <?php foreach ($detalles as $d): ?>
      <tr>
        <td><?= htmlspecialchars($d['producto']) ?></td>
        <td><?= htmlspecialchars($d['medida']) ?></td>
        <td><?= $d['cantidad'] ?></td>
        <td>$<?= number_format($d['precio'],2) ?></td>
      </tr>
    <?php endforeach; ?>
  </tbody>
</table>
<a href="<?= url('index.php?url=Home/index') ?>" class="btn btn-secondary">Volver al Inicio</a>
