<?php
/*
 * Controlador del modulo de unidades
 * Jorge Tenorio
 * 18-02-2011
 * MUSHOQ
 */

@session_start();
require_once($_SESSION['path'].'/conf/includes.php');


class Campos{

    private $ado;

    public function __construct(){
        $this->ado = new Ado();
    }


    public function addCampo($data){
        //verificar q no exista ya la misma frecuencia
        if(!$this->existe($data['nombre'])){
            $sql = "INSERT INTO CAMPOS_ADICIONALES (NOMBRE) VALUES('{$data['nombre']}')";
            $result = $this->ado->query($sql);
               if(!isset($result['Error'])){
                    echo '<p>Campo creado satisfactoriamente.</p>';
               }else{
                   echo '<p class="error">No se ha podido crear el campo.</p>';
               }
        }else{
            echo '<p class="error">Ya existe un campo con el mismo nombre.</p>';
        }
    }

    private function existe($nombre){
        $nombre = trim($nombre);
        $sql = "SELECT * FROM CAMPOS_ADICIONALES WHERE NOMBRE = '$nombre'";
        $result = $this->ado->query($sql);

        if(count($result))
            return TRUE;
        else
            return FALSE;
    }

    public function  getCampos(){
        $sql = "SELECT * FROM CAMPOS_ADICIONALES ORDER BY NOMBRE";
        $result = $this->ado->query($sql);

        return $result;
    }

    public function  getCampo($id){
        $sql = "SELECT * FROM CAMPOS_ADICIONALES WHERE IDCAMPO = $id";
        $result = $this->ado->query($sql);

        return $result;
    }

    public function deleteCampo($id){
        $sql = "DELETE FROM CAMPOS_ADICIONALES WHERE IDCAMPO = $id";
        $result = $this->ado->query($sql);
        if(!isset($result['Error'])){
                    echo '<p>Campo eliminado satisfactoriamente.</p>';
               }else{
                   echo '<p class="error">No se ha podido eliminar el campo en este momento.</p>';
               }
    }
}