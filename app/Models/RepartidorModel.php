<?php

namespace App\Models;

use CodeIgniter\Model;

class RepartidorModel extends Model
{
    protected $table            = 'repartidor';
    protected $primaryKey       = 'id_repartidor';
    protected $useAutoIncrement = true;
    protected $returnType       = 'array';
    protected $useTimestamps    = false;

    protected $allowedFields = [
        'nombre',
        'ap_paterno',
        'ap_materno',
        'telefono',
        'direccion',
        'notas',
    ];

    protected $validationRules = [
        'nombre'     => 'required|max_length[100]',
        'ap_paterno' => 'required|max_length[100]',
        'ap_materno' => 'required|max_length[100]',
        'telefono'   => 'required|max_length[12]',
        'direccion'  => 'required|max_length[500]',
    ];

    protected $validationMessages = [
        'nombre'     => ['required' => 'El nombre es obligatorio.'],
        'ap_paterno' => ['required' => 'El apellido paterno es obligatorio.'],
        'telefono'   => ['required' => 'El teléfono es obligatorio.'],
        'direccion'  => ['required' => 'La dirección es obligatoria.'],
    ];

    // Contar pedidos activos por repartidor
    public function getRepartidoresConPedidos(): array
    {
        return $this->db->table('repartidor r')
            ->select('r.*, COUNT(p.id_pedido) as total_pedidos')
            ->join('pedido p', 'p.id_repartidor = r.id_repartidor', 'left')
            ->groupBy('r.id_repartidor')
            ->orderBy('r.ap_paterno', 'ASC')
            ->get()
            ->getResultArray();
    }
}