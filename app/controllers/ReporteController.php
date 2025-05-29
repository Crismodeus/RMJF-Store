<?php
class ReporteController extends Controller {
    public function index() {
        // Reportes de ventas
        $this->view('admin/reportes/index');
    }
}