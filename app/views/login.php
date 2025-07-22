<?php 
// login.php
// No necesitas session_start() ni include de config: ya lo hace el layout.
?>
<h2 class="text-center mb-4">🔐 Iniciar Sesión</h2>

<?php if (isset($_SESSION['error'])): ?>
  <div class="alert alert-danger"><?= htmlspecialchars($_SESSION['error']); unset($_SESSION['error']); ?></div>
<?php endif; ?>

<form action="<?= url('index.php?url=Login/autenticar') ?>" method="POST" class="w-50 mx-auto">
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
    <label for="password" class="form-label">Contraseña</label>
    <input 
      id="password" 
      type="password" 
      name="password" 
      class="form-control" 
      placeholder="••••••••" 
      required 
    >
  </div>
  <div class="d-grid">
    <button type="submit" class="btn btn-primary">Ingresar</button>
  </div>
  <div class="text-center mt-3">
    <a href="<?= url('index.php?url=Auth/recuperar') ?>">¿Olvidaste tu contraseña?</a>
  </div>
</form>
