<?php

namespace App\Controllers;

use App\Models\ClienteModel;
use App\Models\DireccionModel;

class Clientes extends BaseController
{
    protected ClienteModel   $clienteModel;
    protected DireccionModel $direccionModel;

    public function __construct()
    {
        $this->clienteModel   = new ClienteModel();
        $this->direccionModel = new DireccionModel();
    }

    // Lista todos los clientes con su dirección
    public function index(): string
    {
        $data = [
            'titulo'   => 'Gestión de Clientes',
            'clientes' => $this->clienteModel->getClientesConDireccion(),
        ];
        return view('clientes/index', $data);
    }

    // Formulario para nuevo cliente
    public function create(): string
    {
        return view('clientes/form', [
            'titulo'   => 'Registrar Cliente',
            'cliente'  => null,
            'accion'   => base_url('clientes/guardar'),
        ]);
    }

    // Guardar nuevo cliente + dirección
    public function store()
    {
        // 1. Guardar cliente
        $datosCliente = [
            'nombre'     => $this->request->getPost('nombre'),
            'ap_paterno' => $this->request->getPost('ap_paterno'),
            'ap_materno' => $this->request->getPost('ap_materno'),
            'telefono'   => $this->request->getPost('telefono'),
        ];

        if (! $this->clienteModel->insert($datosCliente)) {
            return redirect()->to(base_url('clientes/crear'))
                ->withInput()
                ->with('errores', $this->clienteModel->errors());
        }

        $idCliente = $this->clienteModel->getInsertID();

        // 2. Guardar dirección ligada al cliente
        $datosDireccion = [
            'colonia'    => $this->request->getPost('colonia'),
            'calle'      => $this->request->getPost('calle'),
            'numero'     => $this->request->getPost('numero'),
            'municipio'  => $this->request->getPost('municipio'),
            'estado'     => $this->request->getPost('estado'),
            'id_cliente' => $idCliente,
        ];
        $this->direccionModel->insert($datosDireccion);

        return redirect()->to(base_url('clientes'))
            ->with('mensaje', '✅ Cliente registrado correctamente.');
    }

    // Formulario de edición
    public function edit(int $id): string
    {
        $cliente = $this->clienteModel->getClienteConDireccion($id);

        if (empty($cliente)) {
            throw \CodeIgniter\Exceptions\PageNotFoundException::forPageNotFound();
        }

        return view('clientes/form', [
            'titulo'  => 'Editar Cliente',
            'cliente' => $cliente,
            'accion'  => base_url("clientes/actualizar/{$id}"),
        ]);
    }

    // Actualizar cliente + dirección
    public function update(int $id)
    {
        $datosCliente = [
            'nombre'     => $this->request->getPost('nombre'),
            'ap_paterno' => $this->request->getPost('ap_paterno'),
            'ap_materno' => $this->request->getPost('ap_materno'),
            'telefono'   => $this->request->getPost('telefono'),
        ];

        if (! $this->clienteModel->update($id, $datosCliente)) {
            return redirect()->to(base_url("clientes/editar/{$id}"))
                ->withInput()
                ->with('errores', $this->clienteModel->errors());
        }

        // Actualizar o insertar dirección
        $idDireccion = $this->request->getPost('id_direccion');
        $datosDireccion = [
            'colonia'    => $this->request->getPost('colonia'),
            'calle'      => $this->request->getPost('calle'),
            'numero'     => $this->request->getPost('numero'),
            'municipio'  => $this->request->getPost('municipio'),
            'estado'     => $this->request->getPost('estado'),
            'id_cliente' => $id,
        ];

        if ($idDireccion) {
            $this->direccionModel->update($idDireccion, $datosDireccion);
        } else {
            $this->direccionModel->insert($datosDireccion);
        }

        return redirect()->to(base_url('clientes'))
            ->with('mensaje', '✅ Cliente actualizado correctamente.');
    }

    // Eliminar cliente
    public function delete(int $id)
    {
        // Primero eliminar dirección (por FK)
        $this->direccionModel->where('id_cliente', $id)->delete();
        $this->clienteModel->delete($id);

        return redirect()->to(base_url('clientes'))
            ->with('mensaje', '🗑️ Cliente eliminado.');
    }
}