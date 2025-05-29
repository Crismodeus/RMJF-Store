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
        $id     = (int) ($_POST['id']      ?? 0);           // id_producto_medida
        $prod   = trim($_POST['producto']  ?? '');
        $medida = trim($_POST['medida']    ?? '');
        $precio = (float) ($_POST['precio'] ?? 0);
        $cant   = max(1, (int) ($_POST['cantidad'] ?? 1));

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
}
