<?php
/*
 * vista del modulo del arbol de indicadores, generar entrys de metas
* jtenorio
 * MUSHOQ
 * 15-02-2011
 */
@session_start();
require_once($_SESSION['path'].'/modules/arbolIndicadores/launch.php');

$arbol = new ArbolIndicadores();

$arbol->generarMetasEntry($_GET['idIndicador'],$_GET['anio']);
//////////////////////////////////////////////////////////

?>

