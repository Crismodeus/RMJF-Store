<h1>Panel de Administrador</h1>
<p>Bienvenido de nuevo, <?= htmlspecialchars($_SESSION['usuario']['nombre_usuario']) ?></p>
<br>
<p></p>
<h3>Creación o Actualización de Datos</h3>
<div class="row mb-4 row-cols-2 g-3">
  <div class="col d-grid"><a class="btn btn-primary" href="<?= url('index.php?url=Productos/index') ?>">Productos</a></div>
  <div class="col d-grid"><a class="btn btn-primary" href="<?= url('index.php?url=Marcas/index') ?>">Marcas</a></div>
  <div class="col d-grid"><a class="btn btn-primary" href="<?= url('index.php?url=Especialidades/index') ?>">Especialidades</a></div>
  <div class="col d-grid"><a class="btn btn-primary" href="<?= url('index.php?url=ProductoMedidas/index') ?>">Medidas de Producto</a></div>
  <div class="col d-grid"><a class="btn btn-primary" href="<?= url('index.php?url=ProductosEspecialidades/index') ?>">Productos por Especialidad</a></div>
  <div class="col d-grid"><a class="btn btn-primary" href="<?= url('index.php?url=ProductoEspecialidadMedidas/index') ?>">Medidas por Especialidad</a></div>
  <div class="col d-grid"><a class="btn btn-primary" href="<?= url('index.php?url=Clientes/index') ?>">Clientes</a></div>
  <div class="col d-grid"><a class="btn btn-primary" href="<?= url('index.php?url=Vendedores/index') ?>">Vendedores</a></div>
  
</div>
<h3>Ventas</h3>
<div class="row mb-4 g-3">
    <div class="col d-grid">
    <a class="btn btn-success" href="<?= url('index.php?url=Pedido/nuevo') ?>">
      🛒 Realizar Venta
    </a>
    </div>
    <div class="col d-grid">
      <a class="btn btn-warning" href="<?= url('index.php?url=PedidosPendientes/index') ?>">
        🕒 Pedidos Pendientes de Pago
      </a>
    </div>
</div>
<br>
<h3>Reportes</h3>
<div class="row mb-4 g-3">
    <div class="col d-grid">
        <a class="btn btn-info" href="<?= url('index.php?url=Reportes/mes') ?>">
        📊 Ventas por Mes
        </a>
    </div>
    <div class="col d-grid">
        <a class="btn btn-info" href="<?= url('index.php?url=Reportes/producto') ?>">
        📊 Ventas por Producto
        </a>
    </div>
<!--     <div class="col d-grid">
         <a class="btn btn-info" href="<?= url('index.php?url=Reportes/especialidad') ?>">
        🏥 Ventas por Especialidad
        </a>
    </div> -->
    <div class="col d-grid">
         <a class="btn btn-info" href="<?= url('index.php?url=Reportes/vendedor') ?>">
        👤 Ventas por Vendedor
        </a>
    </div>
</div>


<h2>Ventas del Mes Actual</h2>
<table class="table">
  <thead><tr>
    <th>Mes</th><th>Total</th>
  </tr></thead>
  <tbody>
    <tr>
      <td><?= date('m/Y') ?></td>
      <td>$<?= number_format($totales['total'],2) ?></td>
    </tr>
  </tbody>
</table>
