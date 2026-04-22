<?php

namespace App\Controllers;

use App\Models\EntradaModel;
use App\Models\ExistenciaModel;
use App\Models\MermaModel;
use App\Models\ProductoModel;

class Inventario extends BaseController
{
    protected EntradaModel    $entradaModel;
    protected ExistenciaModel $existenciaModel;
    protected MermaModel      $mermaModel;
    protected ProductoModel   $productoModel;

    public function __construct()
    {
        $this->entradaModel    = new EntradaModel();
        $this->existenciaModel = new ExistenciaModel();
        $this->mermaModel      = new MermaModel();
        $this->productoModel   = new ProductoModel();
    }

    // =====================================================
    // ENTRADAS
    // =====================================================

    public function entradas(): string
    {
        return view('inventario/entradas', [
            'titulo'   => 'Registro de Entradas',
            'entradas' => $this->entradaModel->getEntradasConProducto(),
        ]);
    }

    public function crearEntrada(): string
    {
        return view('inventario/entrada_form', [
            'titulo'    => 'Registrar Entrada',
            'entrada'   => null,
            'accion'    => base_url('inventario/entradas/guardar'),
            'productos' => $this->productoModel->orderBy('nombre')->findAll(),
        ]);
    }

    public function storeEntrada()
    {
        $cantidad    = (float)$this->request->getPost('cantidad');
        $equivalente = (float)$this->request->getPost('equivalente');
        $convercion  = EntradaModel::calcularConvercion($cantidad, $equivalente);
        $idProducto  = (int)$this->request->getPost('id_producto2');

        // Fecha caducidad: si no se especifica, 5 días después de la entrada
        $fechaEntrada    = $this->request->getPost('fecha');
        $fechaCaducidad  = $this->request->getPost('fecha_caducidad')
            ?: date('Y-m-d', strtotime($fechaEntrada . ' +5 days'));

        $datos = [
            'fecha'           => $fechaEntrada,
            'fecha_caducidad' => $fechaCaducidad,
            'cantidad'        => $cantidad,
            'unidad_compra'   => $this->request->getPost('unidad_compra'),
            'unidad_venta'    => $this->request->getPost('unidad_venta'),
            'precio_compra'   => $this->request->getPost('precio_compra'),
            'id_producto2'    => $idProducto,
            'equivalente'     => $equivalente,
            'convercion'      => $convercion,
        ];

        if (! $this->entradaModel->insert($datos)) {
            return redirect()->to(base_url('inventario/entradas/crear'))
                ->withInput()
                ->with('errores', $this->entradaModel->errors());
        }

        // Actualizar existencias automáticamente
        $this->existenciaModel->crearOActualizar($idProducto, $convercion);

        return redirect()->to(base_url('inventario/entradas'))
            ->with('mensaje', '✅ Entrada registrada. Existencias actualizadas.');
    }

    public function editarEntrada(int $id): string
    {
        $entrada = $this->entradaModel->find($id);
        if (! $entrada) {
            throw \CodeIgniter\Exceptions\PageNotFoundException::forPageNotFound();
        }

        return view('inventario/entrada_form', [
            'titulo'    => 'Editar Entrada',
            'entrada'   => $entrada,
            'accion'    => base_url("inventario/entradas/actualizar/{$id}"),
            'productos' => $this->productoModel->orderBy('nombre')->findAll(),
        ]);
    }

    public function updateEntrada(int $id)
    {
        $cantidad    = (float)$this->request->getPost('cantidad');
        $equivalente = (float)$this->request->getPost('equivalente');
        $convercion  = EntradaModel::calcularConvercion($cantidad, $equivalente);

        $datos = [
            'fecha'           => $this->request->getPost('fecha'),
            'fecha_caducidad' => $this->request->getPost('fecha_caducidad'),
            'cantidad'        => $cantidad,
            'unidad_compra'   => $this->request->getPost('unidad_compra'),
            'unidad_venta'    => $this->request->getPost('unidad_venta'),
            'precio_compra'   => $this->request->getPost('precio_compra'),
            'id_producto2'    => $this->request->getPost('id_producto2'),
            'equivalente'     => $equivalente,
            'convercion'      => $convercion,
        ];

        $this->entradaModel->update($id, $datos);

        return redirect()->to(base_url('inventario/entradas'))
            ->with('mensaje', '✅ Entrada actualizada correctamente.');
    }

    public function deleteEntrada(int $id)
    {
        $this->entradaModel->delete($id);
        return redirect()->to(base_url('inventario/entradas'))
            ->with('mensaje', '🗑️ Entrada eliminada.');
    }

    // =====================================================
    // EXISTENCIAS
    // =====================================================

    public function existencias(): string
    {
        return view('inventario/existencias', [
            'titulo'      => 'Control de Existencias',
            'existencias' => $this->existenciaModel->getExistenciasConProducto(),
        ]);
    }

    // =====================================================
    // MERMAS
    // =====================================================

    public function mermas(): string
    {
        return view('inventario/mermas', [
            'titulo'  => 'Registro de Mermas',
            'mermas'  => $this->mermaModel->getMermasCompleto(),
        ]);
    }

    public function crearMerma(): string
    {
        return view('inventario/merma_form', [
            'titulo'   => 'Registrar Merma',
            'merma'    => null,
            'accion'   => base_url('inventario/mermas/guardar'),
            'entradas' => $this->entradaModel->getEntradasConProducto(),
        ]);
    }

    public function storeMerma()
    {
        $datos = [
            'cantidad'   => $this->request->getPost('cantidad'),
            'fecha'      => $this->request->getPost('fecha'),
            'notas'      => $this->request->getPost('notas'),
            'id_entrada' => $this->request->getPost('id_entrada'),
        ];

        if (! $this->mermaModel->insert($datos)) {
            return redirect()->to(base_url('inventario/mermas/crear'))
                ->withInput()
                ->with('errores', $this->mermaModel->errors());
        }

        return redirect()->to(base_url('inventario/mermas'))
            ->with('mensaje', '✅ Merma registrada correctamente.');
    }

    public function deleteMerma(int $id)
    {
        $this->mermaModel->delete($id);
        return redirect()->to(base_url('inventario/mermas'))
            ->with('mensaje', '🗑️ Merma eliminada.');
    }
}