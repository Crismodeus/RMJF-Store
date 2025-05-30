<?php
// app/controllers/PagoController.php

class PagoController extends Controller
{
    /**
     * Muestra el modal de PayPal, creando o recargando un pedido.
     */
    public function paypal()
    {
        // Sólo Clientes (rol = 3)
        $rol = $_SESSION['usuario']['id_rol'] ?? 0;
        if ($rol !== 3) {
            header('Location: ' . url('index.php?url=Dashboard/index'));
            exit;
        }

        $usuario = $_SESSION['usuario'];
        $carrito = $_SESSION['carrito'] ?? [];
        $pm       = $this->model('Pedido');

        // ¿Viene pedido existente?
        if (!empty($_GET['pedido'])) {
            $idPedido = (int) $_GET['pedido'];
            // Método que debes tener en tu modelo:
            // public function obtenerPedidoPorIdCliente($idPedido, $idCliente): ?array
            $pedido = $pm->obtenerPedidoPorIdCliente(
                $idPedido,
                $usuario['id_usuario']
            );
            if (! $pedido) {
                header('Location: ' . url('index.php?url=Pedido/porPagar'));
                exit;
            }
            $total = $pedido['total_pedido'];

        } else {
            // Flujo desde carrito: creamos un pedido nuevo
            if (empty($carrito)) {
                header('Location: ' . url('index.php?url=Catalogo/index'));
                exit;
            }
            // 1) Calcular total
            $total = array_reduce(
                $carrito,
                fn($sum,$it) => $sum + $it['precio']*$it['cantidad'],
                0
            );
            // 2) Crear pedido (cliente + total)
            $idPedido = $pm->crearPedido(
                $usuario['id_usuario'],
                null,  // id_vendedor = null
                $total
            );
            // 3) Insertar detalles
            foreach ($carrito as $it) {
                $pm->agregarDetalle(
                    $idPedido,
                    $it['id'],      // id_producto_medida
                    $it['cantidad'],
                    $it['precio']
                );
            }
            // 4) Limpiar carrito de session
            unset($_SESSION['carrito']);
        }

        // Renderizamos el modal de PayPal
        $this->view('pago/paypal', [
            'idPedido' => $idPedido,
            'total'    => $total
        ]);
    }

    /**
     * Endpoint AJAX que PayPal invoca al capturar el pago.
     * Marca el pedido como “Pagado”.
     */
    public function confirmar()
    {
        header('Content-Type: application/json');
        $rol = $_SESSION['usuario']['id_rol'] ?? 0;
        if ($rol !== 3) {
            echo json_encode(['success' => false, 'error' => 'No autorizado']);
            exit;
        }

        $data     = json_decode(file_get_contents('php://input'), true);
        $idPedido = isset($data['pedidoId']) ? (int)$data['pedidoId'] : 0;
        if ($idPedido <= 0) {
            echo json_encode(['success' => false, 'error' => 'ID de pedido inválido']);
            exit;
        }

        try {
            $this->model('Pedido')->actualizarEstado($idPedido, 'Pagado');
            echo json_encode(['success' => true]);
        } catch (\Exception $e) {
            echo json_encode(['success' => false, 'error' => $e->getMessage()]);
        }
    }

    public function transferencia()
{
    header('Content-Type: application/json');

    // 1) Sólo Clientes (rol = 3)
    $rol = $_SESSION['usuario']['id_rol'] ?? 0;
    if ($rol !== 3) {
        echo json_encode(['success'=>false,'error'=>'No autorizado']);
        exit;
    }

    $data        = json_decode(file_get_contents('php://input'), true);
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
        // asume que tu modelo Usuario tiene obtenerPorId()
        $ven = $this->model('Usuario')->obtenerPorId($pedido['id_vendedor']);
        $destinoEmail = $ven['email_usuario'] ?? null;
    } else {
        // define en config/config.php algo como: define('ADMIN_EMAIL','admin@tusitio.com');
        $destinoEmail = ADMIN_EMAIL;
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
    $headers = "From: no-reply@tusitio.com\r\n"
             . "Content-Type: text/plain; charset=UTF-8\r\n";

    if (mail($destinoEmail, $subject, $body, $headers)) {
        echo json_encode(['success'=>true]);
    } else {
        echo json_encode(['success'=>false,'error'=>'Error enviando correo.']);
    }
    }

}
