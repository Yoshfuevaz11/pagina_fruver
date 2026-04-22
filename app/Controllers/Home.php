<?php

namespace App\Controllers;

use App\Models\ModeloCliente;

class Home extends BaseController
{
    public function index(): string
    {
        return view('home');
    }

    public function vista_cliente(): string
    {
        return view('alta_cliente');
    }

    public function guardar_cliente()
    {
        $mcliente = new ModeloCliente();

        $datos_cliente = [ 
            'nombre'    => $this->request->getPost('nombre'),
            'direccion' => $this->request->getPost('direccion'),
            'telefono'  => $this->request->getPost('telefono'),
            'rfc'       => $this->request->getPost('rfc')
        ];

        if(empty($datos_cliente['nombre']) ||
           empty($datos_cliente['direccion']) ||
           empty($datos_cliente['rfc']) ||
           empty($datos_cliente['telefono'])) 
        {
            return view('alta_cliente');
        } else {
            //evitar datos repetidos
            $ren=$mcliente->buscRFC($datos_cliente['rfc']);
            $total=count($ren);
              if($total==0)
            $mcliente->insert($datos_cliente);
             else 
                echo"Cliente ya registrado";
        }
    }

    public function lista_clientes(){
         $mcliente = new ModeloCliente();
         $clientes = $mcliente->findAll(); //select * from cliente
            $datos = ['datos_cliente' => $clientes];
            return view('lista_clientes', $datos);
    }
    public function recuperar($id_cliente){
    $mcliente = new ModeloCliente();
    $cliente = $mcliente->getUser($id_cliente);

    if (!$cliente) {
        return "Cliente no encontrado";
    }

    $datos['cliente'] = $cliente;
    return view('emergente', $datos);
}

    public function actualizar(){
        $id = $this->request->getPost('id_cliente');
        $mcliente = new ModeloCliente();
        $datos_mod = [
            'nombre' => $this->request->getPost('nombre'),
            'direccion' => $this->request->getPost('direccion'),
            'rfc' => $this->request->getPost('rfc'),
            'telefono' => $this->request->getPost('telefono')
        ];

      if ($mcliente->update($id, $datos_mod)) {
        $datos['mensaje'] = 'Cliente actualizado correctamente';
    } else {
        $datos['mensaje'] = 'Error al actualizar cliente';
    }

    
    $datos['datos_cliente'] = $mcliente->findAll(); 
    
    return view('lista_clientes', $datos);
}

    public function borrar($id_cliente){
        $mcliente = new ModeloCliente();
        if ($mcliente->eliminarCliente($id_cliente)) {
            $c=$mcliente->findAll();
            $datos=['datos_cliente' => $c];
            return view ('lista_clientes', $datos);
             $datos['mensaje'] = 'Cliente eliminado correctamente';
        } else {
            $datos['mensaje'] = 'Error al eliminar cliente';
        }

        
        $datos['datos_cliente'] = $mcliente->findAll(); 
        
        return view('lista_clientes', $datos);
    }
    
}