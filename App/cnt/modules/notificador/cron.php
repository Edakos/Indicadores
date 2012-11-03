<?php
/* 
 * Archivo php para ser llamado por tarea programa q envia los correos electronicos de notificación
 * jtenorio
 * 16-03-2011
 */

//iniciar sesion
@session_start();
//path de la aplicaci�n
$data = file('../../conf/path.conf');
//modifique esta variable seg�n sea el caso en la instalaci�n
$_SESSION["url"] = trim($data[3]);


//cargar el archivo de path

$_SESSION["path"] = trim($data[1]);
//session del cron

$_SESSION["USER_id"]= 'cron';
$_SESSION["USER_nombre"] = 'Cron Job CNT Sistema de indicadores.';

//obtener todos los indicadores activos

include('../ado/launch.php');
include('../mailer/launch.php');

$ado = new Ado();
$mail = new Mailer();

$enviados = array();

//CAMBIAR ESTADO DE INDICADORES
$sql = "update indicadores set VIGENTE = 0 where VIGENTE_HASTA < sysdate";
$ado->query($sql);


include($_SESSION['path'].'/modules/dataEntry/launch.php');


$dataEntry = new DataEntry();

//listado de indicadors vigentes y activos
$sql = "SELECT * FROM INDICADORES WHERE VIGENTE = 1 AND ESTADO =1 AND MANUAL = 1 AND IDINDICADOR IN(SELECT IDINDICADOR FROM ARBOL_INDICADOR_VALORES)";
$lista = $ado->query($sql);

//parametros de indicadores
$sql = "SELECT * FROM PARAMETROS_INDICADORES";
$parametros = $ado->query($sql);

//$parametros[0]['DIA_INICIO'] = $parametros[0]['DIA_INICIO']==1?0:$parametros[0]['DIA_INICIO'];

$mes = date('m');
$i=0;

