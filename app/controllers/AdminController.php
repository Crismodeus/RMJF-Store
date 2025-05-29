<?php
class UsuarioController extends Controller {
    public function index() {
        // Listado de usuarios (admin)
        $this->view('admin/usuarios/index');
    }
}