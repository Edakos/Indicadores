<?php

/*
 * Controlador del modulo q genera el arbol de indicadores
 * Jorge Tenorio
 * 01-03-2011
 * MUSHOQ
 */

@session_start();
require_once($_SESSION['path'].'/conf/includes.php');
require_once($_SESSION['path'].'/modules/despliegue/launch.php');

class ArbolIndicadores{


    private $ado;
    private $despliegue;
    private $niveles;

    public function  __construct() {
        $this->ado = new Ado();
        $this->despliegue = new Despliegue();
        $this->niveles = 0;
    }

    public function getObjetivosTree($id=0){

        //obetener todos los hijos de padre del id

        $sql = "SELECT * FROM OBJETIVO WHERE OBJETIVO_PADRE=$id";
        $result = $this->ado->query($sql);

        foreach($result as $depData){

            $color = $this->color();
            $this->niveles ++;
            echo '<p>';

            $deep = $this->Objetivosprofundidad($depData['IDOBJETIVO']);
            for($i=0;$i<$deep;$i++)
                echo '&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;';

            echo '<img src="'.$_SESSION['url'].'/images/fizq.gif">';

            $agregar= '<a href="#" onClick="sendPage(\'null\',\'modules/arbolIndicadores/addIndicador.php?addInd='.$depData['IDOBJETIVO'].'\',\'mod_content_user\');"><img src="'.$_SESSION['url'].'/images/agregar.gif"></a>';
            echo '<a href="#" style="border:#4F8DD2 1px solid; padding:4px; margin-top:2px; background-color:'.$color.'; color:orange;" onClick="sendPage(\'null\',\'modules/objetivos/addObjetivo.php?edit='.$depData['IDOBJETIVO'].'\',\'mod_content_user\');"><b>'.$depData['NOMBRE'].'</b></a> '.$agregar.'</p>';

            $this->getObtivoIndicadorTree($depData['IDOBJETIVO'],$deep);


            $this->getObjetivosTree($depData['IDOBJETIVO']);
        }

    }


    private function Objetivosprofundidad($id=0){
        //obtener la profundidad de un padre

        $deep = 0;

        while($id > 0){
            $sql = "SELECT OBJETIVO_PADRE FROM OBJETIVO WHERE IDOBJETIVO=$id";
            $newId = $this->ado->query($sql);

            $id = $newId[0]['OBJETIVO_PADRE'];
            $deep++;
        }

        return $deep;
    }

///////////////////////////////////////////////////////////////////////////////
//////////////////////////////////////////////////////////////////////////////

    public function getAvailableIndicadores(){
        $sql = "SELECT * FROM INDICADORES WHERE VIGENTE = 1 AND ESTADO =1 AND IDINDICADOR NOT IN(SELECT DISTINCT IDINDICADOR FROM ARBOL_INDICADOR)";
        $result = $this->ado->query($sql);
        return $result;
    }
    

