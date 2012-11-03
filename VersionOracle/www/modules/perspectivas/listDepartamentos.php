<?php
/* 
 * listado de departamentos creados
 * jtenorio
 * MUSHOQ
 * 15-02-2011
 */

@session_start();
include($_SESSION['path'].'/modules/perspectivas/launch.php');

$perspectiva = new Perspectiva();

if(isset($_GET['delete'])){
    $perspectiva->deleteDep($_GET['delete']);
}

$perspectiva->getDepTree();
