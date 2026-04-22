<?php

namespace App\Models;

use CodeIgniter\Model;

class MermaModel extends Model
{
    protected $table            = 'merma';
    protected $primaryKey       = 'id_merma';
    protected $useAutoIncrement = true;
    protected $returnType       = 'array';
    protected $useTimestamps    = false;

    protected $allowedFields = [
        'cantidad',
        'fecha',
        'notas',
        'id_entrada',
    ];

    protected $validationRules = [
        'cantidad'   => 'required|decimal',
        'fecha'      => 'required|valid_date',
        'notas'      => 'required|max_length[500]',
        'id_entrada' => 'required|integer',
    ];

    // Mermas con info de entrada y producto
    public function getMermasCompleto(): array
    {
        return $this->db->table('merma m')
            ->select('m.*, e.fecha as fecha_entrada, e.unidad_venta, p.nombre as producto_nombre')
            ->join('entrada e', 'e.id_entrada = m.id_entrada')
            ->join('producto p', 'p.id_producto = e.id_producto2')
            ->orderBy('m.fecha', 'DESC')
            ->get()
            ->getResultArray();
    }
}