<?php
// app/controllers/PagoController.php
class PagoController extends Controller {
    public function paypal() {
        // Usuario y carrito
        $usuario = $_SESSION['usuario'];
        $carrito = $_SESSION['carrito'] ?? [];

        // 1) Si vengo desde "Mis Pedidos" con pedido=ID, lo uso:
        $idPedido = isset($_GET['pedido']) ? (int)$_GET['pedido'] : null;

        // 2) Si no llega ID, creo el pedido nuevo desde carrito:
        if (! $idPedido) {
            if (empty($carrito)) {
                // nada que pagar, al catálogo
                header('Location: ' . url('index.php?url=Catalogo/index'));
                exit;
            }
            // Calcular total desde sesión
            $total = 0;
            foreach ($carrito as $it) {
                $total += $it['precio'] * $it['cantidad'];
            }

            // Crear pedido + detalles
            $pm = $this->model('Pedido');
            $idPedido = $pm->crearPedido(
                $usuario['id_usuario'], 
                null, 
                $total
            );
            foreach ($carrito as $it) {
                $pm->agregarDetalle(
                    $idPedido,
                    $it['id'],
                    $it['cantidad'],
                    $it['precio']
                );
            }
            // Limpiar carrito
            unset($_SESSION['carrito']);
        } else {
            // 3) Si venía el ID, recupero el total REAL de BD
            $pm = $this->model('Pedido');
            $datos = $pm->obtenerPedidos($usuario['id_usuario'], 'Pendiente');
            $f = array_filter($datos, fn($p)=> $p['id_pedido']==$idPedido);
            if (empty($f)) {
                // no es tu pedido, o ya pagado
                header('Location: ' . url('index.php?url=Home/index'));
                exit;
            }
            $pedidoData = array_shift($f);
            $total = $pedidoData['total_pedido'];
        }

        // 4) Renderizamos el modal PayPal
        $this->view('pago/paypal', [
            'idPedido' => $idPedido,
            'total'    => $total
        ]);
    }

    public function exito() {
        $id = (int)($_GET['id'] ?? 0);
        if ($id) {
            $this->model('Pedido')->actualizarEstado($id, 'Pagado');
        }
        header('Location: ' . url('index.php?url=Pedido/misPedidos'));
        exit;
    }

    }

    