    public function generarMetasEntry($idIndicador){

        $sql = "SELECT * FROM INDICADORES WHERE IDINDICADOR=$idIndicador";
        $result = $this->ado->query($sql);

        $frecuencia = $result[0]['DESPLIEGUE'];

        $enNivel = $this->despliegue->getNodesByLevel($frecuencia);

        $anio = date('Y');

        //obtener el periodo de frecuencia
        $sql = "SELECT * FROM FRECUENCIA WHERE IDFRECUENCIA = {$result[0]['FRECUENCIA']}";
        $resultFrecuencia = $this->ado->query($sql);
        $periodo = $resultFrecuencia[0]['MESES'];


        $mesesVigentes = $this->periodosModificables($periodo);


        $segmentos = explode(',', $enNivel);
        echo '<p><b>Ingrese los valores metas para un despliegue '.$this->getDivisionName($frecuencia).'</b></p>';
        echo '<p><b>Frecuencia '.$resultFrecuencia[0]['NOMBRE'].'</b></p>';
        echo '<table>';

        //encabezado
        echo '<tr class="trImpar"><td></td>';
        $mes = 0;
        for($ii=1;$ii <= 12/$periodo;$ii++ ){
                $mes += $periodo;

                if(in_array(($mes + 1), $mesesVigentes) && ($this->monthOnTime($mes +1)))
                    echo "<td><p>Per&iacute;odo $ii</p></td>";
            }
        echo '</tr>';
        ///////////////////////////////////////////////////////////////////////
        $iii=0;
        for($i=0; $i < count($segmentos);$i++){
            if($iii%2)
                $clase = "trImpar";
            else
                $clase = "trPar";
            $iii++;

            echo '<tr class="'.$clase.'">';
            echo "<td><p>".$this->getDespliegueName($segmentos[$i])."</p></td>";
            //imprimir la frecuencia
            $mes = 0;
            for($ii=1;$ii <= 12/$periodo;$ii++){
                $mes += $periodo;

                if(in_array(($mes +1), $mesesVigentes) && ($this->monthOnTime($mes +1))){
                    //obtener los datos si existen
                    $sql = "SELECT * FROM ARBOL_INDICADOR_VALORES WHERE IDINDICADOR= $idIndicador AND IDDESPLIEGUE = $segmentos[$i] AND PERIODO= $ii AND ANIO =$anio";
                    $valorMeta = $this->ado->query($sql);
                    $value = "";
                    if(count($valorMeta))
                        $value = number_format ($valorMeta[0]['VALOR_META']);
                    
                    echo '<td><p><input type="text" name="valorMeta_'.$segmentos[$i].'_'.$ii.'" value="'.$value.'" size="6"/></p></td>';
                    
                }
            }
               
            echo '</tr>';

           
        }
        
        echo '</table> <p></p>';
         echo'<table><tr class="trPar">
                        <td>
                            <p>Aplicar único valor a todos los ingresos :</p>
                        </td>
                        <td>
                            <input type="text" name="valTodos" id="valTodos" value="0" size="6" /> <input type="button" value="Asignar" onclick="setValueToAllInputs($(\'#valTodos\').val(),\'text\');"/>
                        </td>
                    </tr></table>';
    }

    public function getDespliegueName($id){
        $sql = "SELECT NOMBRE FROM DESPLIEGUE WHERE IDDESPLIEGUE = $id";
        $result = $this->ado->query($sql);
        return $result[0]['NOMBRE'];
    }

    public function getDivisionName($id){
        $sql = "SELECT NOMBRE FROM SEGMENTACION WHERE NIVEL = $id";
        $result = $this->ado->query($sql);
        return $result[0]['NOMBRE'];
    }


    public function getObtivoIndicadorTree($idObjetivo,$espacios){
        //generar el árbol de indicadores a partir del objetivo
        $sql = "SELECT IDINDICADOR,PONDERACION FROM ARBOL_INDICADOR WHERE IDOBJETIVO = $idObjetivo AND INDICADOR_PADRE IS NULL";
        $result = $this->ado->query($sql);
        ////////////////////////////////////////////////////////////////////////

        foreach($result as $item){

            $color = $this->color();
            $this->niveles ++;

            echo '<p>';
            echo '&nbsp;&nbsp';
            
            
            $agregar= '<a href="#" onClick="sendPage(\'null\',\'modules/arbolIndicadores/addIndicador.php?addIndHijo='.$item['IDINDICADOR'].'\',\'mod_content_user\');"><img src="'.$_SESSION['url'].'/images/agregar.gif"></a>';
            $valores = '<a href="#" onClick="sendPage(\'null\',\'modules/arbolIndicadores/allValues.php?idIndicador='.$item['IDINDICADOR'].'\',\'mod_content_user\');"><img src="'.$_SESSION['url'].'/images/vermas.gif"></a>';

            $eliminar = "";
            if(!$this->tieneHijosIndicador($item['IDINDICADOR'])){
                $eliminar = '<a href="#" onClick="if(confirm(\'Se se eliminaran las valores ingresados y sera necesario volver a ponderar, desea continua?\')) {sendPage(\'null\',\'modules/arbolIndicadores/home.php?delete='.$item['IDINDICADOR'].'\',\'mod_content_user\');}"><img src="'.$_SESSION['url'].'/images/icontrash.gif"></a>';
            }
            $editar = '<a href="#" onClick="sendPage(\'null\',\'modules/arbolIndicadores/editMetas.php?id='.$item['IDINDICADOR'].'\',\'mod_content_user\');"><img src="'.$_SESSION['url'].'/images/detalle.gif"></a>';
            
            echo $editar.' '.$valores.' '.$eliminar;
            
            for($i=0;$i<$espacios;$i++)
                echo '&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;';

            echo '<img src="'.$_SESSION['url'].'/images/fizq.gif">';
            
            echo '<a href="#" style="border:#4F8DD2 1px solid; padding:4px; margin-top:2px; background-color:'.$color.'; color:white;" onClick="sendPage(\'null\',\'modules/indicadores/addIndicador.php?edit='.$item['IDINDICADOR'].'\',\'mod_content_user\');">'.$this->getIndicadorName($item['IDINDICADOR']).' ('.number_format($item['PONDERACION']).')</a> '.$agregar.'</p>';
            $this->getIndicadoresTree($item['IDINDICADOR'],$espacios);
        }
        

    }


