<?php
/**
 * CALSE QUE MANEJA LOS ERRORES DE LA APLICACION
 * @autor Jorge Tenorio
 * @since 07/04/2010
 */

////////////////////////////////////////////
//MANEJO DE ERRORES
function appErrorHandler($errno, $errstr, $errfile, $errline)
{
   $html='';
   $send = true;
   $to= array('jtenorio@mushoq.com','jllandazuri@mushoq.com');
    switch ($errno) {
    case E_USER_ERROR:
        $html.= "<b>ERROR APP NESTLE</b> [$errno] $errstr<br />";
        $html.= "  Fatal error on line $errline in file $errfile";
        $html.= ", PHP " . PHP_VERSION . " (" . PHP_OS . ")<br />\n";
        $html.= "Aborting...<br />\n";
        echo "<b>LO SENTIMOS, EXISTE UN PORBLEMA CON LA APLICACION.</b>";
        exit(1);
        break;

    case E_USER_WARNING:
         $html.="<b>NESTLE WARNING</b> [$errno] $errstr<br />\n";
        break;

    case E_USER_NOTICE:
         $html.= "<b>NESTLE NOTICE</b> [$errno] $errstr<br />\n";
        break;

    default:
         //echo "NESTLE Unknown error type: [$errno] $errstr<br />\n";
         $send = false;
        break;
    }

    if($send){
        //enviar mail
        require_once($_SESSION['path'].'/modules/mailer/launch.php');
        $mail = new Mailer();
        $mail->sendMail($to, 'ERROR => NESTLE', $html);
    }

    //log
     require_once($_SESSION["path"].'/modules/logs/launch.php');
     $logs =& new Logs();
     $log = $_SESSION['USER_id']."\t".$_SESSION['USER_nombre']."\t{$html}\n";
     $logs->openFile("WRITE",$log);

    //
    //echo $html;
}

set_error_handler("appErrorHandler");
/////////////////////////////////////////