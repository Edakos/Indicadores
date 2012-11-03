<?php
/*
 * AGREGA EDITA USUARIOS
 * Jorge Tenorio
 * 11-02-2011
 * MUSHOQ
 */
@session_start();
include($_SESSION['path'].'/modules/usuarios/launch.php');
$user = new Usuarios();

if(isset($_GET['add'])){
   //agergar el nuevo usuario
    $user->addUser($_POST);
}else if(isset($_GET['editUser'])){
    //editar usuario
    $user->editUser($_GET['editUser'],$_POST);
    $_GET['edit'] = $_GET['editUser'];
}else if(isset($_GET['pwdUser'])){
    //cambiar el password
    $user->resetPwd($_GET['pwdUser']);
    $_GET['edit'] = $_GET['pwdUser'];
}

if(isset($_GET['edit'])){
    $modo = "1";
}else{
    $modo = "0";
}
//obtener los datos del usuario
if($modo){
   $data = $user->getUser($_GET['edit']);   
}


?>
<div id="mod_content_user">
<h2>A&ntilde;adir / Editar usuarios del sistema</h2>
<p></p>
<form name="formUser" id="formUser" method="POST" enctype="multipart/form-data">

    <table>
        <tr>
            <td><p>Nombre:</p></td>
            <td><input type="text" name="nombre" id="nombre" value="<?php if($modo) echo $data[0]['NOMBRE'];?>" size="20" maxlength="55"/></td>
        </tr>

        <tr>
            <td><p>Apellido:</p></td>
            <td><input type="text" name="apellido" value="<?php if($modo) echo $data[0]['APELLIDO'];?>" size="20" maxlength="55"/></td>
        </tr>

        <tr>
            <td><p>E-mail:</p></td>
            <td><input type="text" name="email" value="<?php if($modo) echo $data[0]['EMAIL'];?>" size="40" maxlength="125"/></td>
        </tr>

        <tr>
            <td><p>Estado:</p></td>
            <td><p><input type="radio" name="estado" value="1" <?php if($modo) { if ($data[0]['ESTADO'] == 1) echo 'checked="checked"'; }else{echo 'checked="checked"';}?> /> Activo - <input type="radio" name="estado" value="0" <?php if($modo) { if ($data[0]['ESTADO'] == 0) echo 'checked="checked"'; }?>/>Inactivo</p></td>
        </tr>
        <?php if($modo){?>
        <tr>
            <td><p>Passwrod:</p></td>
            <td><input type="button" value="Cambiar Password" name="changePasswd" onClick="
                    if(confirm('Desea reiniciar el password de este usuario?')){
                        loadPageAjax(null,'modules/usuarios/formUser.php?pwdUser=<?php echo $_GET['edit'];?>','main_content');
                    }
                   "/></td>
        </tr>
        <?php }?>
        <tr>
            <td><p>Tipo:</p></td>
            <td><select name="tipo" size="2">
                    <option value="A" <?php if($modo) {
                        if ($data[0]['TIPO'] == A)
                            echo "selected";
                    }else{
                            echo "selected";
                            
                            } ?>>Administrador</option>
                    <option value="F" <?php if($modo) { if ($data[0]['TIPO'] == F) echo "selected"; } ?>>Facilitador</option>
                </select></td>
        </tr>

        <tr>
            <td colspan="2"><input type="submit" value="Aplicar" name="aplicar" onClick="" /></td>
        </tr>


    </table>
</form>
</div>
<script type="text/javascript">
    divDestino = 'main_content';
    formDestino = 'formUser';
   <?php
     if(!$modo){
        echo "urlDestino = 'modules/usuarios/formUser.php?add=1';";
     }else{
       echo "urlDestino = 'modules/usuarios/formUser.php?editUser={$_GET['edit']}';";
     }
     ?>

     var frmvalidator  = new Validator("formUser");

     frmvalidator.EnableMsgsTogether();

     frmvalidator.addValidation('nombre','req','Por favor ingrese el nombre.');
     frmvalidator.addValidation('nombre','alphanumeric_space','No se permiten caracteres especiales.');
     frmvalidator.addValidation('apellido','req','Por favor ingrese el apellido.');
     frmvalidator.addValidation('apellido','alphanumeric_space','No se permiten caracteres especiales.');
     frmvalidator.addValidation("email","req",'Por favor ingrese el e-mail');
     frmvalidator.addValidation("email","email",'Por favor ingrese un e-mail válido.');

 </script>