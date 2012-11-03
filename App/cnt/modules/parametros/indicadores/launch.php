<?php
/*
 * Controlador de la parametrizacion de indicadores
 * Jorge Tenorio
 * 22-02-2011
 * MUSHOQ
 */

@session_start();
require_once($_SESSION['path'].'/conf/includes.php');

class ParametrosIndicadores{

    private $ado;

    public function __construct(){
        $this->ado = new Ado();
    }

    public function getValues(){
        $sql = "SELECT * FROM PARAMETROS_INDICADORES";
        $result = $this->ado->query($sql);
        return $result;
    }

    public function updateValues($value){
       $sql = "UPDATE PARAMETROS_INDICADORES SET
            DIA_INICIO = {$value['diauno']},
            DIAS_INGRESO = {$value['diados']},
            MAXIMO_INGRESO = {$value['diatres']}";

        $result = $this->ado->query($sql);

         if(!isset($result['Error'])){
                    echo '<p>Valores modificados satisfactoriamente.</p>';
               }else{
                   echo '<p class="error">No se ha podido modificar los valores.</p>';
               }
    }
    
}
