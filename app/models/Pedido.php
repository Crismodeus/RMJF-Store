<?php
// app/models/Pedido.php
require_once __DIR__ . '/../core/Model.php';

class Pedido extends Model {

    /**
     * Crea un nuevo pedido y devuelve su ID.
     * @param int      $idUsuario
     * @param int|null $idVendedor   // en blanco si compra directa
     * @param float    $total
     * @return int     $idPedido
     */
    public function crearPedido(int $idUsuario, ?int $idVendedor, float $total): int {
        $sql = "
            INSERT INTO pedidos
              (id_usuario, id_vendedor, fecha_pedido, total_pedido, estado_pedido)
            VALUES (?, ?, NOW(), ?, 'Pendiente')
        ";
        $stmt = $this->db->prepare($sql);
        $stmt->bind_param('iid', $idUsuario, $idVendedor, $total);
        $stmt->execute();
        return $this->db->insert_id;
    }

    /**
     * Agrega una línea al detalle del pedido.
     * @param int $idPedido
     * @param int $idMedida
     * @param int $cantidad
     * @param float $precio
     */
    public function agregarDetalle(int $idPedido, int $idMedida, int $cantidad, float $precio): void {
        $sql = "
            INSERT INTO detalles_pedidos
              (id_pedido, id_producto_medida, cantidad_pedido, precio_pedido)
            VALUES (?, ?, ?, ?)
        ";
        $stmt = $this->db->prepare($sql);
        $stmt->bind_param('iiid', $idPedido, $idMedida, $cantidad, $precio);
        $stmt->execute();
    }

