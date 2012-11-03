<?php
/**
 * MODULO DE INGRESO DE DATOS PARA LOS USUARIOS FACILITADORES
 * @autor Jorge Tenorio
 * @since 09/03/2011
 */


require_once($_SESSION['path'].'/conf/includes.php');
require_once($_SESSION['path'].'/modules/despliegue/launch.php');

class DataEntry{

    private $ado;
    private $despliegue;
    Private $anio;

    public function __construct(){
        $this->ado = new Ado();
        $this->despliegue = new Despliegue();
        $this->anio = date('Y');
    }

    public function generateMenu(){
        //obtener los items de menu y devolver el html listo
        
      
        
        $html = '<div id="topMenu"><ul><li class="top_izq">';
        
        $html .= '</div>';

        return $html;
    }
    
    public function generatePanel(){
        //obtener los items de menu y devolver el html listo
        //require_once($_SESSION["path"].'/modules/ado/launch.php');
        


        $data = file($_SESSION["path"].'/conf/modules.conf');



        $html = '<ul id="panelMenu">';
        foreach($data as $module){
            $modParam = explode('|',$module);
            $html .= "<li><a  onclick=\"sendPage('null','modules/{$modParam[1]}','main_content');\"><img src=\"{$_SESSION['url']}/images/{$modParam[2]}\">{$modParam[0]}</a></li>";
        }
        $html .= '</ul>';

        return $html;
    }

    public function generateTopInfo(){
        //obtener los items de menu y devolver el html listo
        $html = '<div id="topInfo"><ul >';
        $html .= '<li id="user">'.htmlentities($_SESSION['USER_nombre']).'</li>';
        if(isset($_SESSION['DISTRIB_nombre'])){
            $html .= '<li id="empresa">'.$_SESSION['DISTRIB_nombre'].'</li>';
        }
        $html .= '<li id="logout"><a href="modules/login/launch.php?logout=1"><img src="images/logOut.png">Salir</a></li>';
        $html .= '<li class="fecha">'.date('l jS \of F Y').'</li><li class=""></li></ul></div>';

        return $html;
    }

    public function getIndicadoresNeedData(){

        $sql = "SELECT * FROM INDICADORES WHERE FACILITADOR = {$_SESSION['USER_id']} AND VIGENTE = 1 AND ESTADO =1 AND MANUAL= 1 AND IDINDICADOR IN(SELECT IDINDICADOR FROM ARBOL_INDICADOR_VALORES)";
        $result = $this->ado->query($sql);
        return $result;
    }

    public function generarEjecutadosEntry($idIndicador){

        $extendido = TRUE;
        $periodoExtendido = NULL;

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
        //print_r($mesesVigentes);
       
        $segmentos = explode(',', $enNivel);
        echo '<p><b>Ingrese los valores ejecutados por '.$this->getDivisionName($frecuencia).'</b></p>';
        echo '<table>';

        //encabezado
        echo '<tr class="tableTitle"><td></td>';
        $mes = 0;
        for($ii=1;$ii <= 12/$periodo;$ii++ ){
                $mes += $periodo;
                
                $mesNamne = $this->mesName($mes);

                if(in_array(($mes + 1), $mesesVigentes) && ($this->monthOnTime($mes +1)))
                    echo "<td><p>Per&iacute;odo $ii - $mesNamne</p></td>";
                
            }
            
            //verificar si esta extendido el plazo
            //SI esta con plazo extra
            $mes=0;
            if($this->estaExtendido($idIndicador)){
                $fechaExtendido = $this->fechaExtendido($idIndicador)-1;
                for($ii=1;$ii <= 12/$periodo;$ii++ ){
                    $mes += $periodo;
                    $mesNamne = $this->mesName($mes);
                    if(in_array((date('m')-1), $mesesVigentes) && $extendido && $mes==$fechaExtendido){
                        echo "<td><p>Per&iacute;odo $ii - $mesNamne (extendido)</p></td>";
                        $extendido = FALSE;
                        $periodoExtendido = $ii;
                    }                
                }
                    
            }
            
        echo '</tr>';
        ///////////////////////////////////////////////////////////////////////
        $extendido = TRUE;
        for($i=0; $i < count($segmentos);$i++){

            if($i%2)
                $clase = "trImpar";
            else
                $clase = "trPar";

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
                        $value = number_format ($valorMeta[0]['VALOR_EJECUTADO'],3);

                    echo '<td><p><input type="text" name="valorEjecutado_'.$segmentos[$i].'_'.$ii.'" value="'.$value.'" size="6" maxlength="7"/></p></td>';

                }else{
                    //SI esta con plazo extra
                    if(in_array((date('m')), $mesesVigentes) && $this->estaExtendido($idIndicador) && $periodoExtendido == $ii){
                        $sql = "SELECT * FROM ARBOL_INDICADOR_VALORES WHERE IDINDICADOR= $idIndicador AND IDDESPLIEGUE = $segmentos[$i] AND PERIODO= $ii AND ANIO =$anio";
                        $valorMeta = $this->ado->query($sql);
                        $value = "";
                        if(count($valorMeta))
                            $value = number_format ($valorMeta[0]['VALOR_EJECUTADO'],3);

                        echo '<td><p><input type="text" name="valorEjecutado_'.$segmentos[$i].'_'.$ii.'" value="'.$value.'" size="6" maxlength="7"/></p></td>';
                       
                    }
                }
            }

