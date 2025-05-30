<?php
class ProductoEspecialidadMedidasController extends Controller {
  public function index() {
    $list = $this->model('ProductoEspecialidadMedida')->obtenerTodos();
    $this->view('admin/pem/index',['list'=>$list]);
  }

  public function form(int $id = null) {
    // para elegir primero la asociación producto↔especialidad
    $pes = $this->model('ProductoEspecialidad')->obtenerTodos();
    // y luego las medidas disponibles
    $pms = $this->model('ProductoMedida')->obtenerTodos();
    $pem = [];
    if ($id) {
      $pem = $this->model('ProductoEspecialidadMedida')->obtenerPorEspecialidad($id);
    }
    $this->view('admin/pem/form',[
      'pes'=>$pes,'pms'=>$pms,'pem'=>$pem,'editId'=>$id
    ]);
  }

  public function guardar() {
    $id_pe = (int)($_POST['pe']??0);
    $ids_pm = $_POST['medidas'] ?? [];
    $m = $this->model('ProductoEspecialidadMedida');
    // en edición, borramos todo y reinsertamos
    if (!empty($_POST['editId'])) {
      $old = $m->obtenerPorEspecialidad((int)$_POST['editId']);
      foreach($old as $o) $m->eliminar($o['id_pem']);
    }
    foreach((array)$ids_pm as $pmId) {
      $m->crear($id_pe,(int)$pmId);
    }
    $_SESSION['success']='Asociaciones medidas guardadas.';
    header('Location:'.url('index.php?url=ProductoEspecialidadMedidas/index'));
    exit;
  }

  public function eliminar(int $id) {
    $ok = $this->model('ProductoEspecialidadMedida')->eliminar($id);
    $_SESSION['success']=$ok?'Eliminado.':'Error al eliminar.';
    header('Location:'.url('index.php?url=ProductoEspecialidadMedidas/index'));
    exit;
  }
}
