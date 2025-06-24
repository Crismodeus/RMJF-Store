<?php
class ProductosEspecialidadesController extends Controller {
    
  public function index() {
    $list = $this->model('ProductoEspecialidad')->obtenerTodos();
    $this->view('admin/producto_especialidades/index', ['list' => $list]);
  }

  public function form(int $id = null) {
    $productos      = $this->model('Producto')->obtenerTodos();
    $especialidades = $this->model('Especialidad')->obtenerTodos();
    $asoc           = $id ? $this->model('ProductoEspecialidad')->obtenerPorId($id) : [];
    $this->view('admin/producto_especialidades/form', [
      'productos'      => $productos,
      'especialidades' => $especialidades,
      'asoc'           => $asoc
    ]);
  }

  public function guardar() {
    $id      = isset($_POST['id'])           ? (int)$_POST['id'] : null;
    $id_prod = isset($_POST['producto'])     ? (int)$_POST['producto']     : 0;
    $id_esp  = isset($_POST['especialidad']) ? (int)$_POST['especialidad'] : 0;

    $m = $this->model('ProductoEspecialidad');

    if ($id) {
      $ok = $m->actualizar($id, $id_prod, $id_esp);
      $_SESSION['success'] = $ok
        ? 'Asociación actualizada.'
        : '¡El producto ya existe en la especialidad  o hubo un error!';
    } else {
      $ok = $m->crear($id_prod, $id_esp);
      $_SESSION['success'] = $ok
        ? 'Asociación creada.'
        : '¡El producto ya existe en la especialidad  o hubo un error!';
    }

    header('Location: ' . url('index.php?url=ProductosEspecialidades/index'));
    exit;
  }

  public function eliminar(int $id) {
    $ok = $this->model('ProductoEspecialidad')->eliminar($id);
    $_SESSION['success'] = $ok
      ? 'Asociación eliminada.'
      : 'Error al eliminar.';
    header('Location: ' . url('index.php?url=ProductosEspecialidades/index'));
    exit;
  }
}
