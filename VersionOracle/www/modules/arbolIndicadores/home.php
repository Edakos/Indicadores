<?php
/*
 * vista del modulo del arbol de indicadores
* jtenorio
 * MUSHOQ
 * 15-02-2011
 */
@session_start();
require_once($_SESSION['path'].'/modules/arbolIndicadores/launch.php');

$arbol = new ArbolIndicadores();

if(isset($_GET['delete'])){
    $arbol->deleteIndicadorTree($_GET['delete']);
}else if(isset($_GET['ponderar'])){
    //ingresar o actualizar las ponderaciones
    $arbol->setPonderaciones($_POST);
}


//////////////////////////////////////////////////////////
?>


<div id="mod_content_user">
    <?php $arbol->getObjetivosTree();?>
</div>