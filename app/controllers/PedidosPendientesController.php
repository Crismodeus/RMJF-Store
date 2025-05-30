<?php
class PedidosPendientesController extends Controller
{
    public function index()
    {
        $rol = $_SESSION['usuario']['id_rol'] ?? 0;
        if (!in_array($rol,[1,2],true)) {
            header('Location:' . url('index.php?url=Dashboard/index'));
            exit;
        }
        $idVen = $rol===2 ? $_SESSION['usuario']['id_usuario'] : null;
        $pedidos = $this->model('Pedido')->obtenerPendientes($idVen);
        $this->view('admin/pedidos_pendientes/index', ['pedidos'=>$pedidos]);
    }

    public function actualizar(int $id)
    {
        $nuevoEstado = $_POST['estado'] ?? '';
        if (in_array($nuevoEstado, ['Pagado','Rechazado'], true)) {
            $this->model('Pedido')->actualizarEstado($id, $nuevoEstado);
            $_SESSION['success'] = "Pedido #{$id} marcado como {$nuevoEstado}.";
        } else {
            $_SESSION['error'] = 'Estado no válido.';
        }
        header('Location:' . url('index.php?url=PedidosPendientes/index'));
        exit;
    }

}