foreach ($lista as $item){


    //obtener el facilitador
    $sql = "SELECT * FROM USUARIO WHERE IDUSUARIO = {$item['FACILITADOR']}";
    $facilitador = $ado->query($sql);

    //obetner el responsable
    $sql = "SELECT * FROM CARGO WHERE IDCARGO = {$item['RESPONSABLE']}";
    $responsable = $ado->query($sql);

    //obtener el periodo de frecuencia
    $sql = "SELECT * FROM FRECUENCIA WHERE IDFRECUENCIA = {$item['FRECUENCIA']}";
    $resultFrecuencia = $ado->query($sql);
    $periodo = $resultFrecuencia[0]['MESES'];

    $mesesVigentes = $dataEntry->periodosModificables($periodo);

    $nextMonth = $dataEntry->proximoIngreso($mesesVigentes);


    $i++;
    
//verificar que no este extendido el plazo
 if(!estaExtendido($item['IDINDICADOR'])){   

    if(in_array($mes, $mesesVigentes) && $dataEntry->monthOnTime($mes)){

        //el indicador esta vigente para recibir información

        if(!$dataEntry->yaIngresadoValores($item['IDINDICADOR'], $periodo)){
            //verificar q sea el dia del mes q inicia el ingreo de información
            if(date('d') == $parametros[0]['DIA_INICIO']){
                ///////////////////////////////////////////
                $to = $facilitador[0]['EMAIL'];
                ///////////////////////////////////////////
                $subject = "Hoy inicia el ingreso de valores meta para {$item['NOMBRE']}";
                ///////////////////////////////////////////
                $message = '<p><b>El indicador '.$item['NOMBRE'].' recibe valores ejecutados a partir de hoy.</b></p>';
                $message .= '<p>Estimado '.$facilitador[0]['NOMBRE'].' '.$facilitador[0]['APELLIDO'].', este mensaje es para recordale que a partir de hoy ('.date('d-m-Y').') puede iniciar el ingreso de valores metas durante '.$parametros[0]['DIAS_INGRESO'].' dias.';
                //$mail->sendMail($to, $subject, $message);
            }else if((date('d') > ($parametros[0]['DIA_INICIO'] + $parametros[0]['DIAS_INGRESO'])) && (date('d') <= ($parametros[0]['DIA_INICIO'] + $parametros[0]['DIAS_INGRESO'] + $parametros[0]['MAXIMO_INGRESO']))){
                //advertir q esta en en tiempo extra de ingreso, tambien va el mail al responsable
                $message = '<p><b>IMPORTANTE: No ha ingresado valores para el indicador '.$item['NOMBRE'].'</b></p>';
                $message .= '<p>Estimado '.$facilitador[0]['NOMBRE'].' '.$facilitador[0]['APELLIDO'].' el tiempo para el ingreso de valores a meta est&aacute; por finalizar por favor ingrese los mismos.</p>';
                $message .= '<p>Se ha enviado una copia de este correo a : '.$responsable[0]['RESPONSABLE'].' ('.$responsable[0]['NOMBRE'].')</p>';
                //$mail->sendMail($to, $subject, $message);
            }
        }else{
           //no han ingresado aún datos en el indicador
           //verificar si está dentro del plazo máximo de ingreso.
            
        }
    }else{
        if($dataEntry->estaExtendido($item['IDINDICADOR'])){
                ///////////////////////////////////////////
                $to = array($facilitador[0]['EMAIL'],$responsable[0]['EMAIL']);
                ///////////////////////////////////////////
                $subject = "IMPORTANTE:  Se ha extendido el plazo para {$item['NOMBRE']}";
                ///////////////////////////////////////////
                $message = '<p><b>IMPORTANTE: Se ha extendido el plazo para el ingreso de valores para el indicador '.$item['NOMBRE'].'</b></p>';
                $message .= '<p>Estimado '.$facilitador[0]['NOMBRE'].' '.$facilitador[0]['APELLIDO'].' tiene plazo extendeido para ingresar o modificar los valores meta.</p>';
                $message .= '<p>Se ha enviado una copia de este correo a : '.$responsable[0]['RESPONSABLE'].' ('.$responsable[0]['NOMBRE'].')</p>';
                //$mail->sendMail($to, $subject, $message);
        }else{
            if(!$dataEntry->yaIngresadoValores($item['IDINDICADOR'], $periodo)){
                 ///////////////////////////////////////////
                $to = array($facilitador[0]['EMAIL'],$responsable[0]['EMAIL']);
                ///////////////////////////////////////////
                $subject = "IMPORTANTE: No ha ingresado valores para el indicador {$item['NOMBRE']}";
                ///////////////////////////////////////////
                $message = '<p><b>IMPORTANTE: No ha ingresado valores para el indicador '.$item['NOMBRE'].'</b></p>';
                $message .= '<p>Estimado '.$facilitador[0]['NOMBRE'].' '.$facilitador[0]['APELLIDO'].' el tiempo para el ingreso de valores a meta a finalizado y no ha ingresado valores, solicite una extensi&oacute;n de plazo lo m&aacute; pronto posible.</p>';
                $message .= '<p>Se ha enviado una copia de este correo a : '.$responsable[0]['RESPONSABLE'].' ('.$responsable[0]['NOMBRE'].')</p>';
                //$mail->sendMail($to, $subject, $message);
            }
        }
    }

echo $message;   
 }
}


//DESTRUIR LA SESION DEL CRON
session_destroy();

function estaExtendido($idIndicador){

        global $ado;
        $sql = "SELECT * FROM EXTENSIONES_META WHERE IDINDICADOR = $idIndicador AND FECHAINGRESO IS NULL";
        $result = $ado->query($sql);

        if(count($result)){

            $date1 = $result[0]['FECHA'];
            $date2 = date('Y-m-d');

            $diff = abs(strtotime($date2) - strtotime($date1));
            $years = floor($diff / (365*60*60*24));
            $months = floor(($diff - $years * 365*60*60*24) / (30*60*60*24));
            $days = floor(($diff - $years * 365*60*60*24 - $months*30*60*60*24)/ (60*60*24));

            if($days <= 3){
                return TRUE;
            }else{
                return FALSE;
            }

        }else{
            return FALSE;
        }
    }