<?php
/** @var array $datos */
/** @var int   $mes */
/** @var int   $ano */
/** @var int   $rol */
?>
<h1>🏥 Ventas por Especialidad — <?= str_pad($mes,2,'0',STR_PAD_LEFT) ?>/<?= $ano ?></h1>

<form class="row g-2 mb-4" method="GET">
  <input type="hidden" name="url" value="Reportes/especialidad">
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
      <th>Especialidad</th>
      <th class="text-end">Cant. Pedidos</th>
      <th class="text-end">Total Ventas</th>
    </tr>
  </thead>
  <tbody>
    <?php if (empty($datos)): ?>
      <tr><td colspan="3" class="text-center">No hay datos</td></tr>
    <?php else: ?>
      <?php foreach($datos as $r): ?>
      <tr>
        <td><?= htmlspecialchars($r['nombre_especialidad']) ?></td>
        <td class="text-end"><?= $r['cantidad_pedidos'] ?></td>
        <td class="text-end">$<?= number_format($r['total_ventas'],2) ?></td>
      </tr>
      <?php endforeach; ?>
    <?php endif; ?>
  </tbody>
</table>

<br>
<button class="btn btn-secondary me-2" onclick="history.back(); return false;">Regresar</button>
