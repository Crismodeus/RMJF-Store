<?php
// Levantamos configuración y helper solo UNA vez
require_once __DIR__ . '/../../config/config.php';
?>
<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8">
  <title>RMJF Ecommerce</title>
  <link
        href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css"
        rel="stylesheet">
    <link 
        rel="stylesheet" 
        href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css">
    <script
        src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js">
    </script>
</head>
<body>
    <?php

        $cartCount = 0;
        if (isset($_SESSION['carrito']) && is_array($_SESSION['carrito'])) {
            foreach ($_SESSION['carrito'] as $item) {
                $cartCount += $item['cantidad'];
            }
        }
    ?>
<nav class="navbar navbar-expand-lg navbar-light bg-light">
  <div class="container">
    <!-- tu branding y menú… -->
    <a class="navbar-brand" href="<?= url('index.php?url=Home/index') ?>">RMJF</a>
    <div class="collapse navbar-collapse">
        <ul class="navbar-nav me-auto">
              <li class="nav-item">
                <a class="nav-link" href="<?= url('index.php?url=Home/index') ?>">Inicio</a>
            </li>
            <li class="nav-item">
                <a class="nav-link" href="<?= url('index.php?url=Catalogo/index') ?>">Especialidades</a>
            </li>
        </ul>
    <ul class="navbar-nav ms-auto align-items-center">
      <!-- Carrito -->
      <li class="nav-item me-3">
        <a class="nav-link position-relative" href="<?= url('index.php?url=Carrito/index') ?>">
          <i class="bi bi-cart3" style="font-size:1.2rem"></i>
          <?php if($cartCount>0): ?>
            <span 
              class="position-absolute top-0 start-100 translate-middle badge rounded-pill bg-danger"
            >
              <?= $cartCount ?>
            </span>
          <?php endif; ?>
        </a>
      </li>

      <!-- Usuario con dropdown -->
      <?php if(isset($_SESSION['usuario'])): ?>
      <li class="nav-item dropdown">
        <a 
          class="nav-link dropdown-toggle d-flex align-items-center" 
          href="#" 
          role="button" 
          data-bs-toggle="dropdown" 
          aria-expanded="false"
        >
          <i class="bi bi-person-circle me-1"></i>
          <?= htmlspecialchars($_SESSION['usuario']['nombre_usuario']) ?>
        </a>
        <ul class="dropdown-menu dropdown-menu-end">
          <li><a class="dropdown-item" href="<?= url('index.php?url=Pedido/misPedidos') ?>">Mis pedidos</a></li>
          <li><a class="dropdown-item" href="<?= url('index.php?url=Pedido/porPagar') ?>">Mis pedidos por pagar</a></li>
          <li><hr class="dropdown-divider"></li>
          <li><a class="dropdown-item" href="<?= url('index.php?url=Login/logout') ?>">Cerrar Sesión</a></li>
        </ul>
      </li>
      <?php else: ?>
        <li class="nav-item"><a class="nav-link" href="<?= url('index.php?url=Login/index') ?>">Iniciar sesión</a></li>
        <li class="nav-item"><a class="nav-link" href="<?= url('index.php?url=Auth/registro') ?>">Registrarse</a></li>
      <?php endif; ?>
    </ul>
  </div>
</nav>
  <div class="container mt-4">
    <?php
    // Carga la vista que te pase el controlador:
    // $view = 'login' → se include 'login.php'
    // $view = 'auth/registro' → se include 'auth/registro.php'
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