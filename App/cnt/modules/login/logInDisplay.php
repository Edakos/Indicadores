<?php
/**
 * PANTALLA PARA INICIAR SESION
 * @autor Jorge Tenorio
 * @since 25/03/2010
 */

?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=UTF8" />
<title>CNT :: Administrador de Indicadores</title>
<script src="js/jquery.js"></script>
<script src="js/functions.js"></script>
<link href="css/reset.css" media="all" type="text/css" rel="stylesheet"></link>
<link href="css/style.css" media="all" type="text/css" rel="stylesheet"></link>

<!--[if IE]>
    <link href="css/ie.css" media="all" type="text/css" rel="stylesheet"></link>
<![endif]-->

</head>

<body onkeypress="
    e= event;
    tecla=(document.all) ? e.keyCode : e.which;
  if(tecla == 13){
   
    return false;
    
  }">
<div id="application">
    <div id="login_form">

        <div id="logo">
            <h1>Administraci&oacute;n de Indicadores</h1>
        </div>
        <div id="form_data">
            <form name="contact" method="post" action="" id="login">
                <ul>
                    <li>
                        <p align="right">* Usuario:
                        <input type="text" name="username" id="username" size="15" value="" class="text-input" /></p>
                    </li>
                    <li>
                         <p align="right">* Clave:
                          <input type="password" name="pwd" id="pwd" size="15" value="" class="text-input" /></p>
                    </li>
                  
                    <li>
                       <input type="button" name="submit_btn" class="button" id="submit_btn" value="Ingresar" onclick="
                                    sendPageAjax('login','modules/login/launch.php','application');" />
                    </li>
                      <li>
                      <?php
                      if(isset($_GET['error'])){

                        if($_GET['error'] == 1){
                      ?>

                                <p><label for="error" id="error" class="error">Acceso no permitido.</label></p>

                      <?php
                        }

                        if($_GET['error'] == 2){
                      ?>

                                <p><label for="error" id="error" class="error">Lo sentimos, el sistema se encuentra al momento con exceso de cargas, por favor intente m&aacute;s tarde.</label></p>

                      <?php
                        }
                      }
                      ?>
                     </li>
                    <li id="linkPassword">
                        <a onclick="sendPage('null','modules/login/sendDisplay.php','application');"></a>
                    </li>
                </ul>
       
      </form>
     </div>
        

    </div>
   
</div>
</body>
</html>
