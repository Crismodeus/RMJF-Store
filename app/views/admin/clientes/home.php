<h1>Bienvenido, <?= htmlspecialchars($_SESSION['usuario']['nombre_usuario']) ?></h1>
<p>Explora nuestro catálogo y realiza pedidos.</p>
<a class="btn btn-primary" href="<?= url('index.php?url=Catalogo/index') ?>">Ver Productos</a>