            echo '</tr>';
        }
        echo '</table>';
    }


    public function periodosModificables($frecuencia){

        $mesActual = date('m')-1;
        $meses = array();


        for($i=0;$i <= 12; $i+=$frecuencia){
            if(($i +1) >= $mesActual)
                

                array_push($meses, ($i+1));
        }

        return $meses;
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


    public function monthOnTime($mes){
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
        }else{
            return FALSE;
        }
    }


    public function proximoIngreso($meses = array()){
        $mes = date('m');

        foreach($meses as $valor){
            if($valor >= $mes){
                $next = $valor;
                break;
            }
        }

        return $this->getMonthName($next);

    }


    private function getMonthName($Month){
        $strTime=mktime(1,1,1,$Month,1,date("Y"));
        return date("F",$strTime);
    }


    public function addValoresEjecutados($data,$indicador,$update=false){
        $anio = date('Y');
        $fecha = date('Y-m-d');
        require_once($_SESSION['path'].'/modules/controlCambios/launch.php');
        $cambios = new ControlCambios();
        
        foreach ($data as $field => $value){
            $temp = explode("_",$field);

                        if(count($temp)==3){

                            $idDespliegue = $temp[1];
                            $periodo = $temp[2];

                            $sql = "UPDATE ARBOL_INDICADOR_VALORES SET VALOR_EJECUTADO = $value, FECHA_EJECUTADO = sysdate WHERE
                                            IDINDICADOR = $indicador AND
                                            IDDESPLIEGUE = $idDespliegue AND
                                            PERIODO = $periodo AND
                                            ANIO = $anio";
                          if(strlen($value)){
                            $result = $this->ado->query($sql);
                            if(!isset($result["Error"]) && $update){
                                $cambios->addChagne("MODIFICACIÓN DE VALOR EJECUTADO EN EL INDICADOR $indicador DEL PERIODO $periodo PARA EL AÑO $anio", 0, $value,$data['justificacion'],$indicador);
                            }
                          }

                        }
        }
    }


    public function yaIngresadoValores($idIndicador,$frecuencia){
        $periodo = $this->numeroPeriodo($frecuencia);
        if($periodo>0){

               $sql = "SELECT * FROM ARBOL_INDICADOR_VALORES WHERE VALOR_EJECUTADO IS NOT NULL AND IDINDICADOR = $idIndicador
                    AND ANIO = $this->anio AND PERIODO = $periodo";

                $ingresados = $this->ado->query($sql);

                if(count($ingresados))
                    return TRUE;
                else
                    return FALSE;
        }else{
            return TRUE;
        }
            
    }

    public function numeroPeriodo($frecuencia){
        $mesActual = date('m');
        $meses = array();


        for($i=0;$i <= 12; $i+=$frecuencia){
            array_push($meses, ($i+1));
        }

        $periodo = array_search($mesActual, $meses);

         //print_r($meses);

            if(!strlen($periodo)){
               
                $periodo  = 0;
            }

            return $periodo;
    }


    public function getIndicadorName($idIndicador){
        $sql = "SELECT NOMBRE FROM INDICADORES WHERE IDINDICADOR = $idIndicador";
        $result = $this->ado->query($sql);
        return $result[0]['NOMBRE'];
    }

     public function agruparPor($criterio){

        echo "<p>Agrupado por $criterio</p>";

        $sql = "SELECT * FROM INDICADORES WHERE FACILITADOR = {$_SESSION['USER_id']} AND VIGENTE = 1 AND ESTADO =1 ORDER BY $criterio";
        $result = $this->ado->query($sql);
        return $result;
    }

    public function buscarPor($criterio,$valor){
        echo "<p>Busqueda de $valor en $criterio</p>";
        $sql = "SELECT * FROM INDICADORES WHERE $criterio LIKE '%$valor%' AND FACILITADOR = {$_SESSION['USER_id']} AND VIGENTE = 1 AND ESTADO =1";
        $result = $this->ado->query($sql);
        return $result;
    }


    public function estaExtendido($idIndicador){

        $sql = "select To_Char(fecha,'DD') as DIA,To_Char(fecha,'MM') as MES,To_Char(fecha,'YYYY') as ANIO from extensiones_meta 
            WHERE IDINDICADOR = $idIndicador AND FECHAINGRESO IS NULL AND ID IN(SELECT MAX(ID) from extensiones_meta 
                                        WHERE IDINDICADOR = $idIndicador)";
        $result = $this->ado->query($sql);

        if(count($result)){

            $date1 = $result[0]['ANIO'].'-'.$result[0]['MES'].'-'.$result[0]['DIA'];
            $date2 = date('Y-m-d');

            $diff = abs(strtotime($date2) - strtotime($date1));
            $years = floor($diff / (365*60*60*24));
            $months = floor(($diff - $years * 365*60*60*24) / (30*60*60*24));
            $days = floor(($diff - $years * 365*60*60*24 - $months*30*60*60*24)/ (60*60*24));

            if($days <= 3 && $months==0 && $years==0){
               
                return TRUE;
            }else{
                
                return FALSE;
            }

        }else{
            return FALSE;
        }
    }
    
    
    public function fechaExtendido($idIndicador){

            $sql = "SELECT to_char( FECHA, 'mm' ) as MES,FECHA FROM EXTENSIONES_META WHERE IDINDICADOR = $idIndicador AND FECHAINGRESO IS NULL ORDER BY FECHA DESC";
            $result = $this->ado->query($sql);
            //die($result[0]['MES']);
            return $result[0]['MES'];
     }
    
    private function mesName($mes){
        
        $meses = array('Enero','Febrero','Marzo','Abril','Mayo','Junio','Julio','Agosto','Septiembre','Octubre','Noviembre','Diciembre');
        
        if($mes > 12){
            $mes = $mes - 12;
        }
        
        return $meses[$mes-1];
    }
        
}



