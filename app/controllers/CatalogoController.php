<?php
class CatalogoController extends Controller {
    public function index() {
        $productoModel  = $this->model('Producto');
        $especialidades = $productoModel->obtenerEspecialidades();
        $idEsp   = isset($_GET['especialidad']) ? (int) $_GET['especialidad'] : null;
        $marcas  = $idEsp ? $productoModel->obtenerMarcasPorEspecialidad($idEsp) : [];
        $idMarca = isset($_GET['marca'])         ? (int) $_GET['marca']         : null;

        $productos = [];
        if ($idEsp) {
            $productos = $productoModel->obtenerProductosPorEspecialidad($idEsp, $idMarca);
        }

        $this->view('catalogo', [
            'especialidades' => $especialidades,
            'marcas'         => $marcas,
            'productos'      => $productos,
            'filtroEsp'      => $idEsp,
            'filtroMarca'    => $idMarca,
        ]);
    }
}
