<?php
/* 
 * Controlador del modulo de indicadores
 * Jorge Tenorio
 * 24-02-2011
 * MUSHOQ
 */

@session_start();
require_once($_SESSION['path'].'/conf/includes.php');

class Indicador{

    private $ado;

    public function  __construct() {
        $this->ado = new Ado();
    }

    public function addIndicador($_data){

        if($this->existCode($_data['codigo'])){
             echo '<p class="error">El código ingresado ya ha sido asignado a otro indicador.</p>';
             return;
        }

       $sql = "INSERT INTO INDICADORES(
                    CODIGO,
                    NIVEL_ESTRATEGICO,
                    NOMBRE,
                    UNIDAD,
                    DEFINICION,
                    FORMULA,
                    SEGMENTO,
                    PRODUCTO,
                    TIPO,
                    DESPLIEGUE,
                    FRECUENCIA,
                    RESPONSABLE,
                    FACILITADOR,
                    VIGENTE,
                    ESTADO,
                    COMENTARIO,
                    MANUAL,
                    VIGENTE_HASTA,
                    RESPONSABLE_GERENCIA,
                    SIGNO
                    ) VALUES(
                    '{$_data['codigo']}',
                    {$_data['nivel']},
                    '{$_data['nombre']}',
                    {$_data['unidad']},
                    '{$_data['definicion']}',
                    '{$_data['formula']}',
                    {$_data['segmento']},
                    {$_data['producto']},
                    '{$_data['tipo']}',
                    {$_data['despliegue']},
                    {$_data['frecuencia']},
                    {$_data['cargo']},
                    {$_data['facilitador']},
                    {$_data['vigente']},
                    {$_data['estado']},
                    '{$_data['comentario']}',
                    {$_data['manual']},
                    '{$_data['vigentehasta']}',
                    {$_data['gestion']},
                    '{$_data['signo']}'
                    )";

