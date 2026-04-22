<?php

namespace App\Controllers;

use App\Models\PedidoModel;
use App\Models\ProductoModel;
use App\Models\ClienteModel;
use App\Models\ExistenciaModel;
use App\Models\EntradaModel;
use App\Models\MermaModel;

class Home extends BaseController
{
    public function index(): string
    {
        $pedidoModel    = new PedidoModel();
        $productoModel  = new ProductoModel();
        $clienteModel   = new ClienteModel();
        $existenciaModel = new ExistenciaModel();
        $entradaModel   = new EntradaModel();
        $mermaModel     = new MermaModel();

        // ── Conteos generales ──────────────────────────
        $totalClientes   = $clienteModel->countAll();
        $totalProductos  = $productoModel->countAll();
        $pedidos         = $pedidoModel->getPedidosCompletos();
        $totalPedidos    = count($pedidos);

        // ── Pedidos por estado ─────────────────────────
        $porEstado = array_fill_keys(array_keys(PedidoModel::ESTADOS), 0);
        foreach ($pedidos as $p) {
            $st = $p['status_actual'] ?? 'pedido';
            if (isset($porEstado[$st])) $porEstado[$st]++;
        }

        // ── Pedidos recientes (últimos 5) ──────────────
        $pedidosRecientes = array_slice($pedidos, 0, 5);

        // ── Existencias bajas (menos de 20 unidades) ───
        $existencias    = $existenciaModel->getExistenciasConProducto();
        $stockBajo      = array_filter($existencias, fn($e) =>
            ($e['exis_total_general'] - $e['exis_bloqueo']) < 20
        );

        // ── Entradas próximas a vencer (≤ 3 días) ──────
        $entradas      = $entradaModel->getEntradasConProducto();
        $porVencer     = array_filter($entradas, function($e) {
            $hoy  = new \DateTime();
            $cad  = new \DateTime($e['fecha_caducidad']);
            $diff = $hoy->diff($cad)->days;
            return $cad >= $hoy && $diff <= 3;
        });
        $vencidas      = array_filter($entradas, function($e) {
            return new \DateTime($e['fecha_caducidad']) < new \DateTime();
        });

        // ── Mermas recientes ───────────────────────────
        $mermasRecientes = array_slice($mermaModel->getMermasCompleto(), 0, 5);

        return view('home', [
            'titulo'          => 'Panel Principal',
            'totalClientes'   => $totalClientes,
            'totalProductos'  => $totalProductos,
            'totalPedidos'    => $totalPedidos,
            'porEstado'       => $porEstado,
            'pedidosRecientes'=> $pedidosRecientes,
            'stockBajo'       => $stockBajo,
            'porVencer'       => $porVencer,
            'vencidas'        => $vencidas,
            'mermasRecientes' => $mermasRecientes,
            'estados'         => PedidoModel::ESTADOS,
            'colores'         => PedidoModel::COLORES,
        ]);
    }
}