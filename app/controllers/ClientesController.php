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
        $cliente = [];
        if ($id) {
            $cliente = $this->model('Usuario')->obtenerPorId($id);
        }
        $this->view('admin/clientes/form', [
            'errors'  => $_SESSION['errors'] ?? [],
            'usuario' => $cliente
        ]);
        unset($_SESSION['errors']);
    }

    public function guardar() {
        $id       = isset($_POST['id']) ? (int)$_POST['id'] : null;
        $nombre   = trim($_POST['nombre'] ?? '');
        $email    = trim($_POST['email'] ?? '');
        $cedula   = trim($_POST['cedula'] ?? '');
        $password = $_POST['password'] ?? '';

        $errors = [];
        $usuarioModel = $this->model('Usuario');

        if ($nombre === '') {
            $errors[] = 'Debe ingresar el nombre.';
        }
        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            $errors[] = 'Correo inválido.';
        } elseif ($usuarioModel->existeEmail($email, $id)) {
            $errors[] = 'Correo ya registrado.';
        }
        if (!preg_match('/^\d{10}$/', $cedula) || !$this->validarCedulaEcuador($cedula)) {
            $errors[] = 'Cédula inválida.';
        } elseif ($usuarioModel->existeCedula($cedula, $id)) {
            $errors[] = 'Cédula ya registrada.';
        }
        if (!$id && strlen($password) < 6) {
            $errors[] = 'La contraseña debe tener al menos 6 caracteres.';
        }

        if (!empty($errors)) {
            $_SESSION['errors'] = $errors;
            header('Location: ' . url('index.php?url=Clientes/form/' . ($id ?? '')));
            exit;
        }

        if ($password !== '') {
            $hash = password_hash($password, PASSWORD_BCRYPT);
        } else {
            $hash = $usuarioModel->obtenerPorId($id)['password_usuario'] ?? '';
        }
        if ($id) {
            $ok = $usuarioModel->actualizar($id, $nombre, $email, $password, $cedula, 3);
            $_SESSION['success'] = $ok ? 'Cliente actualizado.' : 'Error al actualizar cliente.';
        } else {
            $ok = $usuarioModel->crear($nombre, $email, $password, $cedula, 3);
            $_SESSION['success'] = $ok ? 'Cliente creado.' : 'Error al crear cliente.';
        }
        header('Location:' . url('index.php?url=Clientes/index'));
        exit;
    }

    public function eliminar(int $id) {
        $ok = $this->model('Usuario')->eliminar($id);
        $_SESSION['success'] = $ok ? 'Cliente eliminado.' : 'Error al eliminar.';
        header('Location: ' . url('index.php?url=Clientes/index'));
        exit;
    }

    private function validarCedulaEcuador(string $c): bool {
        $digits = str_split($c);
        $prov = (int)$digits[0] * 10 + (int)$digits[1];
        if ($prov < 1 || $prov > 24) return false;
        $sum = 0;
        for ($i = 0; $i < 9; $i++) {
            $v = (int)$digits[$i] * (($i % 2) ? 1 : 2);
            if ($v > 9) $v -= 9;
            $sum += $v;
        }
        $digito = (10 - ($sum % 10)) % 10;
        return $digito === (int)$digits[9];
    }

}
