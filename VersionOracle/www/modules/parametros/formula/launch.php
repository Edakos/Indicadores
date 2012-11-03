<?php
/*
 * Controlador del modulo de formulas
 * Jorge Tenorio
 * 18-04-2011
 * MUSHOQ
 */

@session_start();
require_once($_SESSION['path'].'/conf/includes.php');


class Formula{

    private $ado;

    public function __construct(){
        $this->ado = new Ado();
    }


    public function addFormula($data){
        //verificar q no exista ya la misma frecuencia
        if(!$this->existe($data['nombre'])){
            $sql = "INSERT INTO FORMULA (NOMBRE) VALUES('{$data['nombre']}')";
            $result = $this->ado->query($sql);
               if(!isset($result['Error'])){
                    echo '<p>F&oacute;rmula creada satisfactoriamente.</p>';
               }else{
                   echo '<p class="error">No se ha podido crear la f&oacute;rmula.</p>';
               }
        }else{
            echo '<p class="error">Ya existe una f&oacute;rmula con el mismo nombre.</p>';
        }
    }

    private function existe($nombre){
        $nombre = trim($nombre);
        $sql = "SELECT * FROM FORMULA WHERE NOMBRE = '$nombre'";
        $result = $this->ado->query($sql);

        if(count($result))
            return TRUE;
        else
            return FALSE;
    }

    public function  getFormulas(){
        $sql = "SELECT * FROM FORMULA ORDER BY NOMBRE";
        $result = $this->ado->query($sql);

        return $result;
    }

    public function  getFormula($id){
        $sql = "SELECT * FROM FORMULA WHERE IDFORMULA = $id";
        $result = $this->ado->query($sql,FALSE);

        return $result;
    }

    public function deleteFormula($id){
        //vaidar q la fórmula no exista
        
        $sql = "SELECT * FROM INDICADORES WHERE FORMULA = '$id'";
        $existe = $this->ado->query($sql);
        
        
        if(!count($existe)){
            
        
        $sql = "DELETE FROM FORMULA WHERE IDFORMULA = $id";
        $result = $this->ado->query($sql);
        if(!isset($result['Error'])){
                    echo '<p>F&oacute;rmula eliminada satisfactoriamente.</p>';
               }else{
                   echo '<p class="error">No se ha podido eliminar la f&oacute;rmula en este momento.</p>';
               }
        }else{
            echo '<p class="error">No se ha podido eliminar la f&oacute;rmula en este momento, se encuentra vinculada a un indicador.</p>';
        }
    }
}