<?php
class AuthController extends Controller {
    public function registro() {
        // Muestra formulario de registro
        $this->view('auth/registro');
    }

    public function registrar() {
    // 1) Arrancamos la sesión para poder guardar mensajes de feedback


    // 2) Recogemos datos del formulario
    $nombre  = trim($_POST['nombre']  ?? '');
    $email   = trim($_POST['email']   ?? '');
    $cedula  = trim($_POST['cedula']  ?? '');
    $passRaw = $_POST['password']     ?? '';

    // 3) Hasheamos la contraseña
    $passHash = password_hash($passRaw, PASSWORD_DEFAULT);

    // 4) Instanciamos el modelo Usuario
    $userModel = $this->model('Usuario');

    // 5) Llamamos a registrarUsuario()
    $ok = $userModel->registrarUsuario($nombre, $email, $cedula, $passHash);

    // 6) Feedback y redirección
    if ($ok) {
        $_SESSION['success'] = 'Usuario registrado satisfactoriamente. Por favor, inicia sesión.';
        header('Location: ' . url('index.php?url=Login/index'));
    } else {
        $_SESSION['error'] = 'Error al registrar. Verifica tus datos.';
        header('Location: ' . url('index.php?url=Auth/registro'));
    }
    exit;
}


    public function recuperar() {
        $this->view('auth/recuperar');
    }

    public function resetear() {
        $this->view('auth/resetear');
    }
}