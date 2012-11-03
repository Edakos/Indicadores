<?php
/*
 * vista del modulo del arbol de indicadores, vista de valores ingresados
* jtenorio
 * MUSHOQ
 * 22-03-2011
 */
@session_start();
require_once($_SESSION['path'].'/modules/arbolIndicadores/launch.php');
require_once($_SESSION['path'].'/modules/indicadores/launch.php');
require_once($_SESSION['path'].'/modules/despliegue/launch.php');
require_once($_SESSION['path'].'/modules/parametros/frecuencias/launch.php');

$arbol = new ArbolIndicadores();
$indicador = new Indicador();
$despliegue = new Despliegue();
$frecuencia = new Frecuencia();

if(!isset($_GET['anio'])){
    $anio = date('Y');
}else{
    $anio = $_GET['anio'];
}


$idIndicador = $_GET['idIndicador'];

$indicadorDatos = $indicador->getIndicador($idIndicador);
//////////////////////////////////////////////////////////
$frec = $frecuencia->getFrecuencia($indicadorDatos[0]['FRECUENCIA']);
$result = $arbol->getAllValues($idIndicador,$anio);
?>
<h2>Valores ingresados para <?php echo $indicadorDatos[0]['NOMBRE'];?></h2>
<p>Frecuencia <?php echo $frec[0]['NOMBRE'];?></p>
<p></p>
<table style="width: 300px;">
    <tr>
        <td ><p>A&ntilde;o de vizualizaci&oacute;n</p></td>
        <td><select name="anoIngreso" id="anoIngreso" onchange="
                sendPage('null','modules/arbolIndicadores/allValues.php?idIndicador=<?php echo $idIndicador;?>&anio='+$('#anoIngreso').val(),'mod_content_user');
            ">
                <option value="<?php echo date('Y');?>" <?php if(date('Y') == $anio) echo "selected";?>><?php echo date('Y');?></option>
                <option value="<?php echo date('Y') +1;?>" <?php if(date('Y')+1 == $anio) echo "selected";?>><?php echo date('Y') +1;?></option>
            </select>
        </td>
   </tr>
</table>
<p></p>

<p><a onclick="sendPage('null','modules/arbolIndicadores/home.php','main_content');" href="#"><= Retornar</a></p>
<table>
    <tr class="tableTitle">
        <td>Despliegue Geogr&aacute;fico</td>
        <td>Per&iacute;odo</td>
        <td>Valor Meta</td>
        <td>Valor Ejecutado</td>
       
    </tr>


    <?php
        $i=0;
        foreach($result as $item){
            if($i%2)
                $clase = "trImpar";
            else
                $clase = "trPar";
            $i++;
            $despInfo = $despliegue->getDivisionInfo($item['IDDESPLIEGUE']);

            echo '<tr class="'.$clase.'">';
                echo "<td>{$despInfo[0]['NOMBRE']}</td>";
                echo "<td>{$item['PERIODO']}</td>";
                echo "<td>".number_format($item['VALOR_META'],3)."</td>";
                echo "<td>".number_format($item['VALOR_EJECUTADO'],3)."</td>";
            echo '</tr>';
        }

    
    ?>

</table>
<p><a onclick="sendPage('null','modules/arbolIndicadores/home.php','main_content');" href="#"><= Retornar</a></p>