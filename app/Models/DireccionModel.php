<?php

namespace App\Models;

use CodeIgniter\Model;

class DireccionModel extends Model
{
    protected $table            = 'direccion';
    protected $primaryKey       = 'id_direccion';
    protected $useAutoIncrement = true;
    protected $returnType       = 'array';
    protected $useTimestamps    = false;

    protected $allowedFields = [
        'colonia',
        'calle',
        'numero',
        'municipio',
        'estado',
        'id_cliente',
    ];

    protected $validationRules = [
        'colonia'    => 'required|max_length[100]',
        'calle'      => 'required|max_length[100]',
        'numero'     => 'required|integer',
        'municipio'  => 'required|max_length[200]',
        'estado'     => 'required|max_length[150]',
    ];
}