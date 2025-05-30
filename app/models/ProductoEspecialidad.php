<?php
require_once __DIR__ . '/../core/Model.php';

class ProductoEspecialidad extends Model {
  public function obtenerTodos(): array {
    $sql = "
      SELECT pe.id_producto_especialidad,
             p.nombre_producto,
             e.nombre_especialidad
        FROM productos_especialidades pe
        JOIN productos p ON pe.id_producto = p.id_producto
        JOIN especialidades e ON pe.id_especialidad = e.id_especialidad
       ORDER BY p.nombre_producto, e.nombre_especialidad
    ";
    $res = $this->db->query($sql);
    return $res ? $res->fetch_all(MYSQLI_ASSOC) : [];
  }

  public function obtenerPorProducto(int $id_producto): array {
    $stmt = $this->db->prepare("
      SELECT id_producto_especialidad, id_especialidad
        FROM productos_especialidades
       WHERE id_producto = ?
    ");
    $stmt->bind_param('i', $id_producto);
    $stmt->execute();
    return $stmt->get_result()->fetch_all(MYSQLI_ASSOC);
  }

  public function obtenerPorId(int $id): array {
    $stmt = $this->db->prepare("
      SELECT
        pe.id_producto_especialidad,
        pe.id_producto,
        pe.id_especialidad,
        p.nombre_producto,
        e.nombre_especialidad
      FROM productos_especialidades pe
      JOIN productos p ON pe.id_producto = p.id_producto
      JOIN especialidades e ON pe.id_especialidad = e.id_especialidad
      WHERE pe.id_producto_especialidad = ?
    ");
    $stmt->bind_param('i', $id);
    $stmt->execute();
    return $stmt->get_result()->fetch_assoc() ?: [];
  }


  public function crear(int $id_producto, int $id_especialidad): bool {
    $stmt = $this->db->prepare("
      INSERT INTO productos_especialidades
        (id_producto, id_especialidad)
      VALUES (?,?)
    ");
    $stmt->bind_param('ii', $id_producto, $id_especialidad);
    return $stmt->execute();
  }

  public function eliminar(int $id): bool {
    $stmt = $this->db->prepare("
      DELETE FROM productos_especialidades WHERE id_producto_especialidad = ?
    ");
    $stmt->bind_param('i', $id);
    return $stmt->execute();
  }

  public function actualizar(int $id, int $id_producto, int $id_especialidad): bool {
      $stmt = $this->db->prepare("
        UPDATE productos_especialidades
           SET id_producto    = ?,
               id_especialidad = ?
         WHERE id_producto_especialidad = ?
      ");
      $stmt->bind_param('iii', $id_producto, $id_especialidad, $id);
      return $stmt->execute();
  }
}
