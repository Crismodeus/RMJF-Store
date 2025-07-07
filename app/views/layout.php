<?php 
// config y helper
require_once __DIR__ . '/../../config/config.php';

// SESIÓN ya está iniciada por tu Controller::__construct()
$user      = $_SESSION['usuario'] ?? null;
$cartCount = 0;
if (isset($_SESSION['carrito']) && is_array($_SESSION['carrito'])) {
    foreach ($_SESSION['carrito'] as $item) {
        $cartCount += $item['cantidad'];
    }
}
/* 
if ($_SESSION['usuario']['id_rol'] === 3): ?>
  <a href="<?= url('index.php?url=Pago/paypal&pedido='.$p['id_pedido']) ?>"
     class="btn btn-success btn-sm">
    Pagar
  </a>*/

?> 

<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8">
  <title>Tienda RMJF</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css">
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
  <style>
  /* Para fotos de producto (p. ej. en catálogo/card) */
  .thumbnail-product {
    width: 150px;              /* ancho fijo */
    height: 150px;             /* alto fijo */
    object-fit: contain;       /* ajusta la imagen completa, no la recorta */
    background-color: #ffffff; /* fondo blanco para rellenar huecos */
    border-radius: 8px;        /* esquinas redondeadas, pon el radio que prefieras */
    display: block;
    margin: 0 auto;            /* centrar */
    padding: 8px;              /* opcional: crea un “margen interior” */
    box-shadow: 0 0 4px rgba(0,0,0,0.1);
  }
  </style>
</head>
<body>
<nav class="navbar navbar-expand-lg navbar-light bg-light">
  <div class="container">
    <a class="nav-link" href="<?= url('index.php?url=Home/index') ?>">
      <img
      src="https://es.rmjf.ec/wp-content/uploads/2023/01/Logo_RMJF_normal_Mesa-de-trabajo-1-300x204.png"
      alt="RMJF"
      style="height:80px; object-fit:contain;"
      >
    </a>
  
    <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navMenu">
      <span class="navbar-toggler-icon"></span>
    </button>
    <div class="collapse navbar-collapse" id="navMenu">
      <ul class="navbar-nav me-auto">
        <li class="nav-item"><a class="nav-link" href="<?= url('index.php?url=Catalogo/index') ?>">Especialidades</a></li>
      </ul>

      <ul class="navbar-nav ms-auto align-items-center">
        <!-- Carrito -->
        <li class="nav-item me-3">
          <a class="nav-link position-relative" href="<?= url('index.php?url=Carrito/index') ?>">
            <i class="bi bi-cart3" style="font-size:1.2rem"></i>
            <?php if ($cartCount > 0): ?>
              <span class="position-absolute top-0 start-100 translate-middle badge rounded-pill bg-danger">
                <?= $cartCount ?>
              </span>
            <?php endif; ?>
          </a>
        </li>

        <!-- Si hay usuario logueado -->
        <?php if ($user): ?>
          <!-- Dropdown de usuario -->
          <li class="nav-item dropdown">
            <a class="nav-link dropdown-toggle d-flex align-items-center" href="#"
               id="userMenu" role="button" data-bs-toggle="dropdown">
              <i class="bi bi-person-circle me-1"></i>
              <?= htmlspecialchars($user['nombre_usuario']) ?>
            </a>
            <ul class="dropdown-menu dropdown-menu-end" aria-labelledby="userMenu">
              <?php if ($user['id_rol'] === 3): // Cliente ?>
                <li><a class="dropdown-item" href="<?= url('index.php?url=Pedido/misPedidos') ?>">Mis pedidos</a></li>
                <li><a class="dropdown-item" href="<?= url('index.php?url=Pedido/porPagar') ?>">Pedidos por pagar</a></li>
              <?php else: // Vendedor o Admin ?>
                <li><a class="dropdown-item" href="<?= url('index.php?url=Dashboard/index') ?>">Dashboard</a></li>
                <?php if ($user['id_rol'] === 1): // Admin extras ?>
                  <li><hr class="dropdown-divider"></li>
                  <li><h6 class="dropdown-header">Admin</h6></li>
                  <li><a class="dropdown-item" href="<?= url('index.php?url=Productos/index') ?>">Productos</a></li>
                  <li><a class="dropdown-item" href="<?= url('index.php?url=Marcas/index') ?>">Marcas</a></li>
                  <li><a class="dropdown-item" href="<?= url('index.php?url=Especialidades/index') ?>">Especialidades</a></li>
                  <li><a class="dropdown-item" href="<?= url('index.php?url=ProductoMedidas/index') ?>">Medidas de Producto</a></li>
                  <li><a class="dropdown-item" href="<?= url('index.php?url=ProductosEspecialidades/index') ?>">Productos por Especialidad</a></li>
                  <li><a class="dropdown-item" href="<?= url('index.php?url=ProductoEspecialidadMedidas/index') ?>">Medidas por Especialidad</a></li>
                  <li><a class="dropdown-item" href="<?= url('index.php?url=Clientes/index') ?>">Clientes</a></li>
                  <li><a class="dropdown-item" href="<?= url('index.php?url=Vendedores/index') ?>">Vendedores</a></li>
                  <li><hr class="dropdown-divider"></li>
                  <li><h6 class="dropdown-header">Reportes</h6></li>
                  <li><a class="dropdown-item" href="<?= url('index.php?url=Reportes/mes') ?>">Ventas por Mes</a></li>
                  <li><a class="dropdown-item" href="<?= url('index.php?url=Reportes/producto') ?>">Ventas por Producto</a></li>
                  <li><a class="dropdown-item" href="<?= url('index.php?url=Reportes/vendedor') ?>">Ventas por Vendedor</a></li>
                <?php endif; ?>
              <?php endif; ?>
              <li><hr class="dropdown-divider"></li>
              <li>
              <a class="dropdown-item" href="<?= url('index.php?url=Login/logout') ?>">
                Cerrar Sesión
              </a>
            </li>
            </ul>
          </li>

        <?php else: // Invitado ?>
          <li class="nav-item"><a class="nav-link" href="<?= url('index.php?url=Login/index') ?>">Iniciar sesión</a></li>
          <li class="nav-item"><a class="nav-link" href="<?= url('index.php?url=Auth/registro') ?>">Registrarse</a></li>
        <?php endif; ?>

      </ul>
    </div>
  </div>
</nav>

<div class="container mt-4">
  <?php
  $path = __DIR__ . '/' . $view . '.php';
  if (file_exists($path)) {
      include_once $path;
  } else {
      echo "<div class='alert alert-danger'>Error: vista <code>$view.php</code> no encontrada.</div>";
  }
  ?>
</div>
</body>
</html>
