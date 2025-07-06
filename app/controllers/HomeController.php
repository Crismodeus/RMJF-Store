<?php
class HomeController extends Controller {
   public function index()
    {
        // 1) Carga todas las especialidades
        $especialidades = $this->model('Especialidad')->obtenerTodos();

        // 2) Lanza la vista, pasándole el array
        $this->view('/home', [
            'especialidades' => $especialidades
        ]);
    }
}