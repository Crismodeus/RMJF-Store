<?php
require_once __DIR__ . '/../core/Model.php';

class ProductoMedida extends Model {
  public function obtenerTodos(): array {
    $sql = "
      SELECT pm.id_producto_medida,
             pm.id_producto,
             p.nombre_producto,
             pm.nombre_medida,
             pm.costo_producto,
             pm.unidades_producto
        FROM producto_medidas pm
        JOIN productos p ON pm.id_producto = p.id_producto
       ORDER BY p.nombre_producto, pm.nombre_medida
    ";
    $res = $this->db->query($sql);
    return $res ? $res->fetch_all(MYSQLI_ASSOC) : [];
  }

  public function obtenerPorProducto(int $id_producto): array {
    $stmt = $this->db->prepare("
      SELECT id_producto_medida, nombre_medida, costo_producto, unidades_producto
        FROM producto_medidas
       WHERE id_producto = ?
    ");
    $stmt->bind_param('i', $id_producto);
    $stmt->execute();
    return $stmt->get_result()->fetch_all(MYSQLI_ASSOC);
  }

  public function obtenerPorId(int $id): array {
    $stmt = $this->db->prepare("
      SELECT
        pm.id_producto_medida,
        pm.id_producto,
        p.nombre_producto,
        pm.nombre_medida,
        pm.costo_producto,
        pm.unidades_producto
      FROM producto_medidas pm
      JOIN productos p ON pm.id_producto = p.id_producto
      WHERE pm.id_producto_medida = ?
    ");
    $stmt->bind_param('i', $id);
    $stmt->execute();
    return $stmt->get_result()->fetch_assoc() ?: [];
  } 


  public function crear(int $id_producto, string $medida, float $costo, float $unidades): bool {
    $stmt = $this->db->prepare("
      INSERT INTO producto_medidas
        (id_producto, nombre_medida, costo_producto, unidades_producto)
      VALUES (?,?,?,?)
    ");
    $stmt->bind_param('isdd', $id_producto, $medida, $costo, $unidades);
    return $stmt->execute();
  }

  public function actualizar(int $id, string $medida, float $costo, float $unidades): bool {
    $stmt = $this->db->prepare("
      UPDATE producto_medidas
         SET nombre_medida = ?, costo_producto = ?, unidades_producto = ?
       WHERE id_producto_medida = ?
    ");
    $stmt->bind_param('sddi', $medida, $costo, $unidades, $id);
    return $stmt->execute();
  }

  public function eliminar(int $id): bool {
    $stmt = $this->db->prepare("
      DELETE FROM producto_medidas WHERE id_producto_medida = ?
    ");
    $stmt->bind_param('i', $id);
    return $stmt->execute();
  }
}
