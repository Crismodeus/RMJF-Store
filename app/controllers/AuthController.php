<?php
class AuthController extends Controller {
    public function registro() {
        // Muestra formulario de registro
        $this->view('auth/registro');
    }

    public function registrar() {
    // 1) Arrancamos la sesión para poder guardar mensajes de feedback


    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $nombre    = trim($_POST['nombre'] ?? '');
            $email     = trim($_POST['email'] ?? '');
            $cedula    = trim($_POST['cedula'] ?? '');
            $password  = $_POST['password'] ?? '';
            
            $errors = [];
            $usuarioModel = $this->model('Usuario');

            // 1) Validar email
            if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
                $errors[] = 'Correo electrónico inválido.';
            } elseif (!checkdnsrr(substr(strrchr($email, '@'), 1), 'MX')) {
                $errors[] = 'Dominio de correo no válido.';
            } elseif ($usuarioModel->existeEmail($email)) {
                $errors[] = 'El correo ya está registrado.';
            }

            // 2) Validar cédula ecuatoriana (10 dígitos + algoritmo)
            if (!preg_match('/^\d{10}$/', $cedula) || !$this->validarCedulaEcuador($cedula)) {
                $errors[] = 'Cédula ecuatoriana inválida.';
            } elseif ($usuarioModel->existeCedula($cedula)) {
                $errors[] = 'La cédula ya está registrada.';
            }

            // 3) Validar password (mínimo 6)
            if (strlen($password) < 6) {
                $errors[] = 'La contraseña debe tener al menos 6 caracteres.';
            }
            
            if (empty($errors)) {
                $hash = password_hash($password, PASSWORD_BCRYPT);
                $ok = $usuarioModel->crear($nombre, $email, $hash, $cedula);
                if ($ok) {
                    header('Location: ' . url('index.php?url=Login/index'));
                    exit;
                } else {
                    $errors[] = 'Error al registrar usuario.';
                }
            }

            // Renderizar la vista con errores y datos previamente ingresados
            $this->view('auth/registro', [
                'errors' => $errors,
                'data'   => compact('nombre','email','cedula')
            ]);
        } else {
            $this->view('auth/registro');
        }
    }

    /**
     * Verifica el dígito de cédula ecuatoriana
     */
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

    public function recuperar() {
        $this->view('auth/recuperar');
    }

    public function resetear() {
        $this->view('auth/resetear');
    }
}