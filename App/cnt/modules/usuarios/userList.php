<?php
/* 
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */
@session_start();
require_once($_SESSION['path'].'/modules/usuarios/launch.php');


$user = new Usuarios();

if(isset($_GET['delete'])){
    //eliminar el usuario

    $user->delteUser($_GET['delete']);
}

?>
<div id="mod_content_user">
    <p><a href="#" onclick="sendPage('null','modules/usuarios/formUser.php','main_content');"><img src="images/agregar.gif"> Agregar Nuevo Usuario</a></p>
    <h2>Listado de usuarios del sistema</h2>
    <?php echo $user->getUsersList(); ?>
</div>
