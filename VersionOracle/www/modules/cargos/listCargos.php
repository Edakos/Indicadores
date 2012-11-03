<?php
/* 
 * listado de departamentos creados
 * jtenorio
 * MUSHOQ
 * 15-02-2011
 */

@session_start();
include($_SESSION['path'].'/modules/cargos/launch.php');
$cargo = new Cargo();


if(isset($_GET['delete'])){
    $cargo->deleteCargo($_GET['delete']);
}

$cargo->getCargoTree();
