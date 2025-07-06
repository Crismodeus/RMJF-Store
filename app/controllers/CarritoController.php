<?php
// app/controllers/CarritoController.php
class CarritoController extends Controller {
    public function index() {
        // La sesión ya arrancó en Controller::__construct()
        $carrito = $_SESSION['carrito'] ?? [];
        // Cálculo de totales
        $total = 0;
        foreach ($carrito as $item) {
            $total += $item['precio'] * $item['cantidad'];
        }
        // Pasamos a la vista
        $this->view('carrito', [
            'carrito' => $carrito,
            'total'   => $total
        ]);
    }

    public function agregar() {
        // Recogemos datos POST

        $id          = (int) ($_POST['id']         ?? 0); // id_producto_medida
        $prodId      = (int) ($_POST['product_id'] ?? 0); // identificador de producto
        $prod   = trim($_POST['producto']  ?? '');
        $medida = trim($_POST['medida']    ?? '');
        $precio = (float) ($_POST['precio'] ?? 0);
        $cant   = max(1, (int) ($_POST['cantidad'] ?? 1));
        // 👉 VALIDACIÓN STOCK 
        $pmModel = $this->model('ProductoMedida');
        $medidas = $pmModel->obtenerPorProducto($prodId);
        $stock   = 0;
        foreach ($medidas as $m) {
          if ((int)$m['id_producto_medida'] === $id) {
            $stock = (int)$m['unidades_producto'];
            break;
          } 
        }
        if ($cant > $stock) {
          $_SESSION['error'] = "No hay suficientes unidades. Sólo quedan {$stock}.";
          header('Location: ' . url('index.php?url=Catalogo/index'));
          exit;
        }
        if ($cant > 10000) {
          $_SESSION['error'] = 'No puedes pedir más de 10 000 unidades.';
          header('Location: ' . url('index.php?url=Catalogo/index'));
          exit;
        }
        // Inicializamos carrito si no existe
        if (! isset($_SESSION['carrito'])) {
            $_SESSION['carrito'] = [];
        }
        $carrito = &$_SESSION['carrito'];

        // Si ya existe ese id, sumamos cantidad
        if (isset($carrito[$id])) {
            $carrito[$id]['cantidad'] += $cant;
        } else {
            // Nuevo ítem
            $carrito[$id] = [
                'id'       => $id,
                'producto' => $prod,
                'medida'   => $medida,
                'precio'   => $precio,
                'cantidad' => $cant
            ];
        }

        // Mensaje flash y redirigir al carrito
        $_SESSION['success'] = "Se agregaron {$cant} unidad(es) de «{$prod}» al carrito.";
        header('Location: ' . url('index.php?url=Carrito/index'));
        exit;
    }

    public function eliminar($id) {
        $id = (int) $id;
        if (isset($_SESSION['carrito'][$id])) {
            unset($_SESSION['carrito'][$id]);
            $_SESSION['success'] = "Artículo eliminado del carrito.";
        }
        header('Location: ' . url('index.php?url=Carrito/index'));
        exit;
    }

    //Funciones para Pedido Vendedores/Admin

    public function registrar() {
    header('Content-Type: application/json');

     $rol = $_SESSION['usuario']['id_rol'] ?? 0;
    if (!in_array($rol, [1,2], true)) {
        header('Content-Type: application/json');
        echo json_encode([
          'success' => false,
          'error'   => 'No tienes permiso para registrar ventas directamente.'
        ]);
        exit;
    }
    // 1) Client ID y (opcional) vendedor si venimos de Dashboard
    $usuario    = $_SESSION['usuario'] ?? null;
    $venta      = $_SESSION['venta']   ?? null;
    $idCliente  = $venta['cliente']  ?? $usuario['id_usuario'];
    $idVendedor = $venta['vendedor'] ?? null;

    // 2) Carrito en sesión
    $carrito = $_SESSION['carrito'] ?? [];
    if (empty($carrito)) {
      echo json_encode(['success'=>false,'error'=>'El carrito está vacío.']);
      return;
    }

    // 3) Calcular total
    $total = array_reduce($carrito, fn($sum,$itm)=> $sum + $itm['precio']*$itm['cantidad'], 0);

    try {
      $pm = $this->model('Pedido');
      // crea pedido (cliente, vendedor, total)
      $idPedido = $pm->crearPedido($idCliente, $idVendedor, $total);

      // agrega detalles
      foreach ($carrito as $it) {
        $pm->agregarDetalle(
          $idPedido,
          $it['id'],        // id_producto_medida
          $it['cantidad'],
          $it['precio']
        );
      }

      // limpiamos contexto de venta
      unset($_SESSION['carrito'], $_SESSION['venta']);

      echo json_encode(['success'=>true,'idPedido'=>$idPedido]);
    } catch (Exception $e) {
      echo json_encode(['success'=>false,'error'=>$e->getMessage()]);
    }
  }
}
