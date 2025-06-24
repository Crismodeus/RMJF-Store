<?php
// app/controllers/VendedoresController.php
class VendedoresController extends Controller {

    public function index() {
        $vendedores = $this->model('Usuario')->obtenerVendedores();
        $this->view('admin/vendedores/index', ['vendedores' => $vendedores]);
    }

    public function form(int $id = null) {
        $vendedor = [];
        if ($id) {
            $vendedor = $this->model('Usuario')->obtenerVendedorPorId($id);
        }
        $this->view('admin/vendedores/form', [
            'errors'  => $_SESSION['errors'] ?? [],
            'usuario' => $vendedor
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

        // Validaciones
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
            header('Location: ' . url('index.php?url=Vendedores/form/' . ($id ?? '')));
            exit;
        }

        // Hash o conserva existente
        if ($password !== '') {
            $hash = password_hash($password, PASSWORD_BCRYPT);
        } else {
            $hash = $usuarioModel->obtenerVendedorPorId($id)['password_usuario'] ?? '';
        }

        if ($id) {
            // Actualizar
            $ok = $usuarioModel->actualizarVendedor($id, $nombre, $email, $hash, $cedula, 2);
            $_SESSION['success'] = $ok ? 'Vendedor actualizado.' : 'Error al actualizar vendedor.';
        } else {
            // Crear
            $ok = $usuarioModel->crearVendedor($nombre, $email, $hash, $cedula, 2);
            $_SESSION['success'] = $ok ? 'Vendedor creado.' : 'Error al crear vendedor.';
        }

        header('Location: ' . url('index.php?url=Vendedores/index'));
        exit;
    }

    public function eliminar(int $id) {
        $ok = $this->model('Usuario')->eliminarVendedor($id);
        $_SESSION['success'] = $ok ? 'Vendedor eliminado.' : 'Error al eliminar vendedor.';
        header('Location: ' . url('index.php?url=Vendedores/index'));
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
