<?php
/* 
 * listado de departamentos creados
 * jtenorio
 * MUSHOQ
 * 15-02-2011
 */

@session_start();
include($_SESSION['path'].'/modules/despliegue/launch.php');
$despliegue = new Despliegue();


if(isset($_GET['delete'])){
    $despliegue->deleteDivision($_GET['delete']);
}
$despliegue->getDespliegueTree();