    private function getIndicadoresTree($idIndicador,$espacios){
         //obetener todos los hijos de padre del id

        $sql = "SELECT IDINDICADOR,PONDERACION FROM ARBOL_INDICADOR WHERE INDICADOR_PADRE = $idIndicador";
        $result = $this->ado->query($sql);

        foreach($result as $depData){

            $color = $this->color();
            $this->niveles ++;
            
            
            $agregar= '<a href="#" onClick="sendPage(\'null\',\'modules/arbolIndicadores/addIndicador.php?addIndHijo='.$depData['IDINDICADOR'].'\',\'mod_content_user\');"><img src="'.$_SESSION['url'].'/images/agregar.gif"></a>';
            $valores = '<a href="#" onClick="sendPage(\'null\',\'modules/arbolIndicadores/allValues.php?idIndicador='.$depData['IDINDICADOR'].'\',\'mod_content_user\');"><img src="'.$_SESSION['url'].'/images/vermas.gif"></a>';
            $editar = '<a href="#" onClick="sendPage(\'null\',\'modules/arbolIndicadores/editMetas.php?id='.$depData['IDINDICADOR'].'\',\'mod_content_user\');"><img src="'.$_SESSION['url'].'/images/detalle.gif"></a>';

            $eliminar = "";
            if(!$this->tieneHijosIndicador($depData['IDINDICADOR'])){
                $eliminar = '<a href="#" onClick="if(confirm(\'Se se eliminaran las valores ingresados y sera necesario volver a ponderar, desea continua?\')) {sendPage(\'null\',\'modules/arbolIndicadores/home.php?delete='.$depData['IDINDICADOR'].'\',\'mod_content_user\');}"><img src="'.$_SESSION['url'].'/images/icontrash.gif"></a>';
            }

            echo '<p>';
            echo '&nbsp;&nbsp&nbsp;&nbsp';
            echo $editar.' '.$valores.' '.$eliminar;
            
             for($i=0;$i<$espacios;$i++)
                echo '&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;';
            echo '<img src="'.$_SESSION['url'].'/images/fizq.gif">';
            echo '<a href="#" style="border:#4F8DD2 1px solid; padding:4px; margin-top:2px; background-color:'.$color.'; color:white;" onClick="sendPage(\'null\',\'modules/indicadores/addIndicador.php?edit='.$depData['IDINDICADOR'].'\',\'mod_content_user\');">'.$this->getIndicadorName($depData['IDINDICADOR']).' ('.number_format($depData['PONDERACION']).')</a> '.$agregar.'</p>';


            $this->getIndicadoresTree($depData['IDINDICADOR'],++$espacios);
        }
    }


