<?php
// app/models/Entrega.php
require_once __DIR__ . '/../core/Model.php';
class Entrega extends Model
{
    /**
     * Registra una nueva entrega con estado inicial.
     */
    public function crear(int $idPedido, string $fecha, string $direccion, string $estado): bool
    {
        $sql = "INSERT INTO entregas (id_pedido, fecha_entrega, direccion_entrega, estado_entrega)
                  VALUES (?, ?, ?, ?)";
        $stmt = $this->db->prepare($sql);
        $stmt->bind_param('isss', $idPedido, $fecha, $direccion, $estado);
        return $stmt->execute();
    }
}