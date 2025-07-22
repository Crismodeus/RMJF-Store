<?php
// app/controllers/DashboardController.php
class DashboardController extends Controller {

    public function index() {
        $rol = $_SESSION['usuario']['id_rol'] ?? null;

        if ($rol === 3) {
            // Cliente
            $this->view('cliente/home', []);
        }
        elseif ($rol === 2) {
            // Vendedor
            // Carga totales y ventas propias
            $mesActual = date('m');
            $anoActual = date('Y');
            $vModel = $this->model('Pedido');
            $misVentas  = $vModel->getVentasPorVendedor($_SESSION['usuario']['id_usuario'], $mesActual, $anoActual);
            $this->view('dashboard/vendedor', ['ventas'=>$misVentas]);
        }
        elseif ($rol === 1) {
            // Administrador
            $mesActual = date('m');
            $anoActual = date('Y');
            $pModel = $this->model('Pedido');
            $totalesMes = $pModel->getVentasPorMes($mesActual, $anoActual);
            $this->view('dashboard/admin', ['totales'=>$totalesMes]);
        }
        else {
            // Sin rol o no logueado
            header('Location:' . url('index.php?url=Login/index'));
            exit;
        }
    }
}
