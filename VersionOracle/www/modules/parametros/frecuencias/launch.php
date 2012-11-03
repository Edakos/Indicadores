<?php
/*
 * Controlador del modulo de unidades
 * Jorge Tenorio
 * 18-02-2011
 * MUSHOQ
 */

@session_start();
require_once($_SESSION['path'].'/conf/includes.php');


class Frecuencia{

    private $ado;

    public function __construct(){
        $this->ado = new Ado();
    }


    public function addFrecuencia($data){
        //verificar q no exista ya la misma frecuencia
        if(!$this->existe($data['meses'])){
            $sql = "INSERT INTO FRECUENCIA (NOMBRE,MESES) VALUES('{$data['nombre']}',{$data['meses']})";
            $result = $this->ado->query($sql);
               if(!isset($result['Error'])){
                    echo '<p>Frecuencia creada satisfactoriamente.</p>';
               }else{
                   echo '<p class="error">No se ha podido crear la frecuencia.</p>';
               }
        }else{
            echo '<p class="error">Ya existe una frecuencia con el mismo per&iacute;odo.</p>';
        }
    }

    private function existe($meses){
        $sql = "SELECT * FROM FRECUENCIA WHERE MESES = $meses";
        $result = $this->ado->query($sql);

        if(count($result))
            return TRUE;
        else
            return FALSE;
    }

    public function  getFrecuencias(){
        $sql = "SELECT * FROM FRECUENCIA ORDER BY MESES";
        $result = $this->ado->query($sql);

        return $result;
    }

    public function  getFrecuencia($id){
        $sql = "SELECT * FROM FRECUENCIA WHERE IDFRECUENCIA = $id";
        $result = $this->ado->query($sql);

        return $result;
    }

    public function deleteFrecuencia($id){
        $sql = "DELETE FROM FRECUENCIA WHERE IDFRECUENCIA = $id";
        $result = $this->ado->query($sql);
        if(!isset($result['Error'])){
                    echo '<p>Frecuencia eliminada satisfactoriamente.</p>';
               }else{
                   echo '<p class="error">No se ha podido eliminar la frecuencia.</p>';
               }
    }
}