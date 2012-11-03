<?php
/*
 * Creacion / edicion de objetivos
 * Jorge Tenorio
 * 16-02-2011
 * MUSHOQ
 */

@session_start();
include($_SESSION['path'].'/modules/objetivos/launch.php');

$objetivo = new Objetivo();
if(isset($_GET['add'])){
   //agergar el departamento
    $objetivo->addObjetivo($_POST);

}else if(isset($_GET['editObj'])){
    //actualizar los datos
    $objetivo->actualizaObjetivo($_GET['editObj'], $_POST);
    $_GET['edit'] = $_GET['editObj'];
}

if(isset($_GET['edit'])){
    $modo = "1";
    $objdata = $objetivo->getObjetivo($_GET['edit']);    
}else{
    $modo = "0";
}
$lista = $objetivo->getObjetivos();
?>

<h2>Agregar / Modificar Objetivos</h2>
<p></p>
<form name="formObjetivo" id="formObjetivo" method="POST" enctype="multipart/form-data">

    <table>
        <tr>
            <td><p>C&oacute;digo:</p></td>
            <td><input type="text" name="codigo" value="<?php if($modo){ echo $objdata[0]['CODIGO']; }?>" size="15" <?php //if($modo) echo 'readonly="readonly"';?>/></td>
        </tr>
        <tr>
            <td><p>Nombre:</p></td>
            <td><input type="text" name="nombre" value="<?php if($modo){ echo $objdata[0]['NOMBRE'];}?>" size="55" /></td>

        </tr>
         <tr>
            <td><p>Objetivo Padre:</p></td>
            <td><select name="padre">
                    <option value="0">Ning&uacute;n objetivo</option>
                    <?php
                            foreach($lista as $obj){
                                $selected='';
                                if($modo){
                                  if($_GET['edit'] != $obj['IDOBJETIVO']) {
                                    if($obj['IDOBJETIVO'] == $objdata[0]['OBJETIVO_PADRE']){
                                        $selected = 'Selected';
                                    }
                                     echo '<option value="'.$obj['IDOBJETIVO'].'" '.$selected.'>'.$obj['NOMBRE'].'</option>';
                                  }

                                }else{
                                    echo '<option value="'.$obj['IDOBJETIVO'].'" '.$selected.'>'.$obj['NOMBRE'].'</option>';
                                }
                            }
                    ?>
                </select></td>
        </tr>
        <tr>
            <td><p>Signo:</p></td>
            <td><select name="signo" size="2">
                    <option value="+" <?php
                            if($modo){
                                if($objdata[0]['SIGNO'] == '+')
                                    echo 'selected';
                            }else{
                                echo "selected";
                            }

                    ?>>+</option>
                    <option value="-" <?php
                        if($modo){
                                if($objdata[0]['SIGNO'] == '-')
                                    echo 'selected';
                            }
                    ?>>-</option>
                </select></td>
        </tr>
        <tr>
            <td><p>Operaci&oacute;n:</p></td>
            <td><input type="text" name="operacion" value="<?php if($modo){ echo $objdata[0]['OPERACION'];}?>" size="20" /></td>
        </tr>
        <tr>
            <td colspan="2"><input type="submit" value="Aplicar" name="aplicar" onClick="" /></td>
        </tr>

    </table>

</form>

<script type="text/javascript">
    divDestino = 'mod_content';
    formDestino = 'formObjetivo';
   <?php
     if(!$modo){
        echo "urlDestino = 'modules/objetivos/addObjetivo.php?add=1';";
     }else{
       echo "urlDestino = 'modules/objetivos/addObjetivo.php?editObj={$_GET['edit']}';";
     }
     ?>

     var frmvalidator  = new Validator("formObjetivo");

     frmvalidator.EnableMsgsTogether();

     frmvalidator.addValidation('codigo','req','Por favor ingrese el código.');
     frmvalidator.addValidation('nombre','req','Por favor ingrese el nombre.');
     frmvalidator.addValidation('operacion','req','Por favor defina la operación.');
 </script>