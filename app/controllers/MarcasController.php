<?php
// app/controllers/MarcasController.php
class MarcasController extends Controller {

     protected $lastError = '';

    /** Retorna el último error ocurrido */
    public function getLastError(): string {
        return $this->lastError;
    }

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

        // 2) Leer fichero (si existe) → siempre será cadena
        $blob = '';
        if (!empty($_FILES['logo_file']['tmp_name']) &&
            $_FILES['logo_file']['error'] === UPLOAD_ERR_OK
        ) {
            $blob = file_get_contents($_FILES['logo_file']['tmp_name']);
        }

        // 3) Leer URL (si existe)
        $url  = trim($_POST['logo_url'] ?? '');

        // 4) Si editando y no cambian imagen/URL, conservar existentes
        if ($id && $blob === '' && $url === '') {
            $ex = $this->model('Marca')->obtenerPorId($id);
            $url  = $ex['imagen_marca'] ?? '';
            $blob = $ex['imagen_blob']  ?? '';
        }

        // 5) Llamar al modelo
        $m = $this->model('Marca');
        if ($id) {
            $ok = $m->actualizar($id, $nombre, $url, $blob);
            $msg = $ok 
                ? 'Marca actualizada.' 
                : 'Error al actualizar marca: ' . $m->getLastError();
        } else {
            $ok = $m->crear($nombre, $url, $blob);
            $msg = $ok 
                ? 'Marca creada.' 
                : 'Error al crear marca: ' . $m->getLastError();
        }
        $_SESSION['success'] = $msg;

        // 6) Volver al listado
        header('Location: ' . url('index.php?url=Marcas/index'));
        exit;
    }

    public function eliminar(int $id) {
        $m = $this->model('Marca');
        $ok = $m->eliminar($id);
        $msg = $ok 
            ? 'Marca eliminada.' 
            : 'Error al eliminar marca: ' . $m->getLastError();
        $_SESSION['success'] = $msg;

        header('Location: ' . url('index.php?url=Marcas/index'));
        exit;
    }
}
