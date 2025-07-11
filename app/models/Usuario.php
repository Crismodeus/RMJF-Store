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
     public function crearVendedor(string $nombre, string $email, string $password, string $cedula): ?string {
        $hash = password_hash($password, PASSWORD_BCRYPT);
        $rol  = 2;
        $stmt = $this->db->prepare("
            INSERT INTO usuarios
              (nombre_usuario, email_usuario, password_usuario, cedula_usuario, id_rol)
            VALUES (?, ?, ?, ?, ?)
        ");
        $stmt->bind_param('ssssi', $nombre, $email, $hash, $cedula, $rol);
        try {
            $stmt->execute();
            return null;
        } catch (\mysqli_sql_exception $e) {
            if (str_contains($e->getMessage(), 'email_usuario')) {
                return "Error: El correo electrónico '$email' ya existe.";
            }
            if (str_contains($e->getMessage(), 'cedula_usuario')) {
                return "Error: La cédula '$cedula' ya existe.";
            }
            return "Error al crear." . $e->getMessage();
        }
    }

    /**
     * Actualiza un vendedor (rol = 2). Si $password es vacío, no lo modifica.
     * Retorna null si éxito, o un mensaje de error si falla.
     */
    public function actualizarVendedor(int $id, string $nombre, string $email, ?string $password, string $cedula): ?string {
        if ($password !== null && $password !== '') {
            $hash = password_hash($password, PASSWORD_BCRYPT);
            $sql = "
                UPDATE usuarios
                   SET nombre_usuario = ?,
                       email_usuario = ?,
                       password_usuario = ?,
                       cedula_usuario = ?
                 WHERE id_usuario = ? AND id_rol = 2
            ";
            $stmt = $this->db->prepare($sql);
            $stmt->bind_param('ssssi', $nombre, $email, $hash, $cedula, $id);
        } else {
            $sql = "
                UPDATE usuarios
                   SET nombre_usuario = ?,
                       email_usuario = ?,
                       cedula_usuario = ?
                 WHERE id_usuario = ? AND id_rol = 2
            ";
            $stmt = $this->db->prepare($sql);
            $stmt->bind_param('sssi', $nombre, $email, $cedula, $id);
        }

        try {
            $stmt->execute();
            return null;
        } catch (\mysqli_sql_exception $e) {
            if (str_contains($e->getMessage(), 'email_usuario')) {
                return "Error: El correo electrónico '$email' ya existe.";
            }
            if (str_contains($e->getMessage(), 'cedula_usuario')) {
                return "Error: La cédula '$cedula' ya existe.";
            }
            //return "Error al actualizar." . $e->getMessage();
        }
    }

    public function eliminarVendedor(int $id): bool {
        $stmt = $this->db->prepare("
            DELETE FROM usuarios
             WHERE id_usuario = ? AND id_rol = 2
        ");
        $stmt->bind_param('i', $id);
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

    public function verificarCredenciales(string $email, string $password): ?array {
        // 1) Recupera el usuario por email
        $stmt = $this->db->prepare("
            SELECT 
            id_usuario,
            nombre_usuario,
            email_usuario,
            password_usuario,
            cedula_usuario,
            id_rol
            FROM usuarios
            WHERE email_usuario = ?
            AND id_rol IN (1,2,3)      -- o los roles que quieras permitir
            LIMIT 1
        ");
        $stmt->bind_param('s', $email);
        $stmt->execute();
        $user = $stmt->get_result()->fetch_assoc();

        $stmt->close();

        // 2) Si existe y password_verify pasa, lo devolvemos (sin el hash)
        if ($user && password_verify($password, $user['password_usuario'])) {
            unset($user['password_usuario']);
            return $user;
        }

        // 3) En cualquier otro caso, null
        return null;
    }

    public function existeEmail(string $email, ?int $excludeId = null): bool {
        $sql = "SELECT 1 FROM usuarios WHERE email_usuario = ?";
        if ($excludeId) {
            $sql .= " AND id_usuario <> ?";
        }
        $sql .= " LIMIT 1";

        $stmt = $this->db->prepare($sql);
        if ($excludeId) {
            $stmt->bind_param('si', $email, $excludeId);
        } else {
            $stmt->bind_param('s', $email);
        }
        $stmt->execute();
        return (bool) $stmt->get_result()->fetch_assoc();
    }

    /**
     * ¿Existe ya esta cédula en otro usuario distinto de $excludeId?
     */
    public function existeCedula(string $cedula, ?int $excludeId = null): bool {
        $sql = "SELECT 1 FROM usuarios WHERE cedula_usuario = ?";
        if ($excludeId) {
            $sql .= " AND id_usuario <> ?";
        }
        $sql .= " LIMIT 1";

        $stmt = $this->db->prepare($sql);
        if ($excludeId) {
            $stmt->bind_param('si', $cedula, $excludeId);
        } else {
            $stmt->bind_param('s', $cedula);
        }
        $stmt->execute();
        return (bool) $stmt->get_result()->fetch_assoc();
    }
}

