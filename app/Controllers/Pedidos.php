<?php

namespace App\Controllers;

use App\Models\PedidoModel;
use App\Models\ProductoPedidoModel;
use App\Models\StatusModel;
use App\Models\ClienteModel;
use App\Models\RepartidorModel;
use App\Models\ProductoModel;

class Pedidos extends BaseController
{
    protected PedidoModel         $pedidoModel;
    protected ProductoPedidoModel $ppModel;
    protected StatusModel         $statusModel;
    protected ClienteModel        $clienteModel;
    protected RepartidorModel     $repartidorModel;
    protected ProductoModel       $productoModel;

    public function __construct()
    {
        $this->pedidoModel     = new PedidoModel();
        $this->ppModel         = new ProductoPedidoModel();
        $this->statusModel     = new StatusModel();
        $this->clienteModel    = new ClienteModel();
        $this->repartidorModel = new RepartidorModel();
        $this->productoModel   = new ProductoModel();
    }

    public function index(): string
    {
        return view('pedidos/index', [
            'titulo'  => 'Control de Pedidos',
            'pedidos' => $this->pedidoModel->getPedidosCompletos(),
            'estados' => PedidoModel::ESTADOS,
            'colores' => PedidoModel::COLORES,
        ]);
    }

    public function create(): string
    {
        return view('pedidos/form', [
            'titulo'       => 'Registrar Pedido',
            'clientes'     => $this->clienteModel->orderBy('ap_paterno')->findAll(),
            'repartidores' => $this->repartidorModel->orderBy('ap_paterno')->findAll(),
            'productos'    => $this->productoModel->orderBy('nombre')->findAll(),
            'accion'       => base_url('pedidos/guardar'),
        ]);
    }

    public function store()
    {
        $datosPedido = [
            'fecha'         => $this->request->getPost('fecha') ?: date('Y-m-d'),
            'id_cliente1'   => $this->request->getPost('id_cliente1'),
            'id_repartidor' => $this->request->getPost('id_repartidor'),
        ];

        if (! $this->pedidoModel->insert($datosPedido)) {
            return redirect()->to(base_url('pedidos/crear'))
                ->withInput()
                ->with('errores', $this->pedidoModel->errors());
        }

        $idPedido   = $this->pedidoModel->getInsertID();
        $productos  = $this->request->getPost('id_producto3');
        $cantidades = $this->request->getPost('cantidad');
        $precios    = $this->request->getPost('precio_venta');
        $unidades   = $this->request->getPost('unidad_venta');

        if ($productos) {
            foreach ($productos as $i => $idProd) {
                if (empty($idProd)) continue;
                $cant   = (float)($cantidades[$i] ?? 0);
                $precio = (float)($precios[$i]    ?? 0);
                $this->ppModel->insert([
                    'cantidad'     => $cant,
                    'precio_venta' => $precio,
                    'unidad_venta' => $unidades[$i] ?? 'kilos',
                    'total'        => $cant * $precio,
                    'id_pedido1'   => $idPedido,
                    'id_producto3' => $idProd,
                ]);
            }
        }

        $this->statusModel->registrarEstado($idPedido, 'pedido');

        return redirect()->to(base_url('pedidos'))
            ->with('mensaje', 'Pedido #' . $idPedido . ' registrado correctamente.');
    }

    public function show(int $id): string
    {
        $pedido = $this->pedidoModel->getPedidoCompleto($id);

        if (empty($pedido)) {
            throw \CodeIgniter\Exceptions\PageNotFoundException::forPageNotFound();
        }

        return view('pedidos/detalle', [
            'titulo'    => 'Detalle del Pedido #' . $id,
            'pedido'    => $pedido,
            'productos' => $this->ppModel->getProductosDePedido($id),
            'historial' => $this->statusModel->getHistorialDePedido($id),
            'estados'   => PedidoModel::ESTADOS,
            'colores'   => PedidoModel::COLORES,
        ]);
    }

    public function cambiarStatus(int $id)
    {
        $nuevoEstado = $this->request->getPost('estado');

        if (! array_key_exists($nuevoEstado, PedidoModel::ESTADOS)) {
            return redirect()->to(base_url('pedidos/ver/' . $id))
                ->with('error', 'Estado invalido.');
        }

        $this->statusModel->registrarEstado($id, $nuevoEstado);

        return redirect()->to(base_url('pedidos'))
            ->with('mensaje', 'Estado actualizado a: ' . PedidoModel::ESTADOS[$nuevoEstado]);
    }

    public function delete(int $id)
    {
        $this->ppModel->where('id_pedido1', $id)->delete();
        $this->statusModel->where('id_pedido2', $id)->delete();
        $this->pedidoModel->delete($id);

        return redirect()->to(base_url('pedidos'))
            ->with('mensaje', 'Pedido eliminado.');
    }
}