    private function tieneHijosIndicador($id){
        $sql = "SELECT * FROM ARBOL_INDICADOR WHERE INDICADOR_PADRE = $id";
        $result = $this->ado->query($sql);

        if(!isset($result['Error'])){
            if(count($result))
                return TRUE;
            else
                return FALSE;
        }else{
            return TRUE;
        }

    }


    ////////////////////////////////////////////////////////////////////////////
    ////////////////////////////////////////////////////////////////////////////

    public function addMetas($idPadre,$tipoPadre,$data=array()){

        $indicador = $data['indicador'];
        $anio = date('Y');
        
        if($tipoPadre == "O"){
            //el padre es un objetivo
            //solo se debe proceder a insetar el indicador hijo

            $sql = "INSERT INTO ARBOL_INDICADOR (IDOBJETIVO,IDINDICADOR) VALUES($idPadre,$indicador)";
            $result = $this->ado->query($sql);

            if(!isset($result['Error'])){
                    //PROCEDER A INSETAR LOS VALORES POR DESPLIEGUE
                    $sql = "SELECT MAX(IDARBOL) AS ID FROM ARBOL_INDICADOR";
                    $result = $this->ado->query($sql);
                    $idArbol = $result[0]['ID'];

                    foreach($data as $field => $value){
                        $temp = explode("_",$field);

                        if(count($temp)==3){
                            $idDespliegue = $temp[1];
                            $periodo = $temp[2];
                            $sql = "INSERT INTO ARBOL_INDICADOR_VALORES(IDINDICADOR,IDDESPLIEGUE,IDARBOL,VALOR_META,PERIODO,ANIO) VALUES(
                                            $indicador,
                                            {$temp[1]},
                                            $idArbol,
                                            $value,
                                            $periodo,
                                            $anio
                                     )";
                          if(strlen($value))
                            $result = $this->ado->query($sql);
                           
                        }
                    }

                    //fin del ingreso de valores meta
                    echo "<script>sendPage('null','modules/arbolIndicadores/ponderar.php?idPadre=$idPadre&tipoPadre=O','mod_content_user');</script>";

            }else{
                //HAY UN ERROR
                echo "error!";
            }
        }

