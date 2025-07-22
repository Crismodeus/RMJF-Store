<?php
// app/models/Especialidad.php
require_once __DIR__ . '/../core/Model.php';

class Especialidad extends Model {

    public function obtenerTodos(): array {
        $res = $this->db->query(
            "SELECT 
               id_especialidad, 
               nombre_especialidad, 
               foto_especialidad, 
               foto_blob
             FROM especialidades
             ORDER BY nombre_especialidad"
        );
        return $res ? $res->fetch_all(MYSQLI_ASSOC) : [];
    }

    public function obtenerPorId(int $id): array {
        $stmt = $this->db->prepare(
            "SELECT 
               id_especialidad, 
               nombre_especialidad, 
               foto_especialidad, 
               foto_blob
             FROM especialidades
             WHERE id_especialidad = ?"
        );
        $stmt->bind_param('i', $id);
        $stmt->execute();
        return $stmt->get_result()->fetch_assoc() ?: [];
    }

    public function crear(string $nombre, string $url, ?string $blob): bool {
        $sql = "
            INSERT INTO especialidades
              (nombre_especialidad, foto_especialidad, foto_blob)
            VALUES (?, ?, ?)
        ";
        $stmt = $this->db->prepare($sql);
        $stmt->bind_param('sss', $nombre, $url, $blob);
        return $stmt->execute();
    }

    public function actualizar(int $id, string $nombre, string $url, ?string $blob): bool {
        $sql = "
            UPDATE especialidades
               SET nombre_especialidad = ?,
                   foto_especialidad   = ?,
                   foto_blob           = ?
             WHERE id_especialidad = ?
        ";
        $stmt = $this->db->prepare($sql);
        $stmt->bind_param('sssi', $nombre, $url, $blob, $id);
        return $stmt->execute();
    }

    public function eliminar(int $id): bool {
        $stmt = $this->db->prepare(
            "DELETE FROM especialidades WHERE id_especialidad = ?"
        );
        $stmt->bind_param('i', $id);
        return $stmt->execute();
    }
}
