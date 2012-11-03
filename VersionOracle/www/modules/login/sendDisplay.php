<?php
/**
 * PANTALLA PARA RECUPERAR PWD
 * @autor Jorge Tenorio
 * @since 25/03/2010
 */

?>

    <div id="login_form">

        <div id="logo">

        </div>
        <div id="form_data">
            <form name="contact" method="post" action="" id="login">
                <ul>
                    <li>
                       <p><label for="name" id="usr_label">Usuario:</label></p>
                        <input type="text" name="username" id="username" size="20" value="" class="text-input" />
                        <input type="hidden" name="pwd" id="pwd" value=""/>

                    </li>
                   
                   
                    <li>
                        <input type="button" name="submit_btn" class="button" id="submit_btn" value="Enviar contrase&ntilde;a" onclick="sendPageAjax('login','modules/login/launch.php?mail=1','application');">
                    </li>
                    <?php
                    if(isset($_GET['enviado'])){
                        ?>
                    <li>
                        <p class="error">Se ha enviado una confirmaci&oacute;n a su correo electr&oacute;nico.</p>
                    </li>
                    <?php
                    }

                    if(isset($_GET['nouser'])){
                        ?>
                    <li>
                        <p class="error">El usuario no se encuentra registrado.</p>
                    </li>
                    <?php
                    }
                    ?>
                </ul>

      </form>
     </div>


    </div>
    <div id="marca_nestle"></div>

