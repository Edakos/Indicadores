<?php
/*
 * arbol de objetivos
 * jtenorio
 * MUSHOQ
 * 15-02-2011
 */

@session_start();
include($_SESSION['path'].'/modules/objetivos/launch.php');

$objetivo = new Objetivo();

if(isset($_GET['delete'])){
    $objetivo->deleteObjetivo($_GET['delete']);
}

$objetivo->getObjetivosTree();
