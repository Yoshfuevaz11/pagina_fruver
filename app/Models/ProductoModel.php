<?php

namespace App\Models;

use CodeIgniter\Model;

class ProductoModel extends Model
{
    protected $table            = 'producto';
    protected $primaryKey       = 'id_producto';
    protected $useAutoIncrement = true;
    protected $returnType       = 'array';
    protected $useSoftDeletes   = false;
    protected $useTimestamps    = false;

    protected $allowedFields = [
        'nombre',
        'descripcion',
        'imagen',
        'categoria',
        'unidad_venta',
    ];

    // Reglas de validación
    protected $validationRules = [
        'nombre'       => 'required|max_length[200]',
        'categoria'    => 'required|in_list[frutas,verduras,hiervas]',
        'unidad_venta' => 'required|in_list[kilos,domos,ramos]',
        'imagen'       => 'required|max_length[50]',
    ];

    protected $validationMessages = [
        'nombre'    => ['required' => 'El nombre del producto es obligatorio.'],
        'categoria' => ['required' => 'Debes seleccionar una categoría.'],
    ];
}