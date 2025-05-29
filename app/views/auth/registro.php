<?php 
// auth/registro.php
?>
<h2 class="text-center mb-4">📝 Registro de Usuario</h2>

<?php if (isset($_SESSION['error'])): ?>
  <div class="alert alert-danger"><?= htmlspecialchars($_SESSION['error']); unset($_SESSION['error']); ?></div>
<?php elseif (isset($_SESSION['success'])): ?>
  <div class="alert alert-success"><?= htmlspecialchars($_SESSION['success']); unset($_SESSION['success']); ?></div>
<?php endif; ?>

<form action="<?= url('index.php?url=Auth/registrar') ?>" method="POST" class="w-50 mx-auto">
  <div class="mb-3">
    <label for="nombre" class="form-label">Nombre completo</label>
    <input 
      id="nombre" 
      type="text" 
      name="nombre" 
      class="form-control" 
      placeholder="Tu nombre completo" 
      required 
    >
  </div>
  <div class="mb-3">
    <label for="email" class="form-label">Correo electrónico</label>
    <input 
      id="email" 
      type="email" 
      name="email" 
      class="form-control" 
      placeholder="tucorreo@ejemplo.com" 
      required 
    >
  </div>
  <div class="mb-3">
    <label for="cedula" class="form-label">Cédula</label>
    <input 
      id="cedula" 
      type="text" 
      name="cedula" 
      class="form-control" 
      placeholder="Número de cédula" 
      required 
    >
  </div>
  <div class="mb-3">
    <label for="password" class="form-label">Contraseña</label>
    <input 
      id="password" 
      type="password" 
      name="password" 
      class="form-control" 
      placeholder="Crea una contraseña" 
      required 
    >
  </div>
  <div class="d-grid">
    <button type="submit" class="btn btn-success">Registrar</button>
  </div>
  <div class="text-center mt-3">
    <a href="<?= url('index.php?url=Login/index') ?>">¿Ya tienes cuenta? Inicia sesión</a>
  </div>
</form>
