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
        $id          = isset($_POST['id']) ? (int) $_POST['id'] : null;
        $nombre      = trim($_POST['nombre'] ?? '');
        $descripcion = trim($_POST['descripcion'] ?? '');
        $idMarca     = isset($_POST['marca']) ? (int) $_POST['marca'] : 0;
        $imagenBin   = null;
        if (!empty($_FILES['imagen_file']['tmp_name']) && $_FILES['imagen_file']['error'] === UPLOAD_ERR_OK) {
            $imagenBin = file_get_contents($_FILES['imagen_file']['tmp_name']);
        }
        $imagenUrl = trim($_POST['imagen'] ?? '') ?: '';
        if ($id && $imagenBin === '' && $imagenUrl === '') {
            $existing = $this->model('Producto')->obtenerPorId($id);
            $imagenUrl = $existing['imagen_producto'] ?? '';
            $imagenBin = $existing['imagen_blob']     ?? '';
        }

        $prodModel = $this->model('Producto');
        try {
            if ($id) {
                $ok = $prodModel->actualizar(
                    $id,
                    $nombre,
                    $descripcion,
                    $idMarca,
                    $imagenUrl,
                    $imagenBin
                );
                $_SESSION['success'] = $ok ? 'Producto actualizado.' : 'Error al actualizar producto: ' . $prodModel->getLastError();
            } else {
                $ok = $prodModel->crear(
                    $nombre,
                    $descripcion,
                    $idMarca,
                    $imagenUrl,
                    $imagenBin
                );
                $_SESSION['success'] = $ok ? 'Producto creado.' : 'Error al crear producto: ' . $prodModel->getLastError();
            }
        } catch (Exception $e) {
            $_SESSION['success'] = 'Error en la operación: ' . $e->getMessage();
        }

        header('Location: ' . url('index.php?url=Productos/index'));
        exit;
    }

    public function eliminar(int $id) {
        try {
            $ok = $this->model('Producto')->eliminar($id);
            $_SESSION['success'] = $ok ? 'Producto eliminado.' : 'Error al eliminar producto: ' . $this->model('Producto')->getLastError();
        } catch (Exception $e) {
            $_SESSION['success'] = 'Error en la operación: ' . $e->getMessage();
        }
        header('Location: ' . url('index.php?url=Productos/index'));
        exit;
    }

}
