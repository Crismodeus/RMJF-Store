<?php
class App {
    protected $controller = 'HomeController';
    protected $method = 'index';
    protected $params = [];

    public function __construct() {
        $url = $this->parseUrl();

        // Controller
        $controllerName = ucfirst($url[0] ?? 'home') . 'Controller';
        $controllerFile = 'app/controllers/' . $controllerName . '.php';
        if (file_exists($controllerFile)) {
            require_once $controllerFile;
            $this->controller = $controllerName;
        } else {
            require_once 'app/controllers/HomeController.php';
        }
        $this->controller = new $this->controller;

        // Method
        if (isset($url[1]) && method_exists($this->controller, $url[1])) {
            $this->method = $url[1];
        }

        // Params
        $this->params = array_values(array_slice($url, 2));

        // Call
        call_user_func_array([$this->controller, $this->method], $this->params);
    }

    private function parseUrl(): array {
        if (isset($_GET['url'])) {
            return explode('/', filter_var(rtrim($_GET['url'], '/'), FILTER_SANITIZE_URL));
        }
        return ['home'];
    }
}
