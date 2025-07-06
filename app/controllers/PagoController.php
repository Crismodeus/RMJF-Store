<?php
//==========================
// app/controllers/PagoController.php
//==========================
class PagoController extends Controller{
    /**
     * Muestra el modal de PayPal o de transferencia.
     */
    public function paypal()
    {
        // Sólo Clientes (rol = 3)
        $rol = $_SESSION['usuario']['id_rol'] ?? 0;
        if ($rol !== 3) {
            header('Location: ' . url('index.php?url=Home/index'));
            exit;
        }
        $user    = $_SESSION['usuario'];
        $carrito = $_SESSION['carrito'] ?? [];
        $pm      = $this->model('Pedido');

        // Si existe GET pedido, recarga; si no, crea nuevo
        if (!empty($_GET['pedido'])) {
            $idPedido = (int) $_GET['pedido'];
            $pedido   = $pm->obtenerPedidoPorIdCliente($idPedido, $user['id_usuario']);
            if (!$pedido) {
                header('Location: ' . url('index.php?url=Pedido/porPagar'));
                exit;
            }
            $total = $pedido['total_pedido'];
        } else {
            if (empty($carrito)) {
                header('Location: ' . url('index.php?url=Catalogo/index'));
                exit;
            }
            // Calcular total y crear pedido
            $total = array_reduce(
                $carrito,
                fn($sum, $item) => $sum + $item['precio'] * $item['cantidad'],
                0
            );
            $idPedido = $pm->crearPedido($user['id_usuario'], null, $total);
            foreach ($carrito as $item) {
                $pm->agregarDetalle($idPedido, $item['id'], $item['cantidad'], $item['precio']);
            }
            unset($_SESSION['carrito']);
        }

        // Renderizar vista modal PayPal
        $this->view('pago/paypal', [
            'idPedido' => $idPedido,
            'total'    => $total
        ]);
    }

    public function confirmar()
{
    header('Content-Type: application/json; charset=UTF-8');

    $rol = $_SESSION['usuario']['id_rol'] ?? 0;
    if ($rol !== 3) {
        echo json_encode(['success' => false, 'error' => 'No autorizado']);
        exit;
    }

    $data = json_decode(file_get_contents('php://input'), true);
    $idPedido = isset($data['pedidoId']) ? (int)$data['pedidoId'] : 0;
    if ($idPedido <= 0) {
        echo json_encode(['success' => false, 'error' => 'ID de pedido inválido']);
        exit;
    }

    try {
        $this->postPago($idPedido); // ✅ centraliza lógica
        echo json_encode(['success' => true]);
  
    if (! $ok) {
            throw new \Exception('No se pudo actualizar estado a Pagado.');
        }

        // // 2) Reducimos stock de cada detalle
        // $detalles = $pedidoMdl->obtenerDetalles($idPedido);
        // $pmMdl    = $this->model('ProductoMedida');
        // foreach ($detalles as $d) {
        //     // delta negativo para restar
        //     $pmMdl->actualizarStock(
        //         (int)$d['id_producto_medida'],
        //         - (int)$d['cantidad_pedido']
        //     );
        // }

        // // 3) Respondemos éxito sólo al final
        // echo json_encode(['success' => true]);
    } catch (\Exception $e) {
        echo json_encode([
            'success' => false,
            'error'   => $e->getMessage()
        ]);
    }
    }

    public function transferencia()
{
    header('Content-Type: application/json');
    echo json_encode([ 'success'=>true ]);
    exit;

    // 1) Sólo Clientes (rol = 3)
    $rol = $_SESSION['usuario']['id_rol'] ?? 0;
    if ($rol !== 3) {
        echo json_encode(['success'=>false,'error'=>'No autorizado']);
        exit;
    }

    // 2) Leer body
    $data = json_decode(file_get_contents('php://input'), true);
    $idPedido    = (int)($data['pedidoId']   ?? 0);
    $banco       = trim($data['banco']       ?? '');
    $comprobante = trim($data['comprobante'] ?? '');
    $monto       = trim($data['monto']       ?? '');

    if (!$idPedido || !$banco || !$comprobante || !$monto) {
        echo json_encode(['success'=>false,'error'=>'Todos los campos son obligatorios.']);
        exit;
    }

    // 2) Recuperar el pedido para saber si tiene vendedor asignado
    $pm     = $this->model('Pedido');
    $pedido = $pm->obtenerPedidoPorId($idPedido);
    if (!$pedido) {
        echo json_encode(['success'=>false,'error'=>'Pedido no existe.']);
        exit;
    }

    // 3) Destinatario: si id_vendedor existe → correo del vendedor; sino → admin
    $destinoEmail = null;
    if (!empty($pedido['id_vendedor'])) {
        $ven = $this->model('Usuario')->obtenerPorId($pedido['id_vendedor']);
        $destinoEmail = $ven['email_usuario'] ?? null;
    } else {
 
        $destinoEmail = 'sistemas@rmjf.ec';
    }
    if (!$destinoEmail) {
        echo json_encode(['success'=>false,'error'=>'No se encontró email destino.']);
        exit;
    }

    // 4) Enviar correo
    $subject = "Transferencia para Pedido #{$idPedido}";
    $body    = "Se ha registrado una transferencia:\n\n"
             . "Pedido: #{$idPedido}\n"
             . "Banco: {$banco}\n"
             . "Comprobante: {$comprobante}\n"
             . "Monto: {$monto}\n\n"
             . "Visita el panel para actualizar el estado.";
    $headers = "From: mercadeo3@rmjf.ec\r\n"
             . "Content-Type: text/plain; charset=UTF-8\r\n";

    if (mail($destinoEmail, $subject, $body, $headers)) {
        echo json_encode(['success'=>true]);
    } else {
        echo json_encode(['success'=>false,'error'=>'Error enviando correo.']);
    } 
    exit;
    }

    protected function postPago(int $idPedido, string $shipping = null): void
    {
        
        $pm = $this->model('Pedido');
        // 1) Marcar pedido como Pagado
        $pm->actualizarEstado($idPedido, 'Pagado');

        // 2) Descontar stock para cada detalle
        $detalles = $pm->obtenerDetalles($idPedido);
        foreach ($detalles as $d) {
            error_log("POSTPAGO: actualizarStock(id={$d['id_producto_medida']}, delta={$delta})");
            $this->model('ProductoMedida')->actualizarStock(
                (int)$d['id_producto_medida'],
                - (int)$d['cantidad_pedido']
            );
        }

        // 3) Crear entrega con estado por defecto "En Proceso"
        $this->model('Entrega')->crear(
            $idPedido,
            date('Y-m-d H:i:s'),
            $shipping,
            'En Proceso'
        );
    }
}