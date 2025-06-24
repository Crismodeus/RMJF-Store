<?php
// Filtros de Especialidad y Marca
?>
<form method="GET" action="<?= url('index.php') ?>" class="row g-3 mb-4">
  <input type="hidden" name="url" value="Catalogo/index">

  <div class="col-md-4">
    <select name="especialidad" class="form-select" onchange="this.form.submit()">
      <option value="">-- Seleccionar Especialidad --</option>
      <?php foreach ($especialidades as $esp): ?>
        <option value="<?= $esp['id_especialidad'] ?>"
          <?= $filtroEsp === (int)$esp['id_especialidad'] ? 'selected' : '' ?>>
          <?= htmlspecialchars($esp['nombre_especialidad']) ?>
        </option>
      <?php endforeach; ?>
    </select>
  </div>

  <?php if ($filtroEsp): ?>
  <div class="col-md-4">
    <select name="marca" class="form-select" onchange="this.form.submit()">
      <option value="">-- Todas las Marcas --</option>
      <?php foreach ($marcas as $m): ?>
        <option value="<?= $m['id_marca'] ?>"
          <?= $filtroMarca === (int)$m['id_marca'] ? 'selected' : '' ?>>
          <?= htmlspecialchars($m['nombre_marca']) ?>
        </option>
      <?php endforeach; ?>
    </select>
  </div>
  <?php endif; ?>
</form>

<?php
// Agrupamos medidas por producto y guardamos datos de marca en cada grupo
$agrupados = [];
foreach ($productos as $p) {
    $pid = $p['id_producto'];
    if (! isset($agrupados[$pid])) {
        $agrupados[$pid] = [
            'id_producto'     => $pid,
            'nombre'          => $p['nombre_producto'],
            'descripcion'     => $p['descripcion_producto'],
            'id_marca'        => $p['id_marca'],
            'nombre_marca'    => $p['nombre_marca'],
            'imagen_marca'    => $p['imagen_marca'],
            'imagen_producto' => $p['imagen_producto'],
            'medidas'         => []
        ];
    }
    $agrupados[$pid]['medidas'][] = [
        'id_medida' => $p['id_producto_medida'],
        'nombre'    => $p['nombre_medida'],
        'precio'    => $p['costo_producto'],
    ];
}
?>

<?php if ($agrupados): ?>
  <div class="row">
    <?php foreach ($agrupados as $prod): ?>
      <div class="col-md-4 mb-4">
        <div class="card h-100">

          <!-- Logo de la marca -->
          <div class="text-center mt-2">
            <img 
              src="<?= url('public/img/marcas/' . $prod['imagen_marca']) ?>" 
              alt="<?= htmlspecialchars($prod['nombre_marca']) ?>" 
              style="max-height:40px;"
            >
          </div>

          <!-- Imagen del producto -->
          <img 
            src="<?= url('public/img/productos/' . $prod['imagen_producto']) ?>" 
            class="card-img-top" 
            alt="<?= htmlspecialchars($prod['nombre']) ?>"
          >

          <div class="card-body d-flex flex-column">
            <h5 class="card-title"><?= htmlspecialchars($prod['nombre']) ?></h5>
            <p class="card-text"><?= htmlspecialchars($prod['descripcion']) ?></p>

            <!-- Selector de medidas y precios -->
            <div class="mb-3">
              <label class="form-label"><strong>Medida:</strong></label>
              <select id="medida_<?= $prod['id_producto'] ?>" class="form-select">
                <?php foreach ($prod['medidas'] as $m): ?>
                  <option 
                    value="<?= $m['id_medida'] ?>" 
                    data-precio="<?= $m['precio'] ?>"
                  >
                    <?= htmlspecialchars($m['nombre']) ?> (<?= '$'.number_format($m['precio'],2) ?>)
                  </option>
                <?php endforeach; ?>
              </select>
            </div>

            <!-- Form de agregar al carrito -->
            <form method="POST" action="<?= url('index.php?url=Carrito/agregar') ?>" class="mt-auto">
              <input type="hidden" name="id"      id="input_id_<?=    $prod['id_producto'] ?>">
              <input type="hidden" name="producto" value="<?= htmlspecialchars($prod['nombre']) ?>">
              <input type="hidden" name="medida"  id="input_medida_<?= $prod['id_producto'] ?>">
              <input type="hidden" name="precio"  id="input_precio_<?=  $prod['id_producto'] ?>">

              <div class="input-group">
                <input 
                  type="number" 
                  name="cantidad" 
                  value="1" 
                  min="1" 
                  max="10000" 
                  class="form-control"
                >
                <button class="btn btn-success">Agregar</button>
              </div>
            </form>

            <!-- Script inline para sincronizar inputs -->
            <script>
            (function() {
              const sel = document.getElementById('medida_<?= $prod['id_producto'] ?>');
              const inId      = document.getElementById('input_id_<?=    $prod['id_producto'] ?>');
              const inMedida  = document.getElementById('input_medida_<?= $prod['id_producto'] ?>');
              const inPrecio  = document.getElementById('input_precio_<?=  $prod['id_producto'] ?>');

              function actualizar() {
                const o = sel.options[sel.selectedIndex];
                inId.value     = o.value;
                inMedida.value = o.text;
                inPrecio.value = o.dataset.precio;
              }
              sel.addEventListener('change', actualizar);
              actualizar();
            })();
            </script>

          </div>
        </div>
      </div>
    <?php endforeach; ?>
  </div>
<?php elseif ($filtroEsp !== null): ?>
  <div class="alert alert-info">No hay productos que coincidan con tus filtros.</div>
<?php endif; ?>
