<?php
class HomeController extends Controller {
   public function index()
    {
        // 1) Carga todas las especialidades
        $especialidades = $this->model('Especialidad')->obtenerTodos();

        // 2) Obtengo todas las marcas
        $marcas = $this->model('Marca')->obtenerTodos();
        
        // 3) Lanza la vista, pasándole el array
        $this->view('/home', [
            'especialidades' => $especialidades,
            'marcas'         => $marcas,
        ]);

    }
}