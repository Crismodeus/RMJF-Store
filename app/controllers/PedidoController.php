<?php
// app/controllers/PedidoController.php
class PedidoController extends Controller {
    
    public function confirmar() {
        // 1) Cargamos carrito y usuario
        $carrito = $_SESSION['carrito'] ?? [];
        $usuario = $_SESSION['usuario'];

        // Si no hay ítems, volvemos al catálogo
        if (empty($carrito)) {
            header('Location: ' . url('index.php?url=Catalogo/index'));
            exit;
        }

        // 2) Calculamos total
        $total = 0;
        foreach ($carrito as $item) {
            $total += $item['precio'] * $item['cantidad'];
        }

        // 3) Renderizamos la vista de confirmación
        $this->view('confirmacion', [
            'carrito' => $carrito,
            'total'   => $total
        ]);
    }

    public function procesar() {
        // 1) Usuario logueado
        $usuario = $_SESSION['usuario'];

        // 2) Carrito
        $carrito = $_SESSION['carrito'] ?? [];
        if (empty($carrito)) {
            header('Location: ' . url('index.php?url=Catalogo/index'));
            exit;
        }

        // 3) Crear pedido en BD
        $pedidoModel = $this->model('Pedido');
        $idPedido = $pedidoModel->crearPedido(
            $usuario['id_usuario'],
            null,            // si no hay vendedor
            $_POST['total']  // total enviado desde formulario
        );

        // 4) Insertar cada detalle
        foreach ($carrito as $item) {
            $pedidoModel->agregarDetalle(
                $idPedido,
                $item['id'],
                $item['cantidad'],
                $item['precio']
            );
        }

        // 5) Vaciamos carrito
        unset($_SESSION['carrito']);

        // 6) Redirigimos al pago PayPal
        header('Location: ' . url("index.php?url=Pago/paypal&pedido=/$idPedido"));
        exit;
    }

     public function misPedidos() {
        $userId = $_SESSION['usuario']['id_usuario'];
        $pedModel = $this->model('Pedido');
        $pedidos  = $pedModel->obtenerPedidos($userId, 'Pagado');

        $this->view('pedido/misPedidos', [
            'pedidos' => $pedidos
        ]);
    }

    public function verDetalles($idPedido) {
    $pedModel = $this->model('Pedido');
    $pedido   = $pedModel->obtenerPedidos($_SESSION['usuario']['id_usuario'], '%'); // no usado
    $detalles = $pedModel->obtenerDetalles((int)$idPedido);
    $this->view('pedido/detalle', [
        'idPedido' => $idPedido,
        'detalles' => $detalles
    ]);
    }

    public function nuevo() {
        // Sólo Admin (1) o Vendedor (2)
        $rol = $_SESSION['usuario']['id_rol'] ?? 0;
        if (!in_array($rol, [1,2], true)) {
            header('Location:' . url('index.php?url=Dashboard/index'));
            exit;
        }

        // Cargamos lista de clientes
        $clientes = $this->model('Usuario')->obtenerClientes();
        $this->view('pedido/nuevo', ['clientes' => $clientes]);
    }

    /**
     * Procesa el POST de nuevo(): arranca la venta.
     */
    public function iniciar() {
        $rol = $_SESSION['usuario']['id_rol'] ?? 0;
        if (!in_array($rol, [1,2], true)) {
            header('Location:' . url('index.php?url=Dashboard/index'));
            exit;
        }

        $idCliente  = (int)($_POST['cliente'] ?? 0);
        $idVendedor = $_SESSION['usuario']['id_usuario'];

        if (! $idCliente) {
            $_SESSION['error'] = 'Debes seleccionar un cliente.';
            header('Location:' . url('index.php?url=Pedido/nuevo'));
            exit;
        }
        // Guardamos en sesión
        $_SESSION['venta'] = [
            'cliente'  => $idCliente,
            'vendedor' => $idVendedor
        ];
        // Limpiamos carrito actual
        unset($_SESSION['carrito']);

        // Redirigimos al catálogo para que añada productos a la venta
        header('Location:' . url('index.php?url=Catalogo/index'));
        exit;
    }

    //Recuperar pedido por ID de Pedido
    public function obtenerPedidoPorId(int $idPedido): ?array
    {
        $stmt = $this->db->prepare("SELECT * FROM pedidos WHERE id_pedido = ?");
        $stmt->bind_param('i', $idPedido);
        $stmt->execute();
        $res = $stmt->get_result()->fetch_assoc();
        return $res ?: null;
    }

     /**
     * Lista de pedidos pendientes de pago.
     */
    public function porPagar() {
    $userId   = $_SESSION['usuario']['id_usuario'];
    $pedModel = $this->model('Pedido');

    // 1) Trae los pedidos Pendiente
    $pedidos = $pedModel->obtenerPedidos($userId, 'Pendiente');

    // 2) Para cada pedido, añade sus detalles
    foreach ($pedidos as &$p) {
        $p['detalles'] = $pedModel->obtenerDetalles((int)$p['id_pedido']);
    }
    unset($p);

    // 3) Renderiza la vista con pedidos + detalles
    $this->view('pedido/porPagar', [
        'pedidos' => $pedidos
    ]);
    }

}
