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

$idIndicador = $_GET['idIndicador'];

$indicadorDatos = $indicador->getIndicador($idIndicador);
//////////////////////////////////////////////////////////
$frec = $frecuencia->getFrecuencia($indicadorDatos[0]['FRECUENCIA']);
$result = $arbol->getAllValues($idIndicador);
?>
<h2>Valores ingresados para <?php echo $indicadorDatos[0]['NOMBRE'];?></h2>
<p>Frecuencia <?php echo $frec[0]['NOMBRE'];?></p>

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
                echo "<td>".number_format($item['VALOR_META'])."</td>";
                echo "<td>".number_format($item['VALOR_EJECUTADO'])."</td>";
            echo '</tr>';
        }

    
    ?>

</table>
<p><a onclick="sendPage('null','modules/arbolIndicadores/home.php','main_content');" href="#"><= Retornar</a></p>