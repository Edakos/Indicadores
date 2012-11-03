<?php
/*
 * listado de indicadores creados
 * jtenorio
 * MUSHOQ
 * 15-02-2011
 */

@session_start();
include($_SESSION['path'].'/modules/indicadores/launch.php');
include($_SESSION['path'].'/modules/parametros/frecuencias/launch.php');
include($_SESSION['path'].'/modules/parametros/formula/launch.php');
include($_SESSION['path'].'/modules/despliegue/launch.php');
include($_SESSION['path'].'/modules/usuarios/launch.php');

$indicador = new Indicador();

if(isset($_GET['extend'])){
    $indicador->extenderIngresoEjecutados($_GET['extend']);
}elseif(isset($_GET['delete'])){
    $indicador->deleteIndicador($_GET['delete']);
}

///////////////////////////////////////////////////////////
if(isset($_GET['agrupar'])){
     $lista = $indicador->agruparPor($_POST['criterioAgrupar']);
}elseif(isset($_GET['buscar'])){
    $lista = $indicador->buscarPor($_POST['criterioAgrupar'],$_POST['valor']);
}else{
    $lista = $indicador->getIndicadores();
}

?>
<h2>Listado de Indicadores</h2>

<div id="filtroLista">
    <form name="groupBy" id="groupBy" method="POST" enctype="multipart/form-data" onsubmit="return false;">
    <table>
        <tr>
            <td>
                Agrupar por:
            </td>
            <td>
                <select name="criterioAgrupar">
                    <option value="NIVEL_ESTRATEGICO">Nivel</option>
                    <option value="TIPO">Tipo</option>
                    <option value="VIGENTE">Vigencia</option>
                    <option value="ESTADO">Estado</option>
                </select>
            </td>
            <td>
                <input type="button" value="Agrupar" name="doAgrupar" onClick="sendPage('groupBy','modules/indicadores/listIndicadores.php?agrupar=1','mod_content');"/>
            </td>
        </tr>
    </table>
    </form>
    <p></p>
    <form name="search" id="search" method="POST" enctype="multipart/form-data" onsubmit="return false;">
    <table>
        <tr>
            <td>
                Buscar por:
            </td>
            <td>
                <select name="criterioAgrupar">
                    <option value="CODIGO">C&oacute;digo</option>
                    <option value="NOMBRE">Nombre</option>
                    <option value="DEFINICION">Definici&oacute;n</option>                   
                </select>
            </td>
                <td>
                    <input type="text" name="valor" value="" size="29" onkeypress="validaEnter(event,'search');"/>
                </td>
            <td>
                <input type="button" value="Buscar" name="doAgrupar" onClick="sendPage('search','modules/indicadores/listIndicadores.php?buscar=1','mod_content');"/>
            </td>
        </tr>
    </table>
    </form>



</div>
<p></p>
<p></p>
<div style="height: 35px; overflow: hidden; z-index: 1000;">
<table>
   <thead>
    <tr class="tableTitle">
        <td ><p><b>C&Oacute;DIGO</b></p></td>
        <td ><p><b>NIVEL</b></p></td>
        <td ><p><b>NOMBRE</b></p></td>
        <td ><p><b>DEFINICI&Oacute;N</b></p></td>
        <!--<td><p><b>TIPO</b></p></td>!-->
        <td ><p><b>FORMULA</b></p></td>
        <td ><p><b>DESPLIEGUE</b></p></td>
        <td ><p><b>FRECUENCIA</b></p></td>
        <td ><p><b>VIGENTE</b></p></td>
        <td ><p><b>ESTADO</b></p></td>
        <td ><p><b>FACILITADOR</b></p></td>
        <td ></td>
        <td ></td>
        <td ></td>
    </tr>
   </thead> 
   

<!--<div style="height: 410px; overflow: auto; ">!-->

        <?php
        $frecuencia = new Frecuencia();
        $despliegue = new Despliegue();
        $usuario = new Usuarios();

        $i=0;
        foreach ($lista as $item){

            $frec = $frecuencia->getFrecuencia($item['FRECUENCIA']);
            $des = $despliegue->getDivisionInfo($item['DESPLIEGUE']);
            $us = $usuario->getUser($item['FACILITADOR']);

            if($item['VIGENTE'] == 1)
                $vigente = "Vigente";
            else
                $vigente = "No Vigente";

             if(  $item['ESTADO'] == 1)
                $estado = "Activo";
            else
                $estado = "Inactivo";


            if($i%2)
                $clase = "trImpar";
            else
                $clase = "trPar";
            $i++;
			
               echo '<tr class="'.$clase.'" style="height:0px;">';
               echo " <td ><p>{$item['CODIGO']}</p></td>
                    <td><p>{$item['NIVEL_ESTRATEGICO']}</p></td>
                    <td><p>{$item['NOMBRE']}</p></td>
                    <td><p>{$item['DEFINICION']}</p></td>
                    <!--<td><p>{$item['TIPO']}</p></td>!-->
                    <td><p>{$item['FORMULA']}</p></td>
                    <td><p>$nombre</p></td>
                    <td><p>{$frec[0]['NOMBRE']}</p></td>
                    <td><p>$vigente</p></td>
                    <td><p>$estado</p></td>
                    <td><p>{$us[0]['APELLIDO']} {$us[0]['NOMBRE']}</p></td>";
            echo      '<td><a title="Editar" href="#" onClick="sendPage(\'null\',\'modules/indicadores/addIndicador.php?edit='.$item['IDINDICADOR'].'\',\'mod_content\');"><img src="'.$_SESSION['url'].'/images/edit.gif"></a></td>';
            echo      '<td><a href="#" onClick="if(confirm(\'Desea eliminar este indicador?\')) {sendPage(\'null\',\'modules/indicadores/listIndicadores.php?delete='.$item['IDINDICADOR'].'\',\'mod_content\');}"><img src="'.$_SESSION['url'].'/images/icontrash.gif"></a></td>';
            echo      '<td><a title="Extender plazo de ingreso de valores meta."  href="#" onClick="if(confirm(\'Desea extender por 3 dias adicionales el ingreso de valores ejecutados?\')) {sendPage(\'null\',\'modules/indicadores/listIndicadores.php?extend='.$item['IDINDICADOR'].'\',\'mod_content\');}"><img src="'.$_SESSION['url'].'/images/alerta.png"></a></td>';
            echo      "</tr>";
        }
        ?>
    
   
    </table>
