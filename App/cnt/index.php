<?php
/**
 * ARCHIVO DE ARRANQUE DEL SISTEMA CNT
 * CARGA DE BASE DE DATOS
 * ARCHIVOS DE CONFIGURACION
 * PHP version 5
 *
 * @autor Jorge Tenorio
 * @since 24/03/2010
 */



//iniciar sesion
@session_start();
//path de la aplicaci�n
$data = file('conf/path.conf');
//modifique esta variable seg�n sea el caso en la instalaci�n
$_SESSION["url"] = trim($data[3]);


//cargar el archivo de path

$_SESSION["path"] = trim($data[1]);

//////////////////
//si ya ha iniciado session, directo al panel.
if(isset($_SESSION["USER_id"])){
    switch ($_SESSION['USER_tipo']){
                case 'A':
                   include_once("modules/controlPanel/panelDisplay.php");
                    break;
                case 'F':
                    include_once("modules/dataEntry/home.php");
                    break;
                
     }
    
   
    exit;
}



/*require_once('modules/ado/launch.php');
$ado = new Ado();
$test = $ado->query('select	*
 from	 USUARIO');

print_r($test);
die();*/

//AQUI INICIAR EL M�DULO DE LOGIN
//punto de entrada al sistema
include_once('modules/login/logInDisplay.php');

////////////FIN/////////////////////////////////







