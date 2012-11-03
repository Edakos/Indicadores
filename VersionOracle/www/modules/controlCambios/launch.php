<?php
/*
 * Controlador del modulo de control de cmabios
 * Jorge Tenorio
 * 01-03-2011
 * MUSHOQ
 */

@session_start();
require_once($_SESSION['path'].'/conf/includes.php');

class ControlCambios{
    private $ado;


    public function  __construct() {
        $this->ado = new Ado();

    }

    public function addChagne($accion,$vAnterior,$vNuevo,$comentario,$idIndicador){
        $usuario = $_SESSION['USER_id'];
        $email = $_SESSION['USER_mail'];
  
        $comentario = str_replace('+', ' ', $comentario);
   
        $archivo = "";
        if(isset($_SESSION['uploadedFile'])){
            $archivo = $_SESSION['uploadedFile'];
            unset($_SESSION['uploadedFile']);
        }

        $sql="INSERT INTO CONTROL_CAMBIOS(IDUSUARIO,ACCION,VALOR_ANTERIOR,VALOR_NUEVO,ARCHIVO,COMENTARIO,IDINDICADOR) VALUES(
                $usuario,'$accion',$vAnterior,$vNuevo,'$archivo','$comentario',$idIndicador)";

        $result = $this->ado->query($sql);
        
    }



    public function uploadFile($file){
          //instanciar el modulo de carga
          include_once($_SESSION['path'].'\modules\filesUploader\launch.php');
          //print_r($this->files);
          $uploader = new FilesUploader();
          $fileName = $uploader->uploadFile($file, $_SESSION['path'].'/modules/controlCambios/files/',$_SESSION['USER_id'].'_'.date('l jS \of F Y'));

          switch(trim($fileName)){
              case 'TO_BIG':
                  echo '<p class="error">=> Los sentimos, el fichero seleccionado es demasiado grande.</p>';
                  exit;
                  break;
              case 'ERROR':
                  echo '<p class="error">=> Los sentimos, ha ocurrido un error subiendo el archivo, por favor intente nuevamente.</p>';
                  exit;
                  break;
              default:
                  //procesar el archivo
                  $_SESSION['uploadedFile'] = $fileName;
                  echo 'Archivo cargado exitosamente.';
                  break;
          }

    }

    public function getCambios($desde,$fdesde,$fhasta){
        $limite = $desde + 10;
        /*$sql = "SELECT * FROM (SELECT RANK() OVER (ORDER BY ID ASC , ROWNUM ASC ) as RN,CC.* FROM CONTROL_CAMBIOS CC) WHERE RN >=$desde AND RN <= $limite
                AND FECHA BETWEEN '$fdesde' AND '$fhasta'";*/
        
        $sql = "SELECT * FROM (SELECT RANK() OVER (ORDER BY ID DESC , ROWNUM ASC ) as RN,CC.* FROM CONTROL_CAMBIOS CC) WHERE RN >=$desde AND RN <= $limite 
        AND FECHA >= TO_DATE('$fdesde', 'yyyy-mm-dd') AND FECHA <= TO_DATE('$fhasta', 'yyyy-mm-dd')";
        
        $result = $this->ado->query($sql);   
        
       
        return $result;
    }

    public function  getCambiosFiltros($filtro,$valor,$fdesde,$fhasta){

        $sql = "SELECT CC.* FROM CONTROL_CAMBIOS CC WHERE FECHA >= TO_DATE('$fdesde', 'yyyy-mm-dd') AND FECHA <= TO_DATE('$fhasta', 'yyyy-mm-dd') AND $filtro = $valor";
        $result = $this->ado->query($sql);
        return $result;
    }

}