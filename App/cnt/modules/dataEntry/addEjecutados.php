<?php
/*
 * vista del modulo de data entry para ingresar datos
* jtenorio
 * MUSHOQ
 * 10-03-2011
 */
@session_start();
include_once($_SESSION['path'].'/modules/dataEntry/launch.php');

$dataEntry = new DataEntry();

$idIndicador = $_GET['id'];
///////////////////////////////////////////////////////////////////
if(isset($_GET['addEjecutados'])){
    $dataEntry->addValoresEjecutados($_POST, $idIndicador);
}




?>

<h2>Ingreso de valores ejecutados</h2>
<p></p>
<p>Recuerde que &uacute;nicamente puede ingresar datos pare el per&iacute;odo vigente.</p>
<p></p>
<form name="ejecutados" id="ejecutados" method="POST" enctype="multipart/form-data">

    <?php $dataEntry->generarEjecutadosEntry($idIndicador);?>

    <p style="text-align: center;"><input type="button" value="Aplicar" name="aplicar" onClick="
                            if(validaInputs('text')){
                                sendPage('ejecutados','modules/dataEntry/addEjecutados.php?id=<?php echo $idIndicador;?>&addEjecutados=1','mod_content_user');
                                }else{
                                    alert('Debe ingresar los valores meta para cada despliegue geográfico.');
                                }
                            "/></p>
    <p style="text-align: center;"><a href="index.php"><= Regresar al listado de indicadores.</a></p>
</form>