        if($tipoPadre == "I"){

            $sql = "SELECT IDOBJETIVO AS ID FROM ARBOL_INDICADOR WHERE IDINDICADOR=$idPadre";
            $result = $this->ado->query($sql);
            $idObjetivo = $result[0]['ID'];

            $sql = "INSERT INTO ARBOL_INDICADOR (IDOBJETIVO,IDINDICADOR,INDICADOR_PADRE) VALUES($idObjetivo,$indicador,$idPadre)";
            $result = $this->ado->query($sql);

            if(!isset($result['Error'])){


                //PROCEDER A INSETAR LOS VALORES POR DESPLIEGUE
                    $sql = "SELECT MAX(IDARBOL) AS ID FROM ARBOL_INDICADOR";
                    $result = $this->ado->query($sql);
                    $idArbol = $result[0]['ID'];

                    foreach($data as $field => $value){
                        $temp = explode("_",$field);

                        if(count($temp)==3){

                            $idDespliegue = $temp[1];
                            $periodo = $temp[2];
                            
                            $sql = "INSERT INTO ARBOL_INDICADOR_VALORES(IDINDICADOR,IDDESPLIEGUE,IDARBOL,VALOR_META,PERIODO,ANIO) VALUES(
                                            $indicador,
                                            {$temp[1]},
                                            $idArbol,
                                            $value,
                                            $periodo,
                                            $anio
                                     )";
                          if(strlen($value))
                            $result = $this->ado->query($sql);

                        }
                    }

                    //fin del ingreso de valores meta
                    //se debe proceder a ponderar
                    echo "<script>sendPage('null','modules/arbolIndicadores/ponderar.php?idPadre=$idPadre&tipoPadre=I','mod_content_user');</script>";
            
            }else{
                echo $result['Error'];
            }

        }

  
       

    }


    public function getIndicadorName($idIndicador){
        $sql = "SELECT NOMBRE FROM INDICADORES WHERE IDINDICADOR = $idIndicador";
        $result = $this->ado->query($sql);
        return $result[0]['NOMBRE'];
    }


    public function deleteIndicadorTree($id){

        $sql = "SELECT * FROM ARBOL_INDICADOR WHERE IDINDICADOR=$id";
        $indicador = $this->ado->query($sql);

        if(is_null($indicador[0]['INDICADOR_PADRE'])){
            $idPadre = $indicador[0]['IDOBJETIVO'];
            $tipoPadre = 'O';
        }else{
            $idPadre = $indicador[0]['INDICADOR_PADRE'];
            $tipoPadre = 'I';
        }

        //primero elimnar todos los valores q pueda tener
        $sql = "DELETE FROM ARBOL_INDICADOR_VALORES WHERE IDINDICADOR = $id";
        $result = $this->ado->query($sql);

        if(!isset($result['Error'])){
            $sql = "DELETE FROM ARBOL_INDICADOR WHERE IDINDICADOR = $id";
            $result = $this->ado->query($sql);
            if(!isset($result['Error'])){
                echo '<p>Indicador desvinculado correctamente.</p>';
                //AQUI ENVIAR A PONDERAR NUEVAMENTE
                
                echo '<script>sendPage(\'null\',\'modules/arbolIndicadores/ponderar.php?idPadre='.$idPadre.'&tipoPadre='.$tipoPadre.'\',\'mod_content_user\');</script>';
            }else{
                echo '<p class="error">Se han eliminado los valores pero no ha sido posible desvincular el indicador.</p>';
                
            }
            
        }else{
            echo '<p class="error">En este momento no es posible eliminar los valores de este indicador.</p>';
        }
        

    }



    public function getPonderaciones($idPadre,$tipoPadre){

        if($tipoPadre== 'O'){
            $sql ="SELECT * FROM ARBOL_INDICADOR WHERE IDOBJETIVO = $idPadre AND INDICADOR_PADRE IS NULL";
        }

        if($tipoPadre=='I'){
            $sql ="SELECT * FROM ARBOL_INDICADOR WHERE INDICADOR_PADRE = $idPadre";
        }

        $result = $this->ado->query($sql);

        if(count($result)){
            echo '<p><b>Ponderaciones actuales en este rango</b></p>';
            echo '<table>';
            $i=0;
            foreach($result as $item){
                if($i%2)
                    $clase = "trImpar";
                else
                    $clase = "trPar";
                $i++;

                echo '<tr class="'.$clase.'">';
                    echo "<td><p>".$this->getIndicadorName($item['IDINDICADOR'])."</p></td>";
                    echo '<td><p><input type="text" name="ponderacion_'.$item['IDINDICADOR'].'" value="" size="6"/></p></td>';
                echo '</tr>';
            }

            echo '<tr>
                            <td colspan="2" align="center"><input type="button" value="Aplicar" name="aplicar" onClick="
                                if(validaInputs(\'text\')){
                                    t = validarPonderacion();
                                    if(t != 100){
                                        alert(\'El total de las ponderaciones debe ser 100.\');
                                    }else{
                                        sendPage(\'ponderar\',\'modules/arbolIndicadores/home.php?ponderar=1\',\'mod_content_user\');
                                    }
                                }else{
                                    alert(\'Debe ingresar las ponderaciones respectivas para cada indicador.\');
                                }
                                "/></td>
                       </tr>';
            echo '</table>';
        
        }else{
            echo '<a href="#" onClick="sendPage(\'null\',\'modules/arbolIndicadores/home.php?ponderar=1\',\'mod_content_user\');">No existen ponderaciones que asiganar, click para continuar.</a>';
        }
    }



    public function setPonderaciones ($data){
        //se actualizaran la podenraciones de todos los indicadores
        //dentro del arbol
        $cont = 0;
        $error=0;
        foreach($data as $field => $value){
            $temp = explode("_",$field);

               if(count($temp)==2){
                    $sql = "UPDATE ARBOL_INDICADOR SET PONDERACION = $value  WHERE IDINDICADOR = {$temp[1]}";
                    $result = $this->ado->query($sql);

                    if(isset($_GET['Error'])){
                        $error++;
                    }else{
                        $cont++;
                    }
                
               }
        }

        echo "<p>Se han ingresado con éxito $cont ponderaciones de indicadores, han fallado $error</p>";


    }


    private function periodosModificables($frecuencia){

        $mesActual = date('m');
        $meses = array();
      

        for($i=0;$i <= 12; $i+=$frecuencia){
            if(($i +1) >= $mesActual)
                array_push($meses, ($i+1));
        }
        
        return $meses;
    }


    private function monthOnTime($mes){
        ////////////////////////////////////////////////////////////////////////
        
        $mesActual = date('m');
        $diaActual = date('d');
        ////////////////////////////////////////////////////////////////////////
        //obtener los parametros de ingreso de indicadores
        $sql = "SELECT * FROM PARAMETROS_INDICADORES";
        $parametros = $this->ado->query($sql);

        $diaInicio = $parametros[0]['DIA_INICIO'];
        $numDiasIngreso = $parametros[0]['DIAS_INGRESO'];
        $maximoIngreso = $parametros[0]['MAXIMO_INGRESO'];

        $totalDiasIngreso = $numDiasIngreso + $maximoIngreso;
        $diaMaximo = $diaInicio + $totalDiasIngreso + $diaInicio -1;
        ////////////////////////////////////////////////////////////////////////
        //verificar si aun se esta a tiempo para ingresar datos
        ////////////////////////////////////////////////////////////////////////
        if($mes == $mesActual){
            //estoy en el mes de ingreso de datos
            if(($diaActual >= $diaInicio) && ($diaActual <= $diaMaximo))
                return TRUE;
            else
                return FALSE;
        }else if($mes > $mesActual){
            return TRUE;
        }else{
            return FALSE;
        }
    }

    public function updateMetasValues($data,$indicador){

        $anio = date('Y');
        require_once($_SESSION['path'].'/modules/controlCambios/launch.php');

        $cambios = new ControlCambios();

        foreach ($data as $field => $value){
            $temp = explode("_",$field);

                        if(count($temp)==3){

                            $idDespliegue = $temp[1];
                            $periodo = $temp[2];



                            $sql = "UPDATE ARBOL_INDICADOR_VALORES SET VALOR_META = $value WHERE 
                                            IDINDICADOR = $indicador AND 
                                            IDDESPLIEGUE = $idDespliegue AND
                                            PERIODO = $periodo AND
                                            ANIO = $anio";
                          if(strlen($value)){
                            $result = $this->ado->query($sql);
                            if(!isset($result["Error"])){
                                $cambios->addChagne("MODIFICACIÓN DE VALOR META EN EL INDICADOR $indicador DEL PERIODO $periodo PARA EL AÑO $anio", 0, $value,$data['justificacion'],$indicador);
                            }
                          }

                        }
        }

    }


    public function getAllValues($idIndicador){

        $anio = date('Y');

        $sql = "SELECT * FROM ARBOL_INDICADOR_VALORES WHERE IDINDICADOR=$idIndicador AND ANIO = $anio ORDER BY IDDESPLIEGUE,PERIODO";
        $result = $this->ado->query($sql);

       return $result;
    }

    private function color(){
       $lista = array('#346C99','#346C99','#5886AB','#6993B4','#779DBB','#779DBB','#8FAEC7','#9DB8CE','#ADC3D6','#B9CCDC','#C5D5E2','#D1DEE8','#D1DEE8','#DEE7EF','#E9EFF4','#F3F7F9','#FFFFFF');
       $totalColores = count($lista)-1;

       $color = rand(0, $totalColores);

       return $lista[$this->niveles];

    }

}