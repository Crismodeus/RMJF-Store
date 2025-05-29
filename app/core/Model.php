<?php
// app/core/Model.php

class Model {
    /**
     * En esta propiedad guardamos la conexión MySQLi
     */
    protected $db;

    public function __construct() {
        // Inicializas $this->db aquí, por ejemplo:
        $this->db = new mysqli(DB_HOST, DB_USER, DB_PASS, DB_NAME);
        if ($this->db->connect_error) {
            die('MySQL Connect Error: ' . $this->db->connect_error);
        }
        $this->db->set_charset('utf8mb4');
    }

    /**
     * Ejecuta una consulta preparada simple (opcional)
     */
    protected function query(string $sql) {
        return $this->db->query($sql);
    }

    /**
     * GETTER para el último error de BD.
     */
    public function getDbError(): string {
        return $this->db->error;
    }
}