</div>

<div style="padding-top:0px; overflow: hidden; height: 450px; overflow: scroll; z-index: 1;">
<table>
    <thead>
    <tr class="tableTitle" style="height: 1px!important;">
        <td ><p style="color:#11408E;"><b>C&Oacute;DIGO</b></p></td>
        <td ><p style="color:#11408E;"><b>NIVEL</b></p></td>
        <td ><p style="color:#11408E;"><b>NOMBRE</b></p></td>
        <td ><p style="color:#11408E;"><b>DEFINICI&Oacute;N</b></p></td>
        <!--<td><p><b>TIPO</b></p></td>!-->
        <td ><p style="color:#11408E;"><b>FORMULA</b></p></td>
        <td ><p style="color:#11408E;"><b>DESPLIEGUE</b></p></td>
        <td ><p style="color:#11408E;"><b>FRECUENCIA</b></p></td>
        <td ><p style="color:#11408E;"><b>VIGENTE</b></p></td>
        <td ><p style="color:#11408E;"><b>ESTADO</b></p></td>
        <td ><p style="color:#11408E;"><b>FACILITADOR</b></p></td>
        <td ></td>
        <td ></td>
        <td ></td>
    </tr>
   </thead> 

<!--<div style="height: 410px; overflow: auto; ">!-->

        <?php
        $frecuencia = new Frecuencia();
        $despliegue = new Despliegue();
        $usuario = new Usuarios();
        $formula = new Formula();
        

        $i=0;
        foreach ($lista as $item){

            $frec = $frecuencia->getFrecuencia($item['FRECUENCIA']);
            $des = $despliegue->getDivisionInfo($item['DESPLIEGUE']);
            $us = $usuario->getUser($item['FACILITADOR']);
            $for = $formula->getFormula($item['FORMULA']);
            
           
            
            if(isset($for['Error']) || !count($for)){
                $for[0]['NOMBRE'] = $item['FORMULA'];
            }

            if($item['VIGENTE'] == 1)
                $vigente = "Vigente";
            else
                $vigente = "No Vigente";

             if(  $item['ESTADO'] == 1)
                $estado = "Activo";
            else
                $estado = "Inactivo";


            if($i%2)
                $clase = "trImpar";
            else
                $clase = "trPar";
            $i++;
$nombre = ($item['NOMBRE']);

               echo '<tr class="'.$clase.'">';
               echo " <td ><p>{$item['CODIGO']}</p></td>
                    <td><p>{$item['NIVEL_ESTRATEGICO']}</p></td>
                    <td><p>$nombre</p></td>
                    <td><p>{$item['DEFINICION']}</p></td>
                    <!--<td><p>{$item['TIPO']}</p></td>!-->
                    <td><p>{$for[0]['NOMBRE']}</p></td>
                    <td><p>{$des[0]['NOMBRE']}</p></td>
                    <td><p>{$frec[0]['NOMBRE']}</p></td>
                    <td><p>$vigente</p></td>
                    <td><p>$estado</p></td>
                    <td><p>{$us[0]['APELLIDO']} {$us[0]['NOMBRE']}</p></td>";
            echo      '<td><a title="Editar" href="#" onClick="sendPage(\'null\',\'modules/indicadores/addIndicador.php?edit='.$item['IDINDICADOR'].'\',\'mod_content\');"><img src="'.$_SESSION['url'].'/images/edit.gif"></a></td>';
            echo      '<td><a href="#" onClick="if(confirm(\'Desea eliminar este indicador?\')) {sendPage(\'null\',\'modules/indicadores/listIndicadores.php?delete='.$item['IDINDICADOR'].'\',\'mod_content\');}"><img src="'.$_SESSION['url'].'/images/icontrash.gif"></a></td>';
            echo      '<td><a title="Extender plazo de ingreso de valores meta."  href="#" onClick="if(confirm(\'Desea extender por 3 dias adicionales el ingreso de valores ejecutados?\')) {sendPage(\'null\',\'modules/indicadores/listIndicadores.php?extend='.$item['IDINDICADOR'].'\',\'mod_content\');}"><img src="'.$_SESSION['url'].'/images/alerta.png"></a></td>';
            echo      "</tr>";
        }
        ?>
    
  
    </table>
</div>    