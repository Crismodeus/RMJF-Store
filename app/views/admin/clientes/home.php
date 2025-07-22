<h1>Bienvenido, <?= htmlspecialchars($_SESSION['usuario']['nombre_usuario']) ?></h1>
<p>Explora nuestro catálogo y realiza pedidos.</p>
<?php // app/views/home/index.php ?>
<div class="container py-5">
  <h2 class="text-center mb-4">Nuestras Especialidades</h2>
  <div class="row g-4 justify-content-center">
    <?php foreach ($especialidades as $esp): ?>
      <?php
        // El src de la imagen:
        if (!empty($esp['foto_blob'])) {
          $src = 'data:image/png;base64,' . base64_encode($esp['foto_blob']);
        } elseif (!empty($esp['foto_especialidad'])) {
          $src = filter_var($esp['foto_especialidad'], FILTER_VALIDATE_URL)
            ? $esp['foto_especialidad']
            : url('public/img/especialidades/' . $esp['foto_especialidad']);
        } else {
          // placeholder si no hay nada
          $src = url('public/img/default-especialidad.png');
        }
      ?>
      <div class="col-6 col-sm-4 col-md-3 col-lg-2 text-center">
        <a href="<?= url('index.php?url=Catalogo/index&especialidad=' . $esp['id_especialidad']) ?>"
           class="text-decoration-none text-dark">
          <img src="<?= htmlspecialchars($src) ?>"
            alt="<?= htmlspecialchars($esp['nombre_especialidad']) ?>"
            class="img-fluid rounded-circle mb-2"
            style="
            width:70px;
            height:70px;
            object-fit:contain;
            background-color:#f8f9fa;
            ">
          <div><small><?= htmlspecialchars($esp['nombre_especialidad']) ?></small></div>
        </a>
      </div>
    <?php endforeach; ?>
  </div>
</div>