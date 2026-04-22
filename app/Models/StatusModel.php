<?php

namespace App\Models;

use CodeIgniter\Model;

class StatusModel extends Model
{
    protected $table            = 'status';
    protected $primaryKey       = 'id_status';
    protected $useAutoIncrement = true;
    protected $returnType       = 'array';
    protected $useTimestamps    = false;

    protected $allowedFields = [
        'estado',
        'fecha',
        'id_pedido2',
    ];

    public function getHistorialDePedido(int $idPedido): array
    {
        return $this->where('id_pedido2', $idPedido)
            ->orderBy('fecha', 'DESC')
            ->findAll();
    }

    public function getUltimoStatus(int $idPedido): ?array
    {
        return $this->where('id_pedido2', $idPedido)
            ->orderBy('fecha', 'DESC')
            ->first();
    }

    public function registrarEstado(int $idPedido, string $estado): void
    {
        $this->insert([
            'estado'     => $estado,
            'fecha'      => date('Y-m-d H:i:s'),
            'id_pedido2' => $idPedido,
        ]);
    }
}