         $result = $this->ado->query($sql);
         if(isset($result['Error'])){
             echo '<p class="error">No se puede crear el nuevo indicador.</p>';
         }else{

             $sql = "SELECT MAX(IDINDICADOR) AS LAST FROM INDICADORES";
             $nuevo = $this->ado->query($sql);

             $this->addCampoIndicador($nuevo[0]['LAST']);

             echo '<p>Indicador creado satisfactoriamente.</p>';
         }
                    
    }


    public function getIndicadores(){
        $sql = "SELECT * FROM INDICADORES ORDER BY CODIGO";
        $result = $this->ado->query($sql);
        return $result;
    }


    public function deleteIndicador($id){
        $sql = "DELETE FROM INDICADORES WHERE IDINDICADOR = $id";
        $result = $this->ado->query($sql);

       

         if(isset($result['Error'])){
             echo '<p class="error">No se puede eliminar el indicador en este momento.</p>';
         }else{
             echo '<p>Indicador eliminado satisfactoriamente.</p>';
         }
    }

    public function getIndicador($id){
        $sql = "SELECT * FROM INDICADORES WHERE IDINDICADOR = $id";
        $result = $this->ado->query($sql);


         //cargar los campos extras
        if(isset($_SESSION['CAMPOS_TEMPORALES'])){
            unset($_SESSION['CAMPOS_TEMPORALES']);
        }

        $_SESSION['CAMPOS_TEMPORALES'] = array();

        $sql = "SELECT * FROM INDICADOR_CAMPOS WHERE IDINDICADOR = $id";
        $campos = $this->ado->query($sql);

        foreach($campos as $item){
             array_push($_SESSION['CAMPOS_TEMPORALES'], $item['IDCAMPO'].'_'.$item['VALOR']);
        }


        return $result;
    }

    public function editIndicador($id,$_data){
        

            $sql = "UPDATE INDICADORES SET
                    CODIGO = '{$_data['codigo']}',
                    NIVEL_ESTRATEGICO = {$_data['nivel']},
                    NOMBRE = '{$_data['nombre']}',
                    UNIDAD = {$_data['unidad']},
                    DEFINICION = '{$_data['definicion']}',
                    FORMULA = '{$_data['formula']}',
                    SEGMENTO = {$_data['segmento']},
                    PRODUCTO = {$_data['producto']},
                    TIPO = '{$_data['tipo']}',
                    DESPLIEGUE = {$_data['despliegue']},
                    FRECUENCIA = {$_data['frecuencia']},
                    RESPONSABLE = {$_data['cargo']},
                    FACILITADOR = {$_data['facilitador']},
                    VIGENTE = {$_data['vigente']},
                    ESTADO = {$_data['estado']},
                    COMENTARIO = '{$_data['comentario']}',
                    MANUAL = {$_data['manual']},
                    VIGENTE_HASTA = '{$_data['vigentehasta']}',
                    RESPONSABLE_GERENCIA = '{$_data['gestion']}',
                    SIGNO = '{$_data['signo']}'
                  WHERE
                    IDINDICADOR = $id
                  ";

         $result = $this->ado->query($sql);

         $this->addCampoIndicador($id);

         if(isset($result['Error'])){
             echo '<p class="error">No se puede modificar indicador.</p>';
         }else{
             echo '<p>Indicador actualizado satisfactoriamente.</p>';
         }
                    

    }

    private function existCode($code){
        $sql="SELECT * FROM INDICADORES WHERE CODIGO='$code'";
        $result = $this->ado->query($sql);
         if(isset($result['Error'])){
             return TRUE;
         }else{
             if(count($result)){
                 return TRUE;
             }else{
                 return FALSE;
             }
         }

    }


    private function addCampoExtra($idIndicador,$idcampo,$valor){
        
    }

    public function getCampos(){
        $sql = "SELECT * FROM CAMPOS_ADICIONALES";
        $result = $this->ado->query($sql);

        return $result;
    }


    public function addCampoTemp($idCampo,$valor){
        @session_start();
        if(!isset($_SESSION['CAMPOS_TEMPORALES'])){
            $_SESSION['CAMPOS_TEMPORALES'] = array();
        }else{
            if(!array_search($idCampo.'_'.$valor, $_SESSION['CAMPOS_TEMPORALES']))
                array_push($_SESSION['CAMPOS_TEMPORALES'], $idCampo.'_'.$valor);
            else
                echo '<p class="error">El campo ya está agregado en este indicador.</p>';
        }
    }


    public function getCampo($id){
        $sql = "SELECT * FROM CAMPOS_ADICIONALES WHERE IDCAMPO = $id";
        $result = $this->ado->query($sql);

        return $result;
    }

    public function extenderIngresoEjecutados($idIndicador){
        $fecha= date('Y-m-d');
        $sql = "INSERT INTO EXTENSIONES_META(IDINDICADOR,FECHA) VALUES($idIndicador,sysdate)";
        $result = $this->ado->query($sql);
        
        if(isset($result['Error'])){
            echo '<p class="error">NO SE HA PODIDO EXTENDER EL PLAZO PARA ESTE INDICADOR</p>';
         }else{
             echo '<p><b>Se ha extendido el plazo por 3 d&iacute;as a partir de hoy.</b></p>';
         }
    }


    public function removeCampo($indice){
        if(isset($_SESSION['CAMPOS_TEMPORALES'])){
            unset($_SESSION['CAMPOS_TEMPORALES'][$indice]);
        }
    }


    ///////////////////////////////////////////////////////////////
    //FUNCIONES DE FILTROS
    ///////////////////////////////////////////////////////////////

    public function agruparPor($criterio){

        echo "<p>Agrupado por $criterio</p>";

        $sql = "SELECT * FROM INDICADORES ORDER BY $criterio";
        $result = $this->ado->query($sql);
        return $result;
    }

    public function buscarPor($criterio,$valor){
        echo "<p>Busqueda de $valor en $criterio</p>";
        $sql = "SELECT * FROM INDICADORES WHERE $criterio LIKE '%$valor%' ORDER BY CODIGO";
        $result = $this->ado->query($sql);
        return $result;
    }

    //////////////////////////////////////////////////////////////////

    public function addCampoIndicador($idIndicador){
        if(isset($_SESSION['CAMPOS_TEMPORALES'])){

            foreach($_SESSION['CAMPOS_TEMPORALES'] as $value){
                $datos = explode('_', $value);
                if(count($datos == 2)){
                    //$sql = "SELECT * FROM INDICADOR_CAMPOS WHERE IDCAMPO = {$datos[0]} AND IDINDICADOR = $idIndicador";
                    $sql = "DELETE FROM INDICADOR_CAMPOS WHERE IDINDICADOR = $idIndicador";
                    $existe = $this->ado->query($sql);

                    /*if(count($existe)){
                        //es un update
                        $sql = "UPDATE INDICADOR_CAMPOS SET VALOR = {$datos[1]} WHERE IDCAMPO = {$datos[0]} AND IDINDICADOR = $idIndicador";
                        $result = $this->ado->query($sql);

                    }else*/{
                        //es un insert
                        $sql = "INSERT INTO INDICADOR_CAMPOS(IDCAMPO,IDINDICADOR,VALOR) VALUES({$datos[0]},$idIndicador,'{$datos[1]}')";
                        $result = $this->ado->query($sql);
                    }
                }
            }
            //borrar los campos ingresados
            unset($_SESSION['CAMPOS_TEMPORALES']);
            
        }else{
            echo '<p>No hay campos adicionales que agregar.</p>';
        }
    }

}