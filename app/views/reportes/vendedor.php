<?php
/** @var array $datos */
/** @var int   $mes */
/** @var int   $ano */
/** @var int   $rol */
?>
<h1>👤 Ventas por Vendedor — <?= str_pad($mes,2,'0',STR_PAD_LEFT) ?>/<?= $ano ?></h1>

<form class="row g-2 mb-4" method="GET">
  <input type="hidden" name="url" value="Reportes/vendedor">
  <div class="col-auto">
    <input type="number" name="mes" class="form-control" min="1" max="12" value="<?= $mes ?>">
  </div>
  <div class="col-auto">
    <input type="number" name="ano" class="form-control" min="2000" value="<?= $ano ?>">
  </div>
  <div class="col-auto">
    <button class="btn btn-primary">Filtrar</button>
  </div>
</form>

<table class="table table-striped">
  <thead>
    <tr>
      <?php if ($rol === 1): // admin ve columna de vendedor ?>
      <th>Vendedor</th>
      <?php endif; ?>
      <th class="text-end">Cant. Pedidos</th>
      <th class="text-end">Total Ventas</th>
    </tr>
  </thead>
  <tbody>
    <?php if (empty($datos)): ?>
      <tr><td colspan="<?= $rol===1?3:2 ?>" class="text-center">No hay datos</td></tr>
    <?php else: ?>
      <?php foreach($datos as $r): ?>
      <tr>
        <?php if ($rol === 1): ?>
        <td><?= htmlspecialchars($r['vendedor']) ?></td>
        <?php endif; ?>
        <td class="text-end"><?= $r['cantidad_pedidos'] ?></td>
        <td class="text-end">$<?= number_format($r['total_ventas'],2) ?></td>
      </tr>
      <?php endforeach; ?>
    <?php endif; ?>
  </tbody>
</table>
