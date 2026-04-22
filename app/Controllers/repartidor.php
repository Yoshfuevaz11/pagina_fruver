<?php

namespace App\Controllers;

use App\Models\RepartidorModel;

class Repartidor extends BaseController
{
    protected RepartidorModel $repartidorModel;

    public function __construct()
    {
        $this->repartidorModel = new RepartidorModel();
    }

    public function index(): string
    {
        $data = [
            'titulo'       => 'Gestión de Repartidores',
            'repartidores' => $this->repartidorModel->getRepartidoresConPedidos(),
        ];
        return view('repartidores/index', $data);
    }

    public function create(): string
    {
        return view('repartidores/form', [
            'titulo'      => 'Registrar Repartidor',
            'repartidor'  => null,
            'accion'      => base_url('repartidor/guardar'),
        ]);
    }

    public function store()
    {
        $datos = [
            'nombre'     => $this->request->getPost('nombre'),
            'ap_paterno' => $this->request->getPost('ap_paterno'),
            'ap_materno' => $this->request->getPost('ap_materno'),
            'telefono'   => $this->request->getPost('telefono'),
            'direccion'  => $this->request->getPost('direccion'),
            'notas'      => $this->request->getPost('notas') ?? '',
        ];

        if (! $this->repartidorModel->insert($datos)) {
            return redirect()->to(base_url('repartidor/crear'))
                ->withInput()
                ->with('errores', $this->repartidorModel->errors());
        }

        return redirect()->to(base_url('repartidor'))
            ->with('mensaje', '✅ Repartidor registrado correctamente.');
    }

    public function edit(int $id): string
    {
        $repartidor = $this->repartidorModel->find($id);

        if (! $repartidor) {
            throw \CodeIgniter\Exceptions\PageNotFoundException::forPageNotFound();
        }

        return view('repartidores/form', [
            'titulo'     => 'Editar Repartidor',
            'repartidor' => $repartidor,
            'accion'     => base_url("repartidor/actualizar/{$id}"),
        ]);
    }

    public function update(int $id)
    {
        $datos = [
            'nombre'     => $this->request->getPost('nombre'),
            'ap_paterno' => $this->request->getPost('ap_paterno'),
            'ap_materno' => $this->request->getPost('ap_materno'),
            'telefono'   => $this->request->getPost('telefono'),
            'direccion'  => $this->request->getPost('direccion'),
            'notas'      => $this->request->getPost('notas') ?? '',
        ];

        if (! $this->repartidorModel->update($id, $datos)) {
            return redirect()->to(base_url("repartidor/editar/{$id}"))
                ->withInput()
                ->with('errores', $this->repartidorModel->errors());
        }

        return redirect()->to(base_url('repartidor'))
            ->with('mensaje', '✅ Repartidor actualizado correctamente.');
    }

    public function delete(int $id)
    {
        $this->repartidorModel->delete($id);

        return redirect()->to(base_url('repartidor'))
            ->with('mensaje', '🗑️ Repartidor eliminado.');
    }
}   