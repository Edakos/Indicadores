<?php
/**
 * ARCHIVO Q LLAMA A LA CARGA DE ARCHIVOS
 * @autor Jorge Tenorio
 * @since 11/03/2010
 */

//$_FILES['zipFile']
@session_start();
require_once($_SESSION['path'].'/modules/controlCambios/launch.php');

$cambios = new ControlCambios();

$cambios->uploadFile($_FILES['zipFile']);