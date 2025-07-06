<?php
class ProductoMedidasController extends Controller {

  public function index() {
    $medidas = $this->model('ProductoMedida')->obtenerTodos();
    $this->view('admin/producto_medidas/index', ['medidas' => $medidas]);
  }

  public function form(int $id = null) {
    // necesitamos la lista de productos para el dropdown
    $productos = $this->model('Producto')->obtenerTodos();
    $medida = [];
    if ($id) {
      $medida = $this->model('ProductoMedida')->obtenerPorId($id);
    }
    $this->view('admin/producto_medidas/form', [
      'productos' => $productos,
      'medida'    => $medida
    ]);
  }

  public function guardar() {
    $id          = isset($_POST['id']) ? (int) $_POST['id'] : null;
    $id_producto = (int) ($_POST['producto'] ?? 0);
    $medida      = trim($_POST['medida']  ?? '');
    $costo       = (float)($_POST['costo']   ?? 0);
    $unidades       = (float)($_POST['unidades']   ?? 0);

    $pm = $this->model('ProductoMedida');
    if ($id) {
        // si quieres permitir cambiar de producto, extiende actualizar()
        $ok = $pm->actualizar($id, $medida, $costo, $unidades);
    } else {
        $ok = $pm->crear($id_producto, $medida, $costo, $unidades);
    }
    $_SESSION['success'] = $ok ? 'Medida guardada.' : 'Error al guardar.';
    header('Location:' . url('index.php?url=ProductoMedidas/index'));
    exit;
  }

  public function eliminar(int $id) {
    $ok = $this->model('ProductoMedida')->eliminar($id);
    $_SESSION['success'] = $ok ? 'Medida eliminada.' : 'Error al eliminar.';
    header('Location: ' . url('index.php?url=ProductoMedidas/index'));
    exit;
  }

}
