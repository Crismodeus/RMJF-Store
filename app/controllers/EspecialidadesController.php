<?php
// app/controllers/EspecialidadesController.php
class EspecialidadesController extends Controller {

    public function index() {
        $esp = $this->model('Especialidad')->obtenerTodos();
        $this->view('admin/especialidades/index', ['especialidades' => $esp]);
    }

    public function form(int $id = null) {
        $data = [];
        if ($id) {
            $data['especialidad'] = $this->model('Especialidad')->obtenerPorId($id);
        }
        $this->view('admin/especialidades/form', $data);
    }

    public function guardar() {
        // 1) Inputs
        $id     = isset($_POST['id']) ? (int) $_POST['id'] : null;
        $nombre = trim($_POST['nombre'] ?? '');

        // 2) URL (siempre string, por default '')
        $url = trim($_POST['foto_url'] ?? '');

        // 3) Blob (si suben archivo)
        $blob = null;
        if (!empty($_FILES['foto_file']['tmp_name']) &&
            $_FILES['foto_file']['error'] === UPLOAD_ERR_OK
        ) {
            $blob = file_get_contents($_FILES['foto_file']['tmp_name']);
        }

        // 4) En edición: si no cambian nada, conservo lo viejo
        if ($id && $url === '' && $blob === null) {
            $ex = $this->model('Especialidad')->obtenerPorId($id);
            $url  = $ex['foto_especialidad'] ?? '';
            $blob = $ex['foto_blob']         ?? null;
        }

        // 5) Llamada al Modelo
        $m = $this->model('Especialidad');
        if ($id) {
            $ok = $m->actualizar($id, $nombre, $url, $blob);
            $_SESSION['success'] = $ok ? 'Especialidad actualizada.' : 'Error al actualizar.';
        } else {
            $ok = $m->crear($nombre, $url, $blob);
            $_SESSION['success'] = $ok ? 'Especialidad creada.'   : 'Error al crear.';
        }

        header('Location: ' . url('index.php?url=Especialidades/index'));
        exit;
    }

    public function eliminar(int $id) {
        $ok = $this->model('Especialidad')->eliminar($id);
        $_SESSION['success'] = $ok ? 'Especialidad eliminada.' : 'Error al eliminar.';
        header('Location: ' . url('index.php?url=Especialidades/index'));
        exit;
    }
}