    public function actualizarEstado(int $idPedido, string $estado): bool {
        $stmt = $this->db->prepare("
            UPDATE pedidos
               SET estado_pedido = ?
             WHERE id_pedido = ?
        ");
        $stmt->bind_param('si', $estado, $idPedido);
        return $stmt->execute();
    }

    /**
     * Obtiene todos los pedidos de un usuario, filtrados por estado.
     *
     * @return array [ ['id_pedido','fecha_pedido','total_pedido'], ... ]
     */
    public function obtenerPedidos(int $idUsuario, string $estado): array {
        $stmt = $this->db->prepare("
            SELECT id_pedido, fecha_pedido, total_pedido
              FROM pedidos
             WHERE id_usuario = ?
               AND estado_pedido = ?
             ORDER BY fecha_pedido DESC
        ");
        $stmt->bind_param('is', $idUsuario, $estado);
        $stmt->execute();
        return $stmt->get_result()->fetch_all(MYSQLI_ASSOC);
    }

    /**
     * Obtiene los detalles (líneas) de un pedido.
     *
     * @return array [ ['producto','medida','cantidad','precio'], ... ]
     */
    public function obtenerDetalles(int $idPedido): array {
        $stmt = $this->db->prepare("
            SELECT 
              p.nombre_producto AS producto,
              pm.nombre_medida   AS medida,
              dp.cantidad_pedido AS cantidad,
              dp.precio_pedido   AS precio
            FROM detalles_pedidos dp
            JOIN producto_medidas pm 
              ON dp.id_producto_medida = pm.id_producto_medida
            JOIN productos p 
              ON pm.id_producto = p.id_producto
            WHERE dp.id_pedido = ?
        ");
        $stmt->bind_param('i', $idPedido);
        $stmt->execute();
        return $stmt->get_result()->fetch_all(MYSQLI_ASSOC);
    }

    public function getVentasPorVendedor(int $idVendedor, int $mes, int $ano): array {
        $stmt = $this->db->prepare("
            SELECT 
              p.id_pedido,
              u.nombre_usuario AS cliente,
              p.total_pedido,
              DATE_FORMAT(p.fecha_pedido, '%Y-%m-%d %H:%i') AS fecha_pedido
            FROM pedidos p
            JOIN usuarios u ON p.id_usuario = u.id_usuario
            WHERE p.id_vendedor = ?
              AND MONTH(p.fecha_pedido) = ?
              AND YEAR(p.fecha_pedido) = ?
            ORDER BY p.fecha_pedido DESC
        ");
        $stmt->bind_param('iii', $idVendedor, $mes, $ano);
        $stmt->execute();
        return $stmt->get_result()->fetch_all(MYSQLI_ASSOC);
    }

    /**
     * Total de ventas de un mes y año dados.
     * Devuelve ['total' => 1234.56].
     */
    public function getVentasPorMes(int $mes, int $ano): array {
        $stmt = $this->db->prepare("
            SELECT IFNULL(SUM(total_pedido),0) AS total
            FROM pedidos
            WHERE MONTH(fecha_pedido) = ?
              AND YEAR(fecha_pedido) = ?
        ");
        $stmt->bind_param('ii', $mes, $ano);
        $stmt->execute();
        $row = $stmt->get_result()->fetch_assoc();
        return $row ?: ['total'=>0];
    }

    public function reportePorMes(int $mes, int $ano): array {
    $stmt = $this->db->prepare("
      SELECT 
        MONTH(fecha_pedido) AS mes,
        YEAR(fecha_pedido)  AS ano,
        COUNT(*)            AS cantidad_pedidos,
        SUM(total_pedido)   AS total_ventas
      FROM pedidos
      WHERE MONTH(fecha_pedido) = ?
        AND YEAR(fecha_pedido)  = ?
      GROUP BY YEAR(fecha_pedido), MONTH(fecha_pedido)
    ");
    $stmt->bind_param('ii', $mes, $ano);
    $stmt->execute();
    return $stmt->get_result()->fetch_all(MYSQLI_ASSOC);
  }

  /**
     * Reporte de ventas agrupadas por producto.
     * Si $idVendedor es null, trae de todos; si no, sólo de ese vendedor.
     */
    public function reportePorProducto(?int $idVendedor, int $mes, int $ano): array {
        // Base de la consulta
        $sql = "
          SELECT 
            pm.id_producto_medida,
            p.nombre_producto,
            pm.nombre_medida,
            SUM(d.cantidad_pedido) AS total_unidades,
            SUM(d.cantidad_pedido * d.precio_pedido) AS total_ventas
          FROM pedidos pe
          JOIN detalles_pedidos d ON pe.id_pedido = d.id_pedido
          JOIN producto_medidas pm ON d.id_producto_medida = pm.id_producto_medida
          JOIN productos p       ON pm.id_producto           = p.id_producto
          WHERE MONTH(pe.fecha_pedido) = ?
            AND YEAR(pe.fecha_pedido)  = ?
        ";
        // Si pasa idVendedor, lo filtramos
        if ($idVendedor) {
            $sql .= " AND pe.id_vendedor = ?";
        }
        $sql .= "
          GROUP BY pm.id_producto_medida, p.nombre_producto, pm.nombre_medida
          ORDER BY total_ventas DESC
        ";

        if ($idVendedor) {
            $stmt = $this->db->prepare($sql);
            $stmt->bind_param('iii', $mes, $ano, $idVendedor);
        } else {
            $stmt = $this->db->prepare($sql);
            $stmt->bind_param('ii', $mes, $ano);
        }
        $stmt->execute();
        return $stmt->get_result()->fetch_all(MYSQLI_ASSOC);
    }

    public function reportePorEspecialidad(?int $idVendedor, int $mes, int $ano): array {
    $sql = "
      SELECT 
        e.id_especialidad,
        e.nombre_especialidad,
        COUNT(*)            AS cantidad_pedidos,
        SUM(d.cantidad_pedido * d.precio_pedido) AS total_ventas
      FROM pedidos pe
      JOIN detalles_pedidos d ON pe.id_pedido = d.id_pedido
      JOIN producto_medidas pm   ON d.id_producto_medida = pm.id_producto_medida
      JOIN productos_especialidades pe2 ON pm.id_producto = pe2.id_producto
      JOIN especialidades e      ON pe2.id_especialidad = e.id_especialidad
      WHERE MONTH(pe.fecha_pedido) = ?
        AND YEAR(pe.fecha_pedido)  = ?
    ";
    if ($idVendedor) {
        $sql .= " AND pe.id_vendedor = ?";
    }
    $sql .= "
      GROUP BY e.id_especialidad, e.nombre_especialidad
      ORDER BY total_ventas DESC
    ";

    if ($idVendedor) {
        $stmt = $this->db->prepare($sql);
        $stmt->bind_param('iii', $mes, $ano, $idVendedor);
    } else {
        $stmt = $this->db->prepare($sql);
        $stmt->bind_param('ii', $mes, $ano);
    }
    $stmt->execute();
    return $stmt->get_result()->fetch_all(MYSQLI_ASSOC);
  }

  public function reportePorVendedorGlobal(?int $idVendedor, int $mes, int $ano): array {
    $sql = "
      SELECT
        u.id_usuario   AS id_vendedor,
        u.nombre_usuario AS vendedor,
        COUNT(*)       AS cantidad_pedidos,
        SUM(p.total_pedido) AS total_ventas
      FROM pedidos p
      JOIN usuarios u ON p.id_vendedor = u.id_usuario
      WHERE MONTH(p.fecha_pedido) = ?
        AND YEAR(p.fecha_pedido)  = ?
    ";
    if ($idVendedor) {
        $sql .= " AND p.id_vendedor = ?";
    }
    $sql .= "
      GROUP BY u.id_usuario, u.nombre_usuario
      ORDER BY total_ventas DESC
    ";

    if ($idVendedor) {
        $stmt = $this->db->prepare($sql);
        $stmt->bind_param('iii', $mes, $ano, $idVendedor);
    } else {
        $stmt = $this->db->prepare($sql);
        $stmt->bind_param('ii', $mes, $ano);
    }
    $stmt->execute();
    return $stmt->get_result()->fetch_all(MYSQLI_ASSOC);
  }
}
