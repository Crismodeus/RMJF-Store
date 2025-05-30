<?php
require_once __DIR__ . '/../core/Model.php';

class ProductoEspecialidadMedida extends Model {
  public function obtenerTodos(): array {
    $sql = "
      SELECT pem.id_pem,
             p.nombre_producto,
             e.nombre_especialidad,
             pm.nombre_medida
        FROM productos_especialidades_medidas pem
        JOIN productos_especialidades pe ON pem.id_producto_especialidad = pe.id_producto_especialidad
        JOIN productos p ON pe.id_producto = p.id_producto
        JOIN especialidades e ON pe.id_especialidad = e.id_especialidad
        JOIN producto_medidas pm ON pem.id_producto_medida = pm.id_producto_medida
       ORDER BY p.nombre_producto, e.nombre_especialidad, pm.nombre_medida
    ";
    $res = $this->db->query($sql);
    return $res ? $res->fetch_all(MYSQLI_ASSOC) : [];
  }

  public function obtenerPorEspecialidad(int $id_pe): array {
    $stmt = $this->db->prepare("
      SELECT id_pem, id_producto_medida
        FROM productos_especialidades_medidas
       WHERE id_producto_especialidad = ?
    ");
    $stmt->bind_param('i',$id_pe);
    $stmt->execute();
    return $stmt->get_result()->fetch_all(MYSQLI_ASSOC);
  }

  public function crear(int $id_pe, int $id_pm): bool {
    $stmt = $this->db->prepare("
      INSERT INTO productos_especialidades_medidas
        (id_producto_especialidad, id_producto_medida)
      VALUES (?,?)
    ");
    $stmt->bind_param('ii',$id_pe,$id_pm);
    return $stmt->execute();
  }

  public function eliminar(int $id): bool {
    $stmt = $this->db->prepare("
      DELETE FROM productos_especialidades_medidas WHERE id_pem = ?
    ");
    $stmt->bind_param('i',$id);
    return $stmt->execute();
  }
}
