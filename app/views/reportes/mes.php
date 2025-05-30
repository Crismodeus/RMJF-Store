<h1>📊 Ventas de <?= str_pad($mes,2,'0',STR_PAD_LEFT) ?>/<?= $ano ?></h1>

<form class="row g-2 mb-4" method="GET" action="">
  <input type="hidden" name="url" value="Reportes/mes">
  <div class="col-auto">
    <input type="number" name="mes" class="form-control" 
           min="1" max="12" value="<?= $mes ?>">
  </div>
  <div class="col-auto">
    <input type="number" name="ano" class="form-control" 
           min="2000" value="<?= $ano ?>">
  </div>
  <div class="col-auto">
    <button class="btn btn-primary">Filtrar</button>
  </div>
</form>

<table class="table table-bordered">
  <thead>
    <tr><th>Mes/Año</th><th>Cant. Pedidos</th><th>Total Ventas</th></tr>
  </thead>
  <tbody>
    <?php if (empty($datos)): ?>
      <tr><td colspan="3" class="text-center">Sin datos</td></tr>
    <?php else: ?>
      <?php foreach($datos as $r): ?>
      <tr>
        <td><?= str_pad($r['mes'],2,'0',STR_PAD_LEFT) ?>/<?= $r['ano'] ?></td>
        <td><?= $r['cantidad_pedidos'] ?></td>
        <td>$<?= number_format($r['total_ventas'],2) ?></td>
      </tr>
      <?php endforeach; ?>
    <?php endif; ?>
  </tbody>
</table>

<br>
<button class="btn btn-secondary me-2" onclick="history.back(); return false;">Regresar</button>
