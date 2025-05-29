<?php
// app/models/Producto.php
require_once __DIR__ . '/../core/Model.php';

class Producto extends Model {

    /**
     * Devuelve todas las especialidades.
     * @return array
     */
    public function obtenerEspecialidades(): array {
        $sql = "
            SELECT 
              id_especialidad, 
              nombre_especialidad, 
              foto_especialidad
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
        $sql = "
            SELECT 
              p.id_producto,
              p.nombre_producto,
              p.descripcion_producto,
              p.id_marca,
              m.nombre_marca,
              m.imagen_marca,
              pm.id_producto_medida,
              pm.nombre_medida,
              pm.costo_producto,
              p.imagen_producto
            FROM productos_especialidades pe
            JOIN productos p ON pe.id_producto = p.id_producto
            JOIN marcas m    ON p.id_marca = m.id_marca
            JOIN producto_medidas pm ON pm.id_producto = p.id_producto
            WHERE pe.id_especialidad = ?
        ";
        if ($idMarca) {
            $sql .= " AND p.id_marca = ?";
        }
        $stmt = $this->db->prepare($sql);
        if ($idMarca) {
            $stmt->bind_param('ii', $idEsp, $idMarca);
        } else {
            $stmt->bind_param('i', $idEsp);
        }
        $stmt->execute();
        return $stmt->get_result()->fetch_all(MYSQLI_ASSOC);
    }

        /**
     * Devuelve todos los productos (para admin).
     */
    public function obtenerTodos(): array {
        $sql = "
          SELECT
            p.id_producto,
            p.nombre_producto,
            p.descripcion_producto,
            p.id_marca,
            p.imagen_producto,      -- URL de la imagen (campo VARCHAR o LONGBLOB convertido a texto)
            p.imagen_blob,          -- BLOB real (si lo tienes)
            m.nombre_marca
          FROM productos p
          JOIN marcas m ON p.id_marca = m.id_marca
          ORDER BY p.nombre_producto
        ";
        $res = $this->db->query($sql);
        return $res ? $res->fetch_all(MYSQLI_ASSOC) : [];
    }

    /**
     * Devuelve un producto por su ID.
     */
    public function obtenerPorId(int $id): array {
        $stmt = $this->db->prepare("
          SELECT id_producto, nombre_producto, descripcion_producto, id_marca, imagen_producto
          FROM productos
          WHERE id_producto = ?
        ");
        $stmt->bind_param('i', $id);
        $stmt->execute();
        return $stmt->get_result()->fetch_assoc() ?: [];
    }

    /**
     * Crea un nuevo producto.
     */
    // ...
    public function crear(string $nombre, string $descripcion, int $idMarca, ?string $imagenUrl, ?string $imagenBin): bool
    {
        $sql = "
          INSERT INTO productos
            (nombre_producto, descripcion_producto, id_marca, imagen_producto, imagen_blob)
          VALUES (?, ?, ?, ?, ?)
        ";
        $stmt = $this->db->prepare($sql);
        if (! $stmt) {
            throw new \Exception('Prepare failed: ' . $this->db->error);
        }
        // 's','s','i','s','s' – todos strings excepto el int
        $stmt->bind_param(
          'ssiss',
          $nombre,
          $descripcion,
          $idMarca,
          $imagenUrl,
          $imagenBin  // puede ser null o cadena binaria
        );
        return $stmt->execute();
    }

    public function actualizar(int $id, string $nombre, string $descripcion, int $idMarca, ?string $imagenUrl, ?string $imagenBin): bool
    {
        $sql = "
          UPDATE productos
            SET nombre_producto    = ?,
                descripcion_producto = ?,
                id_marca            = ?,
                imagen_producto     = ?,
                imagen_blob         = ?
          WHERE id_producto = ?
        ";
        $stmt = $this->db->prepare($sql);
        if (! $stmt) {
            throw new \Exception('Prepare failed: ' . $this->db->error);
        }
        // 's','s','i','s','s','i'
        $stmt->bind_param(
          'ssissi',
          $nombre,
          $descripcion,
          $idMarca,
          $imagenUrl,
          $imagenBin,
          $id
        );
        return $stmt->execute();
    }

    /**
     * Elimina un producto.
     */
    public function eliminar(int $id): bool {
        $stmt = $this->db->prepare("DELETE FROM productos WHERE id_producto = ?");
        $stmt->bind_param('i', $id);
        return $stmt->execute();
    }
}
