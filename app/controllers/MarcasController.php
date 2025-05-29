<?php
// app/controllers/MarcasController.php
class MarcasController extends Controller {

    public function index() {
        $marcas = $this->model('Marca')->obtenerTodos();
        $this->view('admin/marcas/index', ['marcas' => $marcas]);
    }

    public function form(int $id = null) {
        $marca = [];
        if ($id) {
            $marca = $this->model('Marca')->obtenerPorId($id);
        }
        $this->view('admin/marcas/form', [
            'marca' => $marca
        ]);
    }

    public function guardar() {
        // 1) Datos básicos
        $id     = isset($_POST['id']) ? (int)$_POST['id'] : null;
        $nombre = trim($_POST['nombre'] ?? '');

        // 2) Leer fichero
        $blob = null;
        if (!empty($_FILES['logo_file']['tmp_name']) &&
            $_FILES['logo_file']['error'] === UPLOAD_ERR_OK
        ) {
            $blob = file_get_contents($_FILES['logo_file']['tmp_name']);
        }

        // 3) Leer URL
        $url  = trim($_POST['logo_url'] ?? '') ?: null;

        // 4) Si editando y no cambian imagen/URL, conservar existentes
        if ($id && $blob === null && $url === null) {
            $ex = $this->model('Marca')->obtenerPorId($id);
            $url  = $ex['imagen_marca'] ?? null;
            $blob = $ex['imagen_blob']  ?? null;
        }

        // 5) Llamar al modelo
        $m = $this->model('Marca');
        if ($id) {
            $ok = $m->actualizar($id, $nombre, $url, $blob);
            $_SESSION['success'] = $ok ? 'Marca actualizada.' : 'Error al actualizar marca.';
        } else {
            $ok = $m->crear($nombre, $url, $blob);
            $_SESSION['success'] = $ok ? 'Marca creada.' : 'Error al crear marca.';
        }

        header('Location: ' . url('index.php?url=Marcas/index'));
        exit;
    }

    public function eliminar(int $id) {
        $ok = $this->model('Marca')->eliminar($id);
        $_SESSION['success'] = $ok ? 'Marca eliminada.' : 'Error al eliminar marca.';
        header('Location: ' . url('index.php?url=Marcas/index'));
        exit;
    }
}
