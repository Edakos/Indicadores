<?php
/**
 * CLASE SE ENCARGA DE VALIDACION E INSCRIPCION DE USUARIOS
 * @autor Jorge Tenorio
 * @since 24/03/2010
 */
@session_start();

class Login{

    private $user;
    private $pwd;

    public function __construct($user,$pwd){
       $this->user=$user;
       $this->pwd = sha1($pwd);
    }

    /**
     *validar usuario contra la base de datos
     *
     *@param
     *@return boolean
     */
    public function validateUser(){
     //instancear la clase de ado
        require_once($_SESSION["path"].'/modules/ado/launch.php');
        $ado = new Ado();
        $sql = "select * from USUARIO where PWD='{$this->pwd}' and EMAIL='{$this->user}' and ESTADO = '1' and ELIMINADO=0";
       
        $result = $ado->query($sql);

        if(count($result)==1){
            //crear las variables de session
            
            //validar q sea la primera ves q utiliza su pwd
            if($result[0]['PWD_INIT']==1){
                echo $_SESSION["url"]."/modules/login/changePasswordDisplay.php?user={$this->user}";
                exit;
            }

                    
            $_SESSION['USER_id']= $result[0]['IDUSUARIO'];
            $_SESSION['USER_nombre']= $result[0]['APELLIDO'].' '.$result[0]['NOMBRE'];
            $_SESSION['USER_tipo']= $result[0]['TIPO'];
            $_SESSION['USER_mail']= $result[0]['EMAIL'];
            $_SESSION['USER_start']= date('l jS \of F Y h:i:s A');
            
            
            return true;

        }else{
            return false;
        }

    }

    
  

    public function logOut(){
        session_destroy();
    }

    /**
     *Funcion que cambia el pwd del usuario
     *
     *@param tipo $variable descripcion
     *@return tipo
     */
    public function changePwd(){
        require_once($_SESSION["path"].'/modules/ado/launch.php');
        $ado = new Ado();
        $sql = "update USUARIO set PWD='{$this->pwd}', PWD_INIT='0' where EMAIL='{$this->user}'";
        $result = $ado->query($sql);

        if(isset($result['Error'])){
            return false;
        }else{
            return true;
        }

    }

    /**
     *Funcion para enviar mail de confirmacion de cambio
     *
     *@param tipo $variable descripcion
     *@return tipo
     */

     public function sendConfMail(){
         
         require_once($_SESSION["path"].'/modules/ado/launch.php');
         $ado = new Ado();
         $sql="select IDUSUARIO from USUARIO where EMAIL = '{$this->user}'";
         $result = $ado->query($sql);
        

         if(count($result)==1){

             $newPass = $this->restartPassword();

             $html='<p><b>CAMBIO DE CONTRASE&ntilde;A</b></p>
                    <p>Nos ha solicitado recordar su contrase&ntilde;a.</p>
                    <p>Por motivos de seguridad hemos cambiado la misma.</p>';

             $html.='<p><b>Contrase&ntilde;a temporal: '.$newPass.'</b></p>';

             //validar si el user existe




             require_once($_SESSION['path'].'/modules/mailer/launch.php');
             $mail = new Mailer();
             $mail->sendMail($this->user,'Solicitud de cambio de contraseña.',$html);

             echo 'modules/login/sendDisplay.php?enviado=1';
         }else{
             echo 'modules/login/sendDisplay.php?nouser=1';
         }
     }

     private function restartPassword(){
         $logitud = 8;
         $psswd = substr(md5(microtime()), 1, $logitud);
         require_once($_SESSION["path"].'/modules/ado/launch.php');
         $ado = new Ado();
         $psswd2 = sha1($psswd);
         $sql = "update USUARIO set PWD='{$psswd2}', PWD_INIT='1' where EMAIL='{$this->user}'";
         $result = $ado->query($sql);
         return $psswd;
     }



     public function dirSize($directory) {
         $size = 0;
         foreach(new RecursiveIteratorIterator(new RecursiveDirectoryIterator($directory)) as $file){
             $size+=$file->getSize();
         }
         return $size;
    }


}

///////////////////////////////////////////////

if(!isset($_SESSION['USER_id']))
    @session_start();

//inicializar los logs
require_once($_SESSION["path"].'/modules/logs/launch.php');

$logs =& new Logs();

$user = new Login('', '');

if(isset($_GET['logout'])){
    $log = $_SESSION['USER_id']."\t".$_SESSION['USER_nombre']."\t".$_SESSION['USER_start']."\tLOG OUT\n";
    $logs->openFile("WRITE",$log);
    $user->logOut();
    header("location: ../../");
    exit;
}else{

    $user = new Login($_POST['username'], $_POST['pwd']);
    $log = true;

    if(isset($_GET['mail'])){

        $user->sendConfMail();
        exit;
    }

    if(isset($_GET['change'])){
        $log = $user->changePwd();
        //exit;
    }

    if($log){
        if($user->validateUser()){
            $log = $_SESSION['USER_id']."\t".$_SESSION['USER_nombre']."\t".$_SESSION['USER_start']."\tLogin satisfactorio\n";
            $logs->openFile("WRITE",$log);

            switch ($_SESSION['USER_tipo']){
                case 'A':
                    echo $_SESSION["url"]."/modules/controlPanel/panelDisplay.php";
                    break;
                case 'F':
                    echo $_SESSION["url"]."/modules/dataEntry/home.php";
                    break;
                 
                
            }
            
        }else{

            $log = "No ID\t".$_POST['username']."\t".date('l jS \of F Y h:i:s A')."\tLogin fallido\n";
            $logs->openFile("WRITE",$log);
            echo $_SESSION["url"]."/modules/login/logInDisplay.php?error=1";
        }
    }else{
        $log = "No ID\t".$_POST['username']."\t".date('l jS \of F Y h:i:s A')."\tCambio PWD fallido\n";
        $logs->openFile("WRITE",$log);
        echo $_SESSION["url"]."/modules/login/logInDisplay.php";
        
    }
}


