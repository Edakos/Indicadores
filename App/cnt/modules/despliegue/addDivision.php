<?php
/*
 * vista para agregar departamentos
 * jtenorio
 * MUSHOQ
 * 15-02-2011
 */

@session_start();
include($_SESSION['path'].'/modules/despliegue/launch.php');
$despliegue = new Despliegue();

if(isset($_GET['padre'])){
    $idPadre = $_GET['padre'];
}

if(isset($_GET['add'])){
   //agergar
    $despliegue->addDivision($idPadre,$_POST);
   
}else if(isset($_GET['editDiv'])){
    //actualizar los datos
  $despliegue->updateDvision($_GET['editDiv'], $_POST);
  $_GET['edit'] = $_GET['editDiv'];
   
}



////////////////////////////////////////////////
if(isset($_GET['edit'])){
    $modo = "1";
    $data = $despliegue->getDivisionInfo($_GET['edit']);
}else{
    $modo = "0";
}


?>
<h2>Crear / Modificar Divisiones Geogr&aacute;ficas</h2>
<p></p>
<form name="addDivision" id="addDivision" method="POST" enctype="multipart/form-data">

    <table>
        <tr>
            <td><p>* Nombre:</p></td>
            <td><input type="text" name="nombre" value="<?php if($modo){ echo $data[0]['NOMBRE'];}?>" size="30" /></td>
        </tr>
        <tr>
            <td><p>C&oacute;digo:</p></td>
            <td><input type="text" name="codigo" value="<?php if($modo){ echo $data[0]['CODIGO'];}?>" size="30" /></td>
        </tr>
        
        
        <tr>
            <td><p>Descripci&oacute;n:</p></td>
            <td><textarea name="descripcion" rows="6" cols="30" ><?php if($modo){ echo $data[0]['DESCRIPCION'];}?></textarea>
            </td>
        </tr>

       <tr>
            <td colspan="2" align="center"><input type="submit" value="Aplicar" name="aplicar" onClick="" /></td>
       </tr>

    </table>

</form>

<script type="text/javascript">
    divDestino = 'mod_content';
    formDestino = 'addDivision';
   <?php
     if(!$modo){
        echo "urlDestino = 'modules/despliegue/addDivision.php?add=1&padre=$idPadre';";
     }else{
       echo "urlDestino = 'modules/despliegue/addDivision.php?editDiv={$_GET['edit']}';";
     }
     ?>

     var frmvalidator  = new Validator("addDivision");

     frmvalidator.EnableMsgsTogether();

     frmvalidator.addValidation('nombre','req','Por favor ingrese el nombre.');
              
 </script>