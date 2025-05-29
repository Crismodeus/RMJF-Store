<?php
class DashboardController extends Controller {
    public function index() {
        // KPI’s, gráficas, etc.
        $this->view('admin/dashboard/index');
    }
}