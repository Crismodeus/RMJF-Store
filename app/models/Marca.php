<?php
// app/models/Marca.php
require_once __DIR__ . '/../core/Model.php';

class Marca extends Model {
    /**
     * Almacena el último error de BD
     * @var string
     */
    protected $lastError = '';

    /** Devuelve el último error ocurrido */
    public function getLastError(): string {
        return $this->lastError;
    }

    /** Obtiene todas las marcas */
    public function obtenerTodos(): array {
        $sql = "SELECT id_marca, nombre_marca, imagen_marca, imagen_blob FROM marcas ORDER BY nombre_marca";
        $res = $this->db->query($sql);
        return $res ? $res->fetch_all(MYSQLI_ASSOC) : [];
    }

    /** Obtiene una marca por ID */
    public function obtenerPorId(int $id): array {
        $stmt = $this->db->prepare(
            "SELECT id_marca, nombre_marca, imagen_marca, imagen_blob
             FROM marcas
             WHERE id_marca = ?"
        );
        if (!$stmt) {
            $this->lastError = $this->db->error;
            return [];
        }
        $stmt->bind_param('i', $id);
        $stmt->execute();
        return $stmt->get_result()->fetch_assoc() ?: [];
    }

    /** Crea una nueva marca */
    public function crear(string $nombre, ?string $url, ?string $blob): bool {
        $sql = "INSERT INTO marcas (nombre_marca, imagen_marca, imagen_blob) VALUES (?, ?, ?)";
        $stmt = $this->db->prepare($sql);
        if (!$stmt) {
            $this->lastError = $this->db->error;
            return false;
        }
        // Tratamos URL y BLOB como cadenas, BLOB debe venir codificado correctamente
        $stmt->bind_param('sss', $nombre, $url, $blob);
        if (!$stmt->execute()) {
            $this->lastError = $stmt->error;
            return false;
        }
        return true;
    }

    /** Actualiza una marca existente */
    public function actualizar(int $id, string $nombre, ?string $url, ?string $blob): bool {
        $sql = "UPDATE marcas
                SET nombre_marca = ?,
                    imagen_marca = COALESCE(?, imagen_marca),
                    imagen_blob  = COALESCE(?, imagen_blob)
                WHERE id_marca = ?";
        $stmt = $this->db->prepare($sql);
        if (!$stmt) {
            $this->lastError = $this->db->error;
            return false;
        }
        $stmt->bind_param('sssi', $nombre, $url, $blob, $id);
        if (!$stmt->execute()) {
            $this->lastError = $stmt->error;
            return false;
        }
        return true;
    }

    /** Elimina una marca */
    public function eliminar(int $id): bool {
        $stmt = $this->db->prepare("DELETE FROM marcas WHERE id_marca = ?");
        if (!$stmt) {
            $this->lastError = $this->db->error;
            return false;
        }
        $stmt->bind_param('i', $id);
        if (!$stmt->execute()) {
            $this->lastError = $stmt->error;
            return false;
        }
        return true;
    }
}
