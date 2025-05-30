<?php
class ReportesController extends Controller {
  
  public function mes() {
    // Sólo Admin (rol=1)
    if ($_SESSION['usuario']['id_rol'] !== 1) {
      header('Location: ' . url('index.php?url=Dashboard/index'));
      exit;
    }
    $mes = (int)($_GET['mes'] ?? date('m'));
    $ano = (int)($_GET['ano'] ?? date('Y'));
    $p = $this->model('Pedido');
    $datos = $p->reportePorMes($mes, $ano);
    $this->view('reportes/mes', [
      'datos' => $datos,
      'mes'   => $mes,
      'ano'   => $ano
    ]);
  }

  public function producto() {
    // Sólo Admin o Vendedor
    $rol = $_SESSION['usuario']['id_rol'];
    if (!in_array($rol, [1,2], true)) {
      header('Location: ' . url('index.php?url=Dashboard/index'));
      exit;
    }

    $mes = (int)($_GET['mes'] ?? date('m'));
    $ano = (int)($_GET['ano'] ?? date('Y'));
    // Si es vendedor, filtramos por su ID, si no (admin) usamos null
    $idVen = $rol === 2 ? $_SESSION['usuario']['id_usuario'] : null;

    $p = $this->model('Pedido');
    $datos = $p->reportePorProducto($idVen, $mes, $ano);

    $this->view('reportes/producto', [
      'datos'    => $datos,
      'mes'      => $mes,
      'ano'      => $ano,
      'rol'      => $rol
    ]);
  }

  public function especialidad() {
    // Solo Admin (1) o Vendedor (2)
    $rol = $_SESSION['usuario']['id_rol'];
    if (!in_array($rol, [1,2], true)) {
        header('Location:' . url('index.php?url=Dashboard/index'));
        exit;
    }

    $mes = (int)($_GET['mes'] ?? date('m'));
    $ano = (int)($_GET['ano'] ?? date('Y'));
    $idVen = $rol === 2 ? $_SESSION['usuario']['id_usuario'] : null;

    $p = $this->model('Pedido');
    $datos = $p->reportePorEspecialidad($idVen, $mes, $ano);

    $this->view('reportes/especialidad', [
      'datos' => $datos,
      'mes'   => $mes,
      'ano'   => $ano,
      'rol'   => $rol
    ]);
    }

    public function vendedor() {
    $rol = $_SESSION['usuario']['id_rol'];
    // Sólo Admin (1) o Vendedor (2)
    if (!in_array($rol, [1,2], true)) {
        header('Location:' . url('index.php?url=Dashboard/index'));
        exit;
    }

    $mes = (int)($_GET['mes'] ?? date('m'));
    $ano = (int)($_GET['ano'] ?? date('Y'));
    // Si es vendedor, le pasamos su propio ID, si no (admin) null
    $idVen = $rol === 2 ? $_SESSION['usuario']['id_usuario'] : null;

    $p = $this->model('Pedido');
    $datos = $p->reportePorVendedorGlobal($idVen, $mes, $ano);

    $this->view('reportes/vendedor', [
      'datos'  => $datos,
      'mes'    => $mes,
      'ano'    => $ano,
      'rol'    => $rol
    ]);
  }
}
