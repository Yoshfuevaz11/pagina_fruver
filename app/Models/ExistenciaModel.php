<?php

namespace App\Models;

use CodeIgniter\Model;

class ExistenciaModel extends Model
{
    protected $table            = 'existencias';
    protected $primaryKey       = 'id_existencias';
    protected $useAutoIncrement = true;
    protected $returnType       = 'array';
    protected $useTimestamps    = false;

    protected $allowedFields = [
        'exis_total_general',
        'exis_bloqueo',
        'exis_venta',
        'fecha',
        'id_producto1',
        'exis_total_dia',
    ];

    // Existencias con nombre del producto
    public function getExistenciasConProducto(): array
    {
        return $this->db->table('existencias ex')
            ->select('ex.*, p.nombre as producto_nombre, p.categoria, p.unidad_venta')
            ->join('producto p', 'p.id_producto = ex.id_producto1')
            ->orderBy('p.categoria', 'ASC')
            ->get()
            ->getResultArray();
    }

    public function getByProducto(int $idProducto): ?array
    {
        return $this->where('id_producto1', $idProducto)->first();
    }

    // Crear existencia inicial al registrar una entrada
    public function crearOActualizar(int $idProducto, float $convercion): void
    {
        $existente = $this->getByProducto($idProducto);

        if ($existente) {
            $this->update($existente['id_existencias'], [
                'exis_total_general' => $existente['exis_total_general'] + (int)$convercion,
                'exis_total_dia'     => $existente['exis_total_dia'] + (int)$convercion,
            ]);
        } else {
            $this->insert([
                'id_producto1'       => $idProducto,
                'exis_total_general' => (int)$convercion,
                'exis_total_dia'     => (int)$convercion,
                'exis_bloqueo'       => 0,
                'exis_venta'         => 0,
                'fecha'              => date('Y-m-d H:i:s'),
            ]);
        }
    }
}