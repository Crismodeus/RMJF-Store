<?php
class Controller {
    public function __construct() {
        // 1) Inicia la sesión (si aún no lo está)
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }
        

        // 2) Lista de controladores públicos que NO requieren login
        $publicControllers = [
            'LoginController',
            'AuthController'
        ];

        // 3) Si el controlador actual NO está en la lista, exige sesión
        $me = get_class($this);
        if (! in_array($me, $publicControllers, true)) {
            if (! isset($_SESSION['usuario'])) {
                // redirige al login y corta la ejecución
                header('Location: ' . url('index.php?url=Login/index'));
                exit;
            }
        }
    }

    public function view(string $view, array $data = []) {
        extract($data);
        require_once __DIR__ . '/../views/layout.php';
    }

    public function model(string $model) {
        require_once __DIR__ . '/../models/' . $model . '.php';
        return new $model();
    }
}
