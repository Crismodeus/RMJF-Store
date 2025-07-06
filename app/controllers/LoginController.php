<?php
class LoginController extends Controller {
    public function index() {
        // Muestra formulario de login
        $this->view('login');
    }

    public function autenticar() {

        $email    = trim($_POST['email'] ?? '');
        $password = $_POST['password'] ?? '';

        $usuario = $this->model('Usuario')->verificarCredenciales($email, $password);

        if ($usuario) {
            // guardo usuario sin password
            $_SESSION['usuario'] = $usuario;
            header('Location: ' . url('index.php?url=Catalogo/index'));
        } else {
            $_SESSION['error'] = 'Email o contraseña incorrectos.';
            header('Location: ' . url('index.php?url=Login/index'));
        }
        exit;
    }

    public function logout()
    {
        // 1) Asegúrate de arrancar la sesión
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }

        // 2) Borra todas las variables de sesión
        $_SESSION = [];

        // 3) Si quieres ser completamente limpio, destruye la cookie de sesión
        if (ini_get("session.use_cookies")) {
            $params = session_get_cookie_params();
            setcookie(
                session_name(), '', time() - 42000,
                $params["path"], $params["domain"],
                $params["secure"], $params["httponly"]
            );
        }

        // 4) Finalmente destruye la sesión
        session_destroy();

        // 5) Redirige al login
        header('Location: ' . url('index.php?url=Login/index'));
        exit;
    }
}