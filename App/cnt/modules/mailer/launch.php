<?php
/**
 * CLASE SE ENCARGA DEL ENV�?O DE CORREOS
 * @autor Jorge Tenorio
 * @since 24/03/2010
 */

class Mailer{
    
    private $template;
    private $mail;
    
    public function __construct($template = ''){
        $this->template = $template;
        
        include_once($_SESSION['path'].'/modules/mailer/class.phpmailer.php');
        include_once($_SESSION['path'].'/modules/mailer/class.pop3.php');
        $this->mail             = new PHPMailer();
    }

    public function sendMail($to, $subject, $message, $intentos = 3){

        $intento =0;
        $subject = html_entity_decode($subject);
        $subject = utf8_decode($subject);
       
        date_default_timezone_set('America/New_York');

        //modificar estas configuraciones cuando sea necesario
        $mail = $this->mail;
        $mail             = new PHPMailer();
        $mail->IsSMTP();
        $mail->SMTPAuth   = true;                  // enable SMTP authentication
        $mail->SMTPSecure = "ssl";                 // sets the prefix to the server
        $mail->Host       = "smtp.gmail.com";      // sets GMAIL as the SMTP server
        $mail->Port       = 465;                   // set the SMTP port for the GMAIL server

        $mail->Username   = "mushoqdev3@gmail.com";  // GMAIL username
        $mail->Password   = "mushoq25";            // GMAIL password


        //$mail->AddReplyTo("angelsbookmail@gmail.com","ANGELS BOOK");

        $mail->From       = "jtenorio@mushoq.com";
        $mail->FromName   = "CNT Desarrollo";

        $mail->Subject    = $subject;

        $mail->IsHTML(true);

        $mail->Body       = $message;                      //HTML Body
        $mail->AltBody    = "To view the message, please use an HTML compatible email viewer!"; // optional, comment out and test
        $mail->WordWrap   = 50; // set word wrap

        $mail->MsgHTML($message);


        if(!is_array($to)){
            if(strlen($to)<=0) return;
            $mail->AddAddress($to);
        }else{
            if(count($to)<=0) return;
            foreach($to as $dest){
                $mail->AddAddress($dest);
            }
        }


        //$mail->AddAttachment("images/phpmailer.gif"); // attachment

        $mail->IsHTML(true); // send as HTML

        //hacer los intentos
        while((!$mail->Send()) && ($intento<$intentos))
        {
            $intento++;
        }
        
    }
}

?>
