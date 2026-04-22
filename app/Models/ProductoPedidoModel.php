<?php

namespace App\Models;

use CodeIgniter\Model;

class ProductoPedidoModel extends Model
{
    protected $table            = 'producto_pedido';
    protected $primaryKey       = 'id_producto_pedido';
    protected $useAutoIncrement = true;
    protected $returnType       = 'array';
    protected $useTimestamps    = false;

    protected $allowedFields = [
        'cantidad',
        'precio_venta',
        'unidad_venta',
        'total',
        'id_pedido1',
        'id_producto3',
    ];

    public function getProductosDePedido(int $idPedido): array
    {
        return $this->db->table('producto_pedido pp')
            ->select('pp.*, p.nombre as producto_nombre, p.categoria')
            ->join('producto p', 'p.id_producto = pp.id_producto3')
            ->where('pp.id_pedido1', $idPedido)
            ->get()
            ->getResultArray();
    }
}