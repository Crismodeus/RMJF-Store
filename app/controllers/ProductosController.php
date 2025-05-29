<?php
// app/controllers/ProductosController.php
class ProductosController extends Controller {

    public function index() {
        $prodModel = $this->model('Producto');
        $productos = $prodModel->obtenerTodos();
        $this->view('admin/productos/index', ['productos' => $productos]);
    }

    public function form(int $id = null) {
        $marcaModel = $this->model('Marca');
        $marcas     = $marcaModel->obtenerTodos();

        $producto = [];
        if ($id) {
            $producto = $this->model('Producto')->obtenerPorId($id);
        }

        $this->view('admin/productos/form', [
            'marcas'   => $marcas,
            'producto' => $producto
        ]);
    }

    public function guardar() {
    // 1) Recoger inputs del formulario
        $id          = isset($_POST['id'])            ? (int) $_POST['id']        : null;
        $nombre      = trim($_POST['nombre']      ?? '');        // sí o sí string
        $descripcion = trim($_POST['descripcion'] ?? '');
        $idMarca     = isset($_POST['marca'])         ? (int) $_POST['marca']     : 0;

        // 2) Leer fichero si existe
        $imagenBin = null;
        if (
            ! empty($_FILES['imagen_file']['tmp_name']) &&
            $_FILES['imagen_file']['error'] === UPLOAD_ERR_OK
        ) {
            $imagenBin = file_get_contents($_FILES['imagen_file']['tmp_name']);
        }

        // 3) Leer URL si existe
        $imagenUrl = trim($_POST['imagen_url'] ?? '') ?: null;

        // 4) Si estamos editando y no subieron ni fichero ni URL, conservamos lo existente
        if ($id && $imagenBin === null && $imagenUrl === null) {
            $existing = $this->model('Producto')->obtenerPorId($id);
            $imagenUrl = $existing['imagen_producto'] ?? null;
            $imagenBin = $existing['imagen_blob']     ?? null;
        }

        // 5) Llamada al modelo
        $prodModel = $this->model('Producto');
        if ($id) {
            $ok = $prodModel->actualizar(
                $id,
                $nombre,
                $descripcion,
                $idMarca,
                $imagenUrl,
                $imagenBin
            );
            $_SESSION['success'] = $ok ? 'Producto actualizado.' : 'Error al actualizar.';
        } else {
            $ok = $prodModel->crear(
                $nombre,
                $descripcion,
                $idMarca,
                $imagenUrl,
                $imagenBin
            );
            $_SESSION['success'] = $ok ? 'Producto creado.' : 'Error al crear.';
        }

        // 6) Volver al listado
        header('Location: ' . url('index.php?url=Productos/index'));
        exit;
    }


    public function eliminar(int $id) {
        session_start();
        $ok = $this->model('Producto')->eliminar($id);
        $_SESSION['success'] = $ok ? 'Producto eliminado.' : 'Error al eliminar.';
        header('Location: ' . url('index.php?url=Productos/index'));
        exit;
    }

}
