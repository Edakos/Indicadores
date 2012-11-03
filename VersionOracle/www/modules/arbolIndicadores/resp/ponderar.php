<?php
/*
 * vista del modulo del arbol de indicadores, ponderaciones
* jtenorio
 * MUSHOQ
 * 04-03-2011
 */
@session_start();
require_once($_SESSION['path'].'/modules/arbolIndicadores/launch.php');

$arbol = new ArbolIndicadores();


$idPadre = $_GET['idPadre'];
$tipoPadre = $_GET['tipoPadre'];

////////////////////////////////////////////////////////////////////////////////


?>
<h2>Ponderaciones</h2>
<form name="ponderar" id="ponderar" method="POST" enctype="multipart/form-data">
    <?php $arbol->getPonderaciones($idPadre, $tipoPadre);?>
</form>