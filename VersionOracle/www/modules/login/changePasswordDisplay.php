<?php
/**
 * PANTALLA PARA CAMBIO DE PASSWORDS
 * @autor Jorge Tenorio
 * @since 25/03/2010
 */

?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
<title>NESTLE :: Cambio de password.</title>
<script src="js/jquery.js"></script>
<script src="js/functions.js"></script>
<link href="css/reset.css" media="all" type="text/css" rel="stylesheet"></link>
<link href="css/style.css" media="all" type="text/css" rel="stylesheet"></link>

</head>

<body>
<div id="application">
    <div id="login_form">

        <div id="logo">

        </div>
        <div id="form_data">
            <form name="contact" method="post" action="" id="login">
                <ul>
                    <li>
                        <p>Cambio de contrase&ntilde;a</p>
                        <input type="hidden" id="username" name="username" value="<?php echo $_GET['user'];?>"/>

                    </li>
                    <li>
                       <p>Contrase&ntilde;a:
                        <input type="password" name="pwd" id="pwd" size="10" value="" class="text-input" /></p>
                    </li>
                    <li>
                        <p>Confirmaci&oacute;n:
                          <input type="password" name="pwd2" id="pwd2" size="10" value="" class="text-input" /></p>
                    </li>
                   
                    <li>
                        <!--<input type="button" name="submit_btn" class="button" id="submit_btn" value="" onclick="
                                    sendPageAjax('login','modules/login/launch.php','application');"/>1-->
                        <input type="button" name="submit_btn" class="button" id="submit_btn" value="Continuar" onclick="
                          if($('#pwd').val() != '')  {
                            if($('#pwd').val() == $('#pwd2').val()){
                                    sendPageAjax('login','modules/login/launch.php?change=1','application');
                            }else{
                                alert('Las contrase&ntilde;as no coinciden.');
                            }
                          }else{
                              alert('No puede dejar en blanco los campos.');
                          }
                            "/>
                        
                    </li>

                </ul>

      </form>
     </div>


    </div>
    <div id="marca_nestle"></div>
</div>
</body>
</html>
