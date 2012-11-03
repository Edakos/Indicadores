<?php
/*
 * vista para agregar departamentos
 * jtenorio
 * MUSHOQ
 * 15-02-2011
 */

@session_start();
include($_SESSION['path'].'/modules/cargos/launch.php');
$cargo = new Cargo();

if(isset($_GET['add'])){
   //agergar
   $cargo->addCargo($_POST);
}else if(isset($_GET['editCargo'])){
    //actualizar los datos
   $cargo->updateCargo($_GET['editCargo'], $_POST);
   $_GET['edit'] = $_GET['editCargo'];
}



////////////////////////////////////////////////
if(isset($_GET['edit'])){
    $modo = "1";
    $data = $cargo->getCargoInfo($_GET['edit']);
}else{
    $modo = "0";
}

$cargos = $cargo->getCargos();
?>
<h2>Crear / Modificar Cargo</h2>
<p></p>
<form name="addCargo" id="addCargo" method="POST" enctype="multipart/form-data">

    <table>
        <tr>
            <td><p>Nombre:</p></td>
            <td><input type="text" name="nombre" value="<?php if($modo){ echo $data[0]['NOMBRE'];}?>" size="30" /></td>
        </tr>

        <tr>
            <td><p>Depende de:</p></td>
            <td><select name="depende">
                    <option value="0">Ning&uacute;n departamento</option>
                    <?php
                            foreach($cargos as $depdata){
                                $selected='';
                                if($modo){

                                  if($_GET['edit'] != $depdata['IDCARGO']) {
                                    if($data[0]['IDPADRE'] == $depdata['IDCARGO']){
                                        $selected = 'Selected';
                                        echo '<option value="'.$depdata['IDCARGO'].'" '.$selected.'>'.$depdata['NOMBRE'].'</option>';
                                    }
                                  }
                                }else{
                                    echo '<option value="'.$depdata['IDCARGO'].'" '.$selected.'>'.$depdata['NOMBRE'].'</option>';
                                }
                                
                            }
                    ?>
                </select>
            </td>
        </tr>
        <tr>
            <td><p>Estado:</p></td>
            <td><p><input type="radio" name="estado" value="1" <?php if($modo) { if ($data[0]['ESTADO'] == 1) echo 'checked="checked"'; }else{echo 'checked="checked"';}?> /> Activo - <input type="radio" name="estado" value="0" <?php if($modo) { if ($data[0]['ESTADO'] == 0) echo 'checked="checked"'; }?>/>Inactivo</p></td>
        </tr>
        <tr>
            <td><p>Descripci&oacute;n:</p></td>
            <td><textarea name="descripcion" rows="6" cols="30" ><?php if($modo){ echo $data[0]['DESCRIPCION'];}?></textarea>
            </td>
        </tr>

        <tr>
            <td><p>Responsable:</p></td>
            <td><p><input type="text" name="responsable" value="<?php if($modo){ echo $data[0]['RESPONSABLE'];}?>" size="15" /></p></td>
        </tr>

        <tr>
            <td><p>e-mail:</td>
            <td><p><input type="text" name="email" value="<?php if($modo){ echo $data[0]['EMAIL'];}?>" size="20" /></p></td>
        </tr>

        <tr>
            <td colspan="2" align="center"><input type="submit" value="Aplicar" name="aplicar" onClick="" /></td>
        </tr>

    </table>

</form>

<script type="text/javascript">
    divDestino = 'mod_content';
    formDestino = 'addCargo';
   <?php
     if(!$modo){
        echo "urlDestino = 'modules/cargos/addCargo.php?add=1';";
     }else{
       echo "urlDestino = 'modules/cargos/addCargo.php?editCargo={$_GET['edit']}';";
     }
     ?>

     var frmvalidator  = new Validator("addCargo");

     frmvalidator.EnableMsgsTogether();

     frmvalidator.addValidation('nombre','req','Por favor ingrese el nombre.');
     frmvalidator.addValidation("email","email",'Por favor ingrese un e-mail válido.');
         
 </script>