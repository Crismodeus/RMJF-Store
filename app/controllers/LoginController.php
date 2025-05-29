<?php
class LoginController extends Controller {
    public function index() {
        // Muestra formulario de login
        $this->view('login');
    }

    public function autenticar() {


    // 2) Recogemos credenciales
    $email = trim($_POST['email']    ?? '');
    $pass  = $_POST['password']      ?? '';

    // 3) Llamamos al modelo para verificar
    $userModel = $this->model('Usuario');
    $usuario   = $userModel->verificarCredenciales($email, $pass);

    // 4) Comprobamos resultado y redirigimos
    if ($usuario) {
        // Guardamos los datos del usuario (sin la contraseña) en sesión
        $_SESSION['usuario'] = $usuario;
        // Y llevamos al catálogo
        header('Location: ' . url('index.php?url=Catalogo/index'));
    } else {
        // Si falla, mensaje de error y de vuelta al login
        $_SESSION['error'] = 'Email o contraseña incorrectos.';
        header('Location: ' . url('index.php?url=Login/index'));
    }
    exit;
}


    public function logout() {
        // Aquí irá la lógica de logout
        header('Location: ' . url('index.php?url=Login/index'));
    }
}