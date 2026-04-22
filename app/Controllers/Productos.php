<?php

namespace App\Controllers;

use App\Models\ProductoModel;

class Productos extends BaseController
{
    protected ProductoModel $productoModel;

    public function __construct()
    {
        $this->productoModel = new ProductoModel();
    }

    // ------------------------------------------------------------------
    // INDEX — Listado de todos los productos
    // ------------------------------------------------------------------
    public function index(): string
    {
        $data = [
            'titulo'    => 'Catálogo de Productos',
            'productos' => $this->productoModel->orderBy('categoria', 'ASC')->findAll(),
        ];

        return view('productos/index', $data);
    }

    // ------------------------------------------------------------------
    // CREATE — Mostrar formulario de alta
    // ------------------------------------------------------------------
    public function create(): string
{
    $clienteModel    = new \App\Models\ClienteModel();
    $repartidorModel = new \App\Models\RepartidorModel();

    $data = [
        'titulo'       => 'Registrar Pedido',
        'producto'     => null,
        'accion'       => base_url('productos/guardar'),

        'clientes'     => $clienteModel->findAll(),
        'repartidores' => $repartidorModel->findAll(),
        'productos'    => $this->productoModel->findAll(), // Para el autocomplete de la vista
    ];

    return view('productos/form', $data);
}

    // ------------------------------------------------------------------
    // STORE — Guardar nuevo producto (POST)
    // ------------------------------------------------------------------
    public function store()
    {
        $datos = [
            'nombre'       => $this->request->getPost('nombre'),
            'descripcion'  => $this->request->getPost('descripcion'),
            'imagen'       => $this->request->getPost('imagen'),
            'categoria'    => $this->request->getPost('categoria'),
            'unidad_venta' => $this->request->getPost('unidad_venta'),
        ];

        if (! $this->productoModel->insert($datos)) {
            // Si hay errores de validación, regresa al formulario con errores
            return redirect()
                ->to(base_url('productos/crear'))
                ->withInput()
                ->with('errores', $this->productoModel->errors());
        }

        return redirect()
            ->to(base_url('productos'))
            ->with('mensaje', '✅ Producto registrado correctamente.');
    }

    // ------------------------------------------------------------------
    // EDIT — Mostrar formulario con datos del producto a editar
    // ------------------------------------------------------------------
public function edit(int $id): string
{
    $producto = $this->productoModel->find($id);

    if (! $producto) {
        throw \CodeIgniter\Exceptions\PageNotFoundException::forPageNotFound('Producto no encontrado.');
    }

    $clienteModel    = new \App\Models\ClienteModel();
    $repartidorModel = new \App\Models\RepartidorModel();

    $data = [
        'titulo'       => 'Editar Producto',
        'producto'     => $producto,
        'accion'       => base_url("productos/actualizar/{$id}"),
        // También pasamos las listas aquí para evitar el error
        'clientes'     => $clienteModel->findAll(),
        'repartidores' => $repartidorModel->findAll(),
        'productos'    => $this->productoModel->findAll(),
    ];

    return view('productos/form', $data);
}
    // ------------------------------------------------------------------
    // UPDATE — Actualizar producto existente (POST)
    // ------------------------------------------------------------------
    public function update(int $id)
    {
        $datos = [
            'nombre'       => $this->request->getPost('nombre'),
            'descripcion'  => $this->request->getPost('descripcion'),
            'imagen'       => $this->request->getPost('imagen'),
            'categoria'    => $this->request->getPost('categoria'),
            'unidad_venta' => $this->request->getPost('unidad_venta'),
        ];

        if (! $this->productoModel->update($id, $datos)) {
            return redirect()
                ->to(base_url("productos/editar/{$id}"))
                ->withInput()
                ->with('errores', $this->productoModel->errors());
        }

        return redirect()
            ->to(base_url('productos'))
            ->with('mensaje', '✅ Producto actualizado correctamente.');
    }

    // ------------------------------------------------------------------
    // DELETE — Eliminar producto
    // ------------------------------------------------------------------
    public function delete(int $id)
    {
        $this->productoModel->delete($id);

        return redirect()
            ->to(base_url('productos'))
            ->with('mensaje', '🗑️ Producto eliminado.');
    }
    // ------------------------------------------------
// ENDPOINT AJAX — Buscar productos
// GET /productos/buscar?q=termino
// ------------------------------------------------
public function buscar()
{
    $termino = $this->request->getGet('q');

    $builder = $this->productoModel->builder();

    if (!empty($termino)) {
        $builder->groupStart()
            ->like('nombre', $termino)
            ->orLike('categoria', $termino)
            ->groupEnd();
    }

    $productos = $builder->limit(15)->get()->getResultArray();

    return $this->response->setJSON($productos);
}
}