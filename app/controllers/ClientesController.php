<?php
// app/controllers/ClientesController.php
class ClientesController extends Controller {

    public function index() {
        $clientes = $this->model('Usuario')->obtenerClientes();
        $this->view('admin/clientes/index', ['clientes' => $clientes]);
    }

    /**
     * $id es opcional; si viene, cargamos para editar.
     */
    public function form(int $id = null) {
        $data = [];
        if ($id) {
            $data['cliente'] = $this->model('Usuario')->obtenerPorId($id);
        }
        $this->view('admin/clientes/form', $data);
    }

    public function guardar() {
        $id      = isset($_POST['id']) ? (int) $_POST['id'] : null;
        $nombre  = trim($_POST['nombre']  ?? '');
        $email   = trim($_POST['email']   ?? '');
        $cedula  = trim($_POST['cedula']  ?? '');
        $pass    = trim($_POST['password']?? ''); // si vacío en edición, no cambia

        $usuarioModel = $this->model('Usuario');
        if ($id) {
            $ok = $usuarioModel->actualizar($id, $nombre, $email, $pass, $cedula);
            $_SESSION['success'] = $ok ? 'Cliente actualizado.' : 'Error al actualizar.';
        } else {
            $ok = $usuarioModel->crear($nombre, $email, $pass, $cedula);
            $_SESSION['success'] = $ok ? 'Cliente creado.' : 'Error al crear.';
        }

        header('Location: ' . url('index.php?url=Clientes/index'));
        exit;
    }

    public function eliminar(int $id) {
        $ok = $this->model('Usuario')->eliminar($id);
        $_SESSION['success'] = $ok ? 'Cliente eliminado.' : 'Error al eliminar.';
        header('Location: ' . url('index.php?url=Clientes/index'));
        exit;
    }
}
