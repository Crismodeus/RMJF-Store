<?php
// app/models/Producto.php
require_once __DIR__ . '/../core/Model.php';

class Producto extends Model {
    protected $lastError = '';
    /**
     * Devuelve todas las especialidades.
     * @return array
     */
    public function obtenerEspecialidades(): array {
        $sql = "
            SELECT 
              id_especialidad, 
              nombre_especialidad, 
              foto_especialidad,
              foto_blob            -- El BLOB
            FROM especialidades
            ORDER BY nombre_especialidad
        ";
        $res = $this->db->query($sql);
        return $res ? $res->fetch_all(MYSQLI_ASSOC) : [];
    }

    /**
     * Devuelve las marcas presentes dentro de una especialidad.
     * @param int $idEsp
     * @return array
     */
    public function obtenerMarcasPorEspecialidad(int $idEsp): array {
        $sql = "
            SELECT DISTINCT 
              m.id_marca, 
              m.nombre_marca, 
              m.imagen_marca
            FROM productos_especialidades pe
            JOIN productos p ON pe.id_producto = p.id_producto
            JOIN marcas m    ON p.id_marca = m.id_marca
            WHERE pe.id_especialidad = ?
            ORDER BY m.nombre_marca
        ";
        $stmt = $this->db->prepare($sql);
        $stmt->bind_param('i', $idEsp);
        $stmt->execute();
        return $stmt->get_result()->fetch_all(MYSQLI_ASSOC);
    }

    /**
     * Devuelve los productos (y sus medidas) de una especialidad,
     * opcionalmente filtrados por marca.
     * @param int $idEsp
     * @param int|null $idMarca
     * @return array
     */
    public function obtenerProductosPorEspecialidad(int $idEsp, ?int $idMarca = null): array {
    // 1) SQL corregido, incluye unidades_producto AS stock
    $sql = "
        SELECT 
          p.id_producto,
          p.nombre_producto,
          p.descripcion_producto,
          p.id_marca,
          p.imagen_producto,
          p.imagen_blob           AS imagen_blob,
          m.nombre_marca,
          m.imagen_marca,
          m.imagen_blob       AS imagen_marca_blob,
          pm.id_producto_medida,
          pm.nombre_medida,
          pm.costo_producto,
          pm.unidades_producto             AS stock
        FROM productos_especialidades pe
        JOIN productos p     ON pe.id_producto        = p.id_producto
        JOIN marcas m        ON p.id_marca            = m.id_marca
        JOIN producto_medidas pm ON pm.id_producto    = p.id_producto
        WHERE pe.id_especialidad = ?
    ";
    // 2) Si hay filtro de marca, añadimos otro placeholder
    if ($idMarca) {
        $sql .= " AND p.id_marca = ?";
    }
    $sql .= " ORDER BY p.nombre_producto, pm.nombre_medida";

    // 3) Preparamos y comprobamos errores
    $stmt = $this->db->prepare($sql);
    if (! $stmt) {
        throw new \Exception("Error en SQL (obtenerProductosPorEspecialidad): " . $this->db->error);
    }

    // 4) Ligamos parámetros según corresponda
    if ($idMarca) {
        $stmt->bind_param('ii', $idEsp, $idMarca);
    } else {
        $stmt->bind_param('i', $idEsp);
    }

    // 5) Ejecutamos y devolvemos resultado
    $stmt->execute();
    return $stmt->get_result()->fetch_all(MYSQLI_ASSOC);
    }

    public function getLastError(): string {
        return $this->lastError;
    }

    public function obtenerTodos(): array {
        $sql = "
          SELECT p.id_producto, p.nombre_producto, p.descripcion_producto, p.id_marca,
                 p.imagen_producto, p.imagen_blob, m.nombre_marca
            FROM productos p
            JOIN marcas m ON p.id_marca = m.id_marca
           ORDER BY p.nombre_producto
        ";
        $res = $this->db->query($sql);
        return $res ? $res->fetch_all(MYSQLI_ASSOC) : [];
    }

    public function obtenerPorId(int $id): array {
        $stmt = $this->db->prepare(
            "SELECT id_producto, nombre_producto, descripcion_producto,
                    id_marca, imagen_producto, imagen_blob
               FROM productos
              WHERE id_producto = ?"
        );
        if (!$stmt) throw new Exception('Prepare failed: ' . $this->db->error);
        $stmt->bind_param('i', $id);
        $stmt->execute();
        return $stmt->get_result()->fetch_assoc() ?: [];
    }

    public function crear(string $nombre, string $descripcion, int $idMarca, ?string $imagenUrl, ?string $imagenBin): bool {
        $sql = "
          INSERT INTO productos
            (nombre_producto, descripcion_producto, id_marca, imagen_producto, imagen_blob)
          VALUES (?, ?, ?, ?, ?)
        ";
        $stmt = $this->db->prepare($sql);
        if (!$stmt) {
            $this->lastError = $this->db->error;
            return false;
        }
        $stmt->bind_param('ssiss', $nombre, $descripcion, $idMarca, $imagenUrl, $imagenBin);
        $res = $stmt->execute();
        if (!$res) $this->lastError = $stmt->error;
        return $res;
    }

    public function actualizar(int $id, string $nombre, string $descripcion, int $idMarca, ?string $imagenUrl, ?string $imagenBin): bool {
        $sql = "
          UPDATE productos
             SET nombre_producto    = ?,
                 descripcion_producto = ?,
                 id_marca            = ?,
                 imagen_producto     = COALESCE(?, imagen_producto),
                 imagen_blob         = COALESCE(?, imagen_blob)
           WHERE id_producto = ?
        ";
        $stmt = $this->db->prepare($sql);
        if (!$stmt) {
            $this->lastError = $this->db->error;
            return false;
        }
        $stmt->bind_param('ssissi', $nombre, $descripcion, $idMarca, $imagenUrl, $imagenBin, $id);
        $res = $stmt->execute();
        if (!$res) $this->lastError = $stmt->error;
        return $res;
    }

    public function eliminar(int $id): bool {
        $stmt = $this->db->prepare("DELETE FROM productos WHERE id_producto = ?");
        if (!$stmt) {
            $this->lastError = $this->db->error;
            return false;
        }
        $stmt->bind_param('i', $id);
        $res = $stmt->execute();
        if (!$res) $this->lastError = $stmt->error;
        return $res;
    }
}
