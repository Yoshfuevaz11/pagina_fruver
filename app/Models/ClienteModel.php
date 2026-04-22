<?php

namespace App\Models;

use CodeIgniter\Model;

class ClienteModel extends Model
{
    protected $table            = 'cliente';
    protected $primaryKey       = 'id_cliente';
    protected $useAutoIncrement = true;
    protected $returnType       = 'array';
    protected $useTimestamps    = false;

    protected $allowedFields = [
        'nombre',
        'ap_paterno',
        'ap_materno',
        'telefono',
    ];

    protected $validationRules = [
        'nombre'     => 'required|max_length[100]',
        'ap_paterno' => 'required|max_length[100]',
        'telefono'   => 'required|max_length[12]',
    ];

    protected $validationMessages = [
        'nombre'     => ['required' => 'El nombre es obligatorio.'],
        'ap_paterno' => ['required' => 'El apellido paterno es obligatorio.'],
        'telefono'   => ['required' => 'El teléfono es obligatorio.'],
    ];

    // Obtener cliente con su dirección (JOIN)
    public function getClientesConDireccion(): array
    {
        return $this->db->table('cliente c')
            ->select('c.*, d.colonia, d.calle, d.numero, d.municipio, d.estado, d.id_direccion')
            ->join('direccion d', 'd.id_cliente = c.id_cliente', 'left')
            ->orderBy('c.ap_paterno', 'ASC')
            ->get()
            ->getResultArray();
    }

    public function getClienteConDireccion(int $id): array
    {
        return $this->db->table('cliente c')
            ->select('c.*, d.colonia, d.calle, d.numero, d.municipio, d.estado, d.id_direccion')
            ->join('direccion d', 'd.id_cliente = c.id_cliente', 'left')
            ->where('c.id_cliente', $id)
            ->get()
            ->getRowArray() ?? [];
    }
}