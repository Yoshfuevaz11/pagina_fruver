<?php
namespace App\Models;

use CodeIgniter\Model;
class ModeloCliente extends Model
{
    protected $table = 'cliente';
    protected $primaryKey = 'id_cliente'; //definimos la llave primaria de la tabla
    protected $allowedFields = ['nombre', 'direccion', 'rfc', 'telefono']; // definimos los campos de la tabla cliente

    public function getUser($idcliente){
         //SELECT * FROM cliente 'cliente' where 'id_cliente' = 1FVCG
        return $this->where('id_cliente', $idcliente)->first();
    }

    public function buscRFC($rfc){
        //select * from cliente where rfc?$rfc
        return $this->where('rfc', $rfc)->findAll();
        
    }

    public function eliminarCliente($id_cliente){
        if ($this->delete($id_cliente)) 
            return true; 
        else 
            return false; 
        }

}


