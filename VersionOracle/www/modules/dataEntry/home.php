<?php
/**
 * ARCHIVO QUE MUESTRA EL MENU DE ACCESO A LOS MODULOS
 * CONTIENE LA PLANTILLA GENERAL DE LA APLICACION
 * PHP version 5
 *
 * @autor Jorge Tenorio
 * @since 25/03/2010
 * @return
 * @uses
 */


if(!isset($_SESSION['USER_id']))
    session_start();

//print_r($_SESSION);
include_once($_SESSION['path'].'/modules/dataEntry/launch.php');


$dataEntry = new DataEntry();



if(isset($_GET['agrupar'])){
     $lista = $dataEntry->agruparPor($_POST['criterioAgrupar']);
}elseif(isset($_GET['buscar'])){
    $lista = $dataEntry->buscarPor($_POST['criterioAgrupar'],$_POST['valor']);
}else{
    $lista = $dataEntry->getIndicadoresNeedData();
}


$ado = new Ado();



?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
<title>DEMO :: BIENVENIDOS :: Administrador de Indicadores :: Ingreso de Valores Meta</title>
<script src="js/jquery.js" type="text/javascript"></script>
<script src="js/functions.js" type="text/javascript"></script>
<script src="js/ajaxupload.js" type="text/javascript"></script>
<script src="js/gen_validatorv4.js" type="text/javascript"></script>
<link href="css/reset.css" media="all" type="text/css" rel="stylesheet"></link>
<link href="css/style.css" media="all" type="text/css" rel="stylesheet"></link>

</head>

<body style="text-align: center; margin: 0 auto;">

        <div id="top">

            <a href="index.php" id="topLogo"></a>

            <?php echo $dataEntry->generateTopInfo();?>
            <?php echo $dataEntry->generateMenu();?>

        </div>

        <div id="main_content">
            <div id="mod_content_user">
                <div id="filtroLista">
                <form name="groupBy" id="groupBy" method="POST" enctype="multipart/form-data">
                <table align="center">
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
                            <input type="button" value="Agrupar" name="doAgrupar" onClick="sendPage('groupBy','modules/dataEntry/home.php?agrupar=1','mod_content_user');"/>
                        </td>
                    </tr>
                </table>
                </form>
                <p></p>
                <form name="search" id="search" method="POST" enctype="multipart/form-data">
                    <table align="center">
                    <tr>
                        <td>
                            Buscar por:
                        </td>
                        <td>
                            <select name="criterioAgrupar">
                                <option value="CODIGO">C&oacute;digo</option>
                                <option value="NOMBRE">Nombre</option>
                                <option value="DEFINICIÓN">Definici&oacute;n</option>
                            </select>
                        </td>
                            <td>
                                <input type="text" name="valor" value="" size="29" />
                            <td>
                        <td>
                            <input type="button" value="Buscar" name="doAgrupar" onClick="sendPage('search','modules/dataEntry/home.php?buscar=1','mod_content_user');"/>
                        </td>
                    </tr>
                </table>
                </form>
            </div>
            <p></p>
            <p></p>

            <h2>Listado de Indicadores Asigandos</h2>

            <table >
                <tr class="tableTitle">
                    <td><p><b>C&Oacute;DIGO</b></p></td>
                    <td><p><b>NIVEL</b></p></td>
                    <td><p><b>NOMBRE</b></p></td>
                    <td><p><b>DEFINICI&Oacute;N</b></p></td>
                    <td><p><b>TIPO</b></p></td>
                    <td><p><b>FRECUENCIA</b></p></td>
                    <td><p><b>PR&Oacute;XIMO INGRESO</b></p></td>
                    <td></td>                   
                </tr>
                <?php
                $mes = date('m');
                
                //validacion solo para enero de cada año
                if($mes == 1){
                    $mes=12;
                }
                
                $i=0;

                foreach ($lista as $item){
                   
                    //obtener el periodo de frecuencia
                    $sql = "SELECT * FROM FRECUENCIA WHERE IDFRECUENCIA = {$item['FRECUENCIA']}";
                    $resultFrecuencia = $ado->query($sql);
                    $periodo = $resultFrecuencia[0]['MESES'];

                    $mesesVigentes = $dataEntry->periodosModificables($periodo);

                    $nextMonth = $dataEntry->proximoIngreso($mesesVigentes);
                    
                    //print_r($mesesVigentes);
                    
                    if($i%2)
                        $clase = "trImpar";
                    else
                        $clase = "trPar";
                    $i++;

                    echo '<tr class="'.$clase.'">';
                    echo "    <td><p>{$item['CODIGO']}</p></td>
                            <td><p>{$item['NIVEL_ESTRATEGICO']}</p></td>
                            <td><p>{$item['NOMBRE']}</p></td>
                            <td><p>{$item['DEFINICION']}</p></td>
                            <td><p>{$item['TIPO']}</p></td>
                            <td><p>{$resultFrecuencia[0]['NOMBRE']}</p></td>
                            <td><p>$nextMonth</p></td>";

                    if(in_array($mes, $mesesVigentes) && $dataEntry->monthOnTime($mes)){
                        if(!$dataEntry->yaIngresadoValores($item['IDINDICADOR'], $periodo))
                            $link = '<td><a href="#" onClick="sendPage(\'null\',\'modules/dataEntry/addEjecutados.php?id='.$item['IDINDICADOR'].'\',\'mod_content_user\');"><img src="'.$_SESSION['url'].'/images/exito.png"></a></td>';
                        else
                            $link = '<td><a href="#" onClick="sendPage(\'null\',\'modules/dataEntry/editEjecutados.php?id='.$item['IDINDICADOR'].'\',\'mod_content_user\');"><img src="'.$_SESSION['url'].'/images/detalle.gif"></a></td>';
                    }else{
                        if($dataEntry->estaExtendido($item['IDINDICADOR'])){
                            $link = '<td><a href="#" onClick="sendPage(\'null\',\'modules/dataEntry/addEjecutados.php?id='.$item['IDINDICADOR'].'\',\'mod_content_user\');"><img src="'.$_SESSION['url'].'/images/alerta.png"></a></td>';
                        }else{
                            $link = '<td><a href="#" onClick="alert(\'Este indicador no recibe valores en este momento\');"><img src="'.$_SESSION['url'].'/images/error.png"></a></td>';
                        }
                    }

                    echo $link;
                    echo      "</tr>";
                }
                ?>
            </table>

            </div>
        </div>
       

        <div id="bottom">
           
        </div>





</body>
</html>