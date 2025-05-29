<?php
// app/models/Marca.php
require_once __DIR__ . '/../core/Model.php';

class Marca extends Model {

    /**
     * Obtiene todas las marcas.
     * @return array
     */
    public function obtenerTodos(): array {
        $res = $this->db->query("
            SELECT 
              id_marca, 
              nombre_marca, 
              imagen_marca, 
              imagen_blob
            FROM marcas
            ORDER BY nombre_marca
        ");
        return $res ? $res->fetch_all(MYSQLI_ASSOC) : [];
    }

    /**
     * Obtiene una marca por ID.
     */
    public function obtenerPorId(int $id): array {
        $stmt = $this->db->prepare("
            SELECT 
              id_marca, 
              nombre_marca, 
              imagen_marca, 
              imagen_blob
            FROM marcas
            WHERE id_marca = ?
        ");
        $stmt->bind_param('i', $id);
        $stmt->execute();
        return $stmt->get_result()->fetch_assoc() ?: [];
    }

    /**
     * Crea una marca nueva.
     */
    public function crear(string $nombre, ?string $url, ?string $blob): bool {
        $sql = "
            INSERT INTO marcas
              (nombre_marca, imagen_marca, imagen_blob)
            VALUES (?, ?, ?)
        ";
        $stmt = $this->db->prepare($sql);
        $stmt->bind_param('sss', $nombre, $url, $blob);
        return $stmt->execute();
    }

    /**
     * Actualiza una marca existente.
     */
    public function actualizar(int $id, string $nombre, ?string $url, ?string $blob): bool {
        $sql = "
            UPDATE marcas
               SET nombre_marca = ?, 
                   imagen_marca = ?, 
                   imagen_blob  = ?
             WHERE id_marca = ?
        ";
        $stmt = $this->db->prepare($sql);
        $stmt->bind_param('sssi', $nombre, $url, $blob, $id);
        return $stmt->execute();
    }

    /**
     * Elimina una marca.
     */
    public function eliminar(int $id): bool {
        $stmt = $this->db->prepare("DELETE FROM marcas WHERE id_marca = ?");
        $stmt->bind_param('i', $id);
        return $stmt->execute();
    }
}
