<?php
// app/models/Usuario.php
require_once __DIR__ . '/../core/Model.php';

class Usuario extends Model {

    /**
     * Devuelve todos los clientes (rol=3).
     */
    public function obtenerClientes(): array {
        $stmt = $this->db->prepare("
            SELECT 
              id_usuario,
              nombre_usuario,
              email_usuario,
              cedula_usuario
            FROM usuarios
            WHERE id_rol = 3
            ORDER BY nombre_usuario
        ");
        $stmt->execute();
        return $stmt->get_result()->fetch_all(MYSQLI_ASSOC);
    }

    /**
     * Devuelve un cliente por ID.
     */
    public function obtenerPorId(int $id): array {
        $stmt = $this->db->prepare("
            SELECT 
              id_usuario,
              nombre_usuario,
              email_usuario,
              cedula_usuario
            FROM usuarios
            WHERE id_usuario = ? AND id_rol = 3
        ");
        $stmt->bind_param('i', $id);
        $stmt->execute();
        return $stmt->get_result()->fetch_assoc() ?: [];
    }

    /**
     * Crea un nuevo cliente con contraseña hasheada.
     */
    public function crear(string $nombre, string $email, string $password, string $cedula): bool {
        $hash = password_hash($password, PASSWORD_BCRYPT);
        $rol  = 3;
        $stmt = $this->db->prepare("
            INSERT INTO usuarios 
              (nombre_usuario, email_usuario, password_usuario, cedula_usuario, id_rol)
            VALUES (?, ?, ?, ?, ?)
        ");
        $stmt->bind_param('ssssi', $nombre, $email, $hash, $cedula, $rol);
        return $stmt->execute();
    }

    /**
     * Actualiza datos del cliente (si el password viene vacío, lo deja igual).
     */
    public function actualizar(int $id, string $nombre, string $email, ?string $password, string $cedula): bool {
        if ($password !== null && $password !== '') {
            $hash = password_hash($password, PASSWORD_BCRYPT);
            $sql = "
                UPDATE usuarios
                   SET nombre_usuario   = ?,
                       email_usuario    = ?,
                       password_usuario = ?,
                       cedula_usuario   = ?
                 WHERE id_usuario = ? AND id_rol = 3
            ";
            $stmt = $this->db->prepare($sql);
            $stmt->bind_param('ssssi', $nombre, $email, $hash, $cedula, $id);
        } else {
            // sin cambiar contraseña
            $sql = "
                UPDATE usuarios
                   SET nombre_usuario = ?,
                       email_usuario  = ?,
                       cedula_usuario = ?
                 WHERE id_usuario = ? AND id_rol = 3
            ";
            $stmt = $this->db->prepare($sql);
            $stmt->bind_param('sssi', $nombre, $email, $cedula, $id);
        }
        return $stmt->execute();
    }

    /**
     * Elimina un cliente.
     */
    public function eliminar(int $id): bool {
        $stmt = $this->db->prepare("
            DELETE FROM usuarios
             WHERE id_usuario = ? AND id_rol = 3
        ");
        $stmt->bind_param('i', $id);
        return $stmt->execute();
    }


    /**
    * Crea un nuevo vendedor con contraseña hasheada.
    */
    public function crearVendedor(string $nombre, string $email, string $password, string $cedula): bool {
        $hash = password_hash($password, PASSWORD_BCRYPT);
        $rol  = 2;
        $stmt = $this->db->prepare("
            INSERT INTO usuarios
              (nombre_usuario, email_usuario, password_usuario, cedula_usuario, id_rol)
            VALUES (?, ?, ?, ?, ?)
        ");
        $stmt->bind_param('ssssi', $nombre, $email, $hash, $cedula, $rol);
        return $stmt->execute();
    }

    /**
     * Actualiza un vendedor (rol = 2). Si $password es vacío, no lo modifica.
     */
    public function actualizarVendedor(int $id, string $nombre, string $email, ?string $password, string $cedula): bool {
        if ($password !== null && $password !== '') {
            $hash = password_hash($password, PASSWORD_BCRYPT);
            $stmt = $this->db->prepare("
                UPDATE usuarios
                   SET nombre_usuario   = ?,
                       email_usuario    = ?,
                       password_usuario = ?,
                       cedula_usuario   = ?
                 WHERE id_usuario = ? AND id_rol = 2
            ");
            $stmt->bind_param('ssssi', $nombre, $email, $hash, $cedula, $id);
        } else {
            $stmt = $this->db->prepare("
                UPDATE usuarios
                   SET nombre_usuario = ?,
                       email_usuario  = ?,
                       cedula_usuario = ?
                 WHERE id_usuario = ? AND id_rol = 2
            ");
            $stmt->bind_param('sssi', $nombre, $email, $cedula, $id);
        }
        return $stmt->execute();
    }
        /**
     * Devuelve todos los vendedores (rol = 2).
     */
    public function obtenerVendedores(): array {
        $stmt = $this->db->prepare("
            SELECT 
              id_usuario,
              nombre_usuario,
              email_usuario,
              cedula_usuario
            FROM usuarios
            WHERE id_rol = 2
            ORDER BY nombre_usuario
        ");
        $stmt->execute();
        return $stmt->get_result()->fetch_all(MYSQLI_ASSOC);
    }

    /**
     * Devuelve un vendedor por ID (rol = 2).
     */
    public function obtenerVendedorPorId(int $id): array {
        $stmt = $this->db->prepare("
            SELECT 
              id_usuario,
              nombre_usuario,
              email_usuario,
              cedula_usuario
            FROM usuarios
            WHERE id_usuario = ? AND id_rol = 2
        ");
        $stmt->bind_param('i', $id);
        $stmt->execute();
        return $stmt->get_result()->fetch_assoc() ?: [];
    }
}
