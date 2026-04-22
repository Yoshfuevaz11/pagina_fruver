<?php

namespace App\Models;

use CodeIgniter\Model;

class PedidoModel extends Model
{
    protected $table            = 'pedido';
    protected $primaryKey       = 'id_pedido';
    protected $useAutoIncrement = true;
    protected $returnType       = 'array';
    protected $useTimestamps    = false;

    protected $allowedFields = [
        'fecha',
        'id_cliente1',
        'id_repartidor',
    ];

    // Estados válidos del sistema
    public const ESTADOS = [
        'pedido'            => 'Pedido',
        'pedido_confirmado' => 'Pedido Confirmado',
        'en_transito'       => 'En Tránsito',
        'venta_confirmada'  => 'Venta Confirmada',
        'a_credito'         => 'A Crédito',
        'pagado'            => 'Pagado',
        'cancelado'         => 'Cancelado',
    ];

    // Colores para badges
    public const COLORES = [
        'pedido'            => '#757575',
        'pedido_confirmado' => '#1565c0',
        'en_transito'       => '#f57c00',
        'venta_confirmada'  => '#2e7d32',
        'a_credito'         => '#6a1b9a',
        'pagado'            => '#1b5e20',
        'cancelado'         => '#c62828',
    ];

    // Pedidos con toda la info relacionada
public function getPedidosCompletos(): array
{
    // Subconsulta para obtener SOLO el último status de cada pedido
    $db = \Config\Database::connect();

    return $db->query("
        SELECT 
            p.*,
            c.nombre, c.ap_paterno, c.ap_materno, c.telefono,
            r.nombre  AS rep_nombre,
            r.ap_paterno AS rep_ap,
            s.estado  AS status_actual,
            s.fecha   AS fecha_status
        FROM pedido p
        INNER JOIN cliente    c ON c.id_cliente    = p.id_cliente1
        INNER JOIN repartidor r ON r.id_repartidor = p.id_repartidor
        LEFT JOIN status s ON s.id_status = (
            SELECT id_status FROM status
            WHERE id_pedido2 = p.id_pedido
            ORDER BY fecha DESC
            LIMIT 1
        )
        ORDER BY p.fecha DESC
    ")->getResultArray();
}

public function getPedidoCompleto(int $id): array
{
    $db = \Config\Database::connect();

    return $db->query("
        SELECT 
            p.*,
            c.nombre, c.ap_paterno, c.ap_materno, c.telefono,
            r.nombre  AS rep_nombre,
            r.ap_paterno AS rep_ap,
            d.calle, d.numero, d.colonia, d.municipio, d.estado AS estado_dir,
            s.estado  AS status_actual
        FROM pedido p
        INNER JOIN cliente    c ON c.id_cliente    = p.id_cliente1
        INNER JOIN repartidor r ON r.id_repartidor = p.id_repartidor
        LEFT JOIN  direccion  d ON d.id_cliente    = c.id_cliente
        LEFT JOIN status s ON s.id_status = (
            SELECT id_status FROM status
            WHERE id_pedido2 = p.id_pedido
            ORDER BY fecha DESC
            LIMIT 1
        )
        WHERE p.id_pedido = $id
    ")->getRowArray() ?? [];
}
}