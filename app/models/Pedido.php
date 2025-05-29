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
}
