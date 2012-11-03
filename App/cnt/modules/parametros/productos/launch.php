<?php
/*
 * Controlador del modulo de unidades
 * Jorge Tenorio
 * 18-02-2011
 * MUSHOQ
 */
@session_start();
require_once($_SESSION['path'].'/conf/includes.php');

class Productos{

    private $ado;

    public function  __construct() {
        $this->ado = new Ado();
    }

    public function addProducto($data){
        //verificar que no haya el mismo simbolo ya utilizado

        if($this->verificarProducto($data['nombre'])){
            echo '<p class="error">ADVERTENCIA: Ya existe un producto con el mismo nombre.</p>';
        }else{

            $sql = "INSERT INTO PRODUCTO (NOMBRE,DESCRIPCION) VALUES(
                    '{$data['nombre']}','{$data['descripcion']}')";

            $result = $this->ado->query($sql);
               if(!isset($result['Error'])){
                    echo '<p>Producto agregado satisfactoriamente.</p>';
               }else{
                   echo '<p class="error">No se ha podido agregar el producto</p>';
               }
        }
    }

    public function verificarProducto($nombre){
        $sql = "SELECT * FROM PRODUCTO WHERE NOMBRE='$nombre'";
        $result = $this->ado->query($sql);

        if(!isset($result['Error'])){
            if(count($result))
                return true;
            else
                return false;
        }else{
            return true;
        }
    }

    public function getProductos(){
        $sql = "SELECT * FROM PRODUCTO";
        $result = $this->ado->query($sql);

        return $result;
    }

    public function getProducto($id){
        $sql = "SELECT * FROM PRODUCTO WHERE IDPRODUCTO = $id";
        $result = $this->ado->query($sql);

        return $result;
    }

    public function editProducto($id,$data){
        if($this->verificarProducto($data['nombre'])){
            echo '<p class="error">ADVERTENCIA: Ya existe un producto con el mismo nombre.</p>';
        }
        {
            $sql = "UPDATE PRODUCTO SET
                    NOMBRE = '{$data['nombre']}',                   
                    DESCRIPCION='{$data['descripcion']}'
                    WHERE
                    IDPRODUCTO= $id";

            $result = $this->ado->query($sql);
               if(!isset($result['Error'])){
                    echo '<p>Producto editado satisfactoriamente.</p>';
               }else{
                   echo '<p class="error">No se ha podido editar el producto.</p>';
               }
        }
    }

    public function deleteProducto($id){
        $sql = "DELETE FROM PRODUCTO
                WHERE
                IDPRODUCTO= $id";

        $result = $this->ado->query($sql);
           if(!isset($result['Error'])){
                echo '<p>Producto eliminado satisfactoriamente.</p>';
           }else{
               echo '<p class="error">No se ha podido eliminar el producto.</p>';
           }
    }

}