
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
            width:90px;
            height:90px;
            object-fit:contain;
            background-color:#f8f9fa;
            ">
          <div><small><?= htmlspecialchars($esp['nombre_especialidad']) ?></small></div>
        </a>
      </div>
    <?php endforeach; ?>
  </div>
</div>

<!-- Carrusel de Marcas -->
</div>

<section class="my-5">
  <h2 class="text-center mb-4">
    Representamos las mejores marcas para el Ecuador
  </h2>

  <?php if (!empty($marcas)): ?>
    <div id="marcasCarousel" class="carousel slide" data-bs-ride="carousel">
      <div class="carousel-inner">
        <?php foreach ($marcas as $idx => $m): ?>
          <?php
            // Decide la URL de la imagen:
            if (!empty($m['imagen_marca'])) {
              $src = htmlspecialchars($m['imagen_marca']);
            } elseif (!empty($m['imagen_blob'])) {
              $src = 'data:image/png;base64,' . base64_encode($m['imagen_blob']);
            } else {
              $src = url('public/img/default-marca.png');
            }
          ?>
          <div class="carousel-item <?= $idx === 0 ? 'active' : '' ?>">
            <img src="<?= $src ?>"
                 class="d-block mx-auto"
                 style="max-height:120px; object-fit:contain;"
                 alt="<?= htmlspecialchars($m['nombre_marca']) ?>">
          </div>
        <?php endforeach; ?>
      </div>
      <button class="carousel-control-prev" type="button"
              data-bs-target="#marcasCarousel" data-bs-slide="prev"> 
        <span class="carousel-control-prev-icon"></span>
        <
      </button>
      <button class="carousel-control-next" type="button"
              data-bs-target="#marcasCarousel" data-bs-slide="next">
        <span class="carousel-control-next-icon"></span> 
      </button>
    </div>
  <?php else: ?>
    <p class="text-center">No hay marcas para mostrar.</p>
  <?php endif; ?>
</section>