<?php
// app/controllers/VendedoresController.php
class VendedoresController extends Controller {

    public function index() {
        $vendedores = $this->model('Usuario')->obtenerVendedores();
        $this->view('admin/vendedores/index', ['vendedores' => $vendedores]);
    }

    public function form(int $id = null) {
        $data = [];
        if ($id) {
            $data['vendedor'] = $this->model('Usuario')->obtenerVendedorPorId($id);
        }
        $this->view('admin/vendedores/form', $data);
    }

    public function guardar() {
        $id      = isset($_POST['id'])      ? (int) $_POST['id']      : null;
        $nombre  = trim($_POST['nombre']    ?? '');
        $email   = trim($_POST['email']     ?? '');
        $cedula  = trim($_POST['cedula']    ?? '');
        $pass    = trim($_POST['password']  ?? ''); // si vacío en edición, no cambia

        $usuarioModel = $this->model('Usuario');

        if ($id) {
            // editar vendedor
            $ok = $usuarioModel->actualizarVendedor(
                $id, $nombre, $email, $pass, $cedula
            );
            $_SESSION['success'] = $ok ? 'Vendedor actualizado.' : 'Error al actualizar.';
        } else {
            // crear vendedor
            $ok = $usuarioModel->crearVendedor(
                $nombre, $email, $pass, $cedula
            );
            $_SESSION['success'] = $ok ? 'Vendedor creado.' : 'Error al crear.';
        }

        header('Location: ' . url('index.php?url=Vendedores/index'));
        exit;
    }

    public function eliminar(int $id) {
        $ok = $this->model('Usuario')->eliminar($id);
        $_SESSION['success'] = $ok ? 'Vendedor eliminado.' : 'Error al eliminar.';
        header('Location: ' . url('index.php?url=Vendedores/index'));
        exit;
    }
}
