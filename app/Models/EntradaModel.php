<?php

namespace App\Models;

use CodeIgniter\Model;

class EntradaModel extends Model
{
    protected $table            = 'entrada';
    protected $primaryKey       = 'id_entrada';
    protected $useAutoIncrement = true;
    protected $returnType       = 'array';
    protected $useTimestamps    = false;

    protected $allowedFields = [
        'fecha',
        'fecha_caducidad',
        'cantidad',
        'unidad_compra',
        'unidad_venta',
        'precio_compra',
        'id_producto2',
        'equivalente',
        'convercion',
    ];

    protected $validationRules = [
        'fecha'          => 'required|valid_date',
        'fecha_caducidad'=> 'required|valid_date',
        'cantidad'       => 'required|decimal',
        'unidad_compra'  => 'required|in_list[caja,mazo,arpilla]',
        'unidad_venta'   => 'required|in_list[kilos,domos,ramos]',
        'precio_compra'  => 'required|decimal',
        'id_producto2'   => 'required|integer',
        'equivalente'    => 'required|decimal',
    ];

    // Entradas con nombre del producto
    public function getEntradasConProducto(): array
    {
        return $this->db->table('entrada e')
            ->select('e.*, p.nombre as producto_nombre, p.categoria')
            ->join('producto p', 'p.id_producto = e.id_producto2')
            ->orderBy('e.fecha', 'DESC')
            ->get()
            ->getResultArray();
    }

    public function getEntradaConProducto(int $id): array
    {
        return $this->db->table('entrada e')
            ->select('e.*, p.nombre as producto_nombre, p.categoria')
            ->join('producto p', 'p.id_producto = e.id_producto2')
            ->where('e.id_entrada', $id)
            ->get()
            ->getRowArray() ?? [];
    }

    // Calcular conversión automática: cantidad * equivalente
    public static function calcularConvercion(float $cantidad, float $equivalente): float
    {
        return $cantidad * $equivalente;
    }
}