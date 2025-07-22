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

    public function actualizar(int $idPedido = 0) {
        // valida rol
        $rol = $_SESSION['usuario']['id_rol'] ?? 0;
        if (!in_array($rol,[1,2],true)) {
            header('Location:' . url('index.php?url=Dashboard/index'));
            exit;
        }

        if ($idPedido <= 0) {
            $_SESSION['error'] = 'ID de pedido inválido.';
        } else {
            $pedidoMdl = $this->model('Pedido');
            // 1) Marcamos como Pagado
            $ok = $pedidoMdl->actualizarEstado($idPedido, 'Pagado');
            if ($ok) {
                // 2) Obtenemos detalles y ajustamos stock
                $detalles = $pedidoMdl->obtenerDetalles($idPedido);
                $pmMdl    = $this->model('ProductoMedida');
                foreach ($detalles as $d) {
                    // restamos unidades
                    $pmMdl->actualizarStock(
                        (int)$d['id_producto_medida'],
                        - (int)$d['cantidad_pedido']
                    );
                }
                $_SESSION['success'] = 'Pedido marcado como pagado y stock ajustado.';
            } else {
                $_SESSION['error'] = 'Error al actualizar el estado del pedido.';
            }

            }
            header('Location:' . url('index.php?url=PedidosPendientes/index'));
            exit;
    }

        

    /**
     * Marcar un pedido pendiente como pagado manualmente.
     */
    public function marcarPagado(int $id)
    {
        // Sólo Admin (1) o Vendedor (2)
        $rol = $_SESSION['usuario']['id_rol'] ?? 0;
        if (!in_array($rol, [1, 2], true)) {
            header('Location:' . url('index.php?url=Dashboard/index'));
            exit;
        }

        try {
            // Aquí reutilizamos el flujo post-pago
            $pc = new PagoController();
            $pc->postPago($id, null);
            $_SESSION['success'] = 'Pedido marcado como pagado.';
        } catch (Exception $e) {
            $_SESSION['error'] = 'Error al marcar pedido: ' . $e->getMessage();
        }

        header('Location:' . url('index.php?url=PedidosPendientes/index'));
        exit;
    }

}
