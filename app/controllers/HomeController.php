<?php
class HomeController extends Controller {
    public function index() {
        // Vista: app/views/home.php
        $this->view('home');
    }
}