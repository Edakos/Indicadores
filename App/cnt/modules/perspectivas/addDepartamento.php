<?php
/*
 * vista para agregar departamentos
 * jtenorio
 * MUSHOQ
 * 15-02-2011
 */

@session_start();
include($_SESSION['path'].'/modules/perspectivas/launch.php');

$perspectiva = new Perspectiva();
if(isset($_GET['add'])){
   //agergar el departamento
    $perspectiva->addDepartamento($_POST);
}else if(isset($_GET['editDep'])){
    //actualizar los datos
    $perspectiva->updateDep($_GET['editDep'], $_POST);
    $_GET['edit'] = $_GET['editDep'];
}



////////////////////////////////////////////////
if(isset($_GET['edit'])){
    $modo = "1";
    $data = $perspectiva->getDepInfo($_GET['edit']);
}else{
    $modo = "0";
}

$departamentos = $perspectiva->getDepartamentos();
?>
<h2>Agregar / Modificar Departamento</h2>
<p></p>
<form name="addDepartamento" id="addDepartamento" method="POST" enctype="multipart/form-data">

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
                            foreach($departamentos as $depdata){
                                $selected='';
                                if($modo){
                                  if($_GET['edit'] != $depdata['IDDEPARTAMENTO']) {
                                    if($data[0]['IDPADRE'] == $depdata['IDDEPARTAMENTO']){
                                        $selected = 'Selected';
                                    }
                                    echo '<option value="'.$depdata['IDDEPARTAMENTO'].'" '.$selected.'>'.$depdata['NOMBRE'].'</option>';
                                  }

                                }else{
                                    echo '<option value="'.$depdata['IDDEPARTAMENTO'].'" '.$selected.'>'.$depdata['NOMBRE'].'</option>';
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
            <td colspan="2"><input type="submit" value="Aplicar" name="aplicar" onClick="" /></td>
        </tr>

    </table>

</form>

<script type="text/javascript">
    divDestino = 'mod_content';
    formDestino = 'addDepartamento';
   <?php
     if(!$modo){
        echo "urlDestino = 'modules/perspectivas/addDepartamento.php?add=1';";
     }else{
       echo "urlDestino = 'modules/perspectivas/addDepartamento.php?editDep={$_GET['edit']}';";
     }
     ?>

     var frmvalidator  = new Validator("addDepartamento");

     frmvalidator.EnableMsgsTogether();

     frmvalidator.addValidation('nombre','req','Por favor ingrese el nombre.');
     
    

 </script>