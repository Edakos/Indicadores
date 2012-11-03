<?php
/*
 * Controlador del modulo de unidades
 * Jorge Tenorio
 * 18-02-2011
 * MUSHOQ
 */
@session_start();
require_once($_SESSION['path'].'/conf/includes.php');

class Unidades{

    private $ado;

    public function  __construct() {
        $this->ado = new Ado();
    }

    public function addUnidad($data){
        //verificar que no haya el mismo simbolo ya utilizado
        if($this->verificarSimbolo($data['simbolo'])){
            echo '<p class="error">ADVERTENCIA: Ya existe una unidad con el mismo s&iacute;mbolo.</p>';
        }

        $sql = "INSERT INTO UNIDADES (NOMBRE,SIMBOLO,DESCRIPCION) VALUES(
                '{$data['nombre']}','{$data['simbolo']}','{$data['descripcion']}')";

        $result = $this->ado->query($sql);
       if(!isset($result['Error'])){
            echo '<p>Unidad agregada satisfactoriamente.</p>';
       }else{
           echo '<p class="error">No se ha podido agregar la unidad</p>';
       }
    }

    public function verificarSimbolo($simbolo){
        $sql = "SELECT * FROM UNIDADES WHERE SIMBOLO='$simbolo'";
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

    public function getUnidades(){
        $sql = "SELECT * FROM UNIDADES";
        $result = $this->ado->query($sql);

        return $result;
    }

    public function getUnidad($id){
        $sql = "SELECT * FROM UNIDADES WHERE IDUNIDAD = $id";
        $result = $this->ado->query($sql);

        return $result;
    }

    public function editUnidad($id,$data){
        if($this->verificarSimbolo($data['simbolo'])){
            echo '<p class="error">ADVERTENCIA: Ya existe una unidad con el mismo simbolo.</p>';
        }
        $sql = "UPDATE UNIDADES SET
                NOMBRE = '{$data['nombre']}',
                SIMBOLO = '{$data['simbolo']}',
                DESCRIPCION='{$data['descripcion']}'
                WHERE
                IDUNIDAD= $id";

        $result = $this->ado->query($sql);
           if(!isset($result['Error'])){
                echo '<p>Unidad editada satisfactoriamente.</p>';
           }else{
               echo '<p class="error">No se ha podido editar la unidad</p>';
           }
        
    }

    public function deleteUnidad($id){
        $sql = "DELETE FROM UNIDADES
                WHERE
                IDUNIDAD= $id";

        $result = $this->ado->query($sql);
           if(!isset($result['Error'])){
                echo '<p>Unidad eliminada satisfactoriamente.</p>';
           }else{
               echo '<p class="error">No se ha podido eliminar la unidad</p>';
           }
    }

}