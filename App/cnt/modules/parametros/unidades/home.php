<?php
/*
 * Vista del submodulo de unidades
 * Jorge Tenorio
 * 17-02-2011
 * MUSHOQ
 */

@session_start();
include($_SESSION['path'].'/modules/parametros/unidades/launch.php');

$unidad = new Unidades();


if(isset($_GET['add'])){
    //agregar unidad
    $unidad->addUnidad($_POST);
}else if(isset($_GET['editUnidad'])){
    $unidad->editUnidad($_GET['editUnidad'], $_POST);
}else if(isset($_GET['delete'])){
    $unidad->deleteUnidad($_GET['delete']);
}

//////////////////////////////////////////////////////
if(isset($_GET['edit'])){
    $data = $unidad->getUnidad($_GET['edit']);
    $modo = "1";
}else{
    $modo = "0";
}

$lista = $unidad->getUnidades();
?>

<h2>Unidades</h2>

<div id="addUnidades">
    <p>Agregar una nueva unidad</p>
    <form name="addUnidad" id="addUnidad" method="POST" enctype="multipart/form-data">
        <table>
            <tr>
                <td>Nombre</td>
                <td><input type="text" name="nombre" value="<?php if($modo) echo $data[0]['NOMBRE'];?>" size="15" /></td>
            </tr>
            <tr>
                <td>S&iacute;mbolo</td>
                <td><input type="text" name="simbolo" value="<?php if($modo) echo $data[0]['SIMBOLO'];?>" size="5" /></td>
            </tr>
            <tr>
                <td>Descripci&oacute;n</td>
                <td><input type="text" name="descripcion" value="<?php if($modo) echo $data[0]['DESCRIPCION'];?>" size="20" /></td>
            </tr>
             <tr>
                <td colspan="2"><input type="submit" value="Aplicar" name="aplicar" onClick="" /></td>
            </tr>
        </table>
    </form>
</div>
<p></p>
<p></p>
<div id="listUnidades">
    <p>Listado de Unidades existentes</p>
    <p></p>
    <table border="1">
        <tr class="tableTitle">
            <td>NOMBRE</td>
            <td>SIMBOLO</td>
            <td>DESCRIPCI&Oacute;N</td>
            <td></td>
            <td></td>
        </tr>
        <?php
    //imprimir la lista de objetivos
        $i=0;

        foreach($lista as $item){
            if($i%2)
                $clase = "trImpar";
            else
                $clase = "trPar";
            $i++;

              echo '<tr class="'.$clase.'">';
              echo "<td><p>{$item['NOMBRE']}</p></td>
                      <td><p>{$item['SIMBOLO']}</p></td>
                      <td><p>{$item['DESCRIPCION']}</p></td>";
           echo      '<td><a href="#" onClick="sendPage(\'null\',\'modules/parametros/unidades/home.php?edit='.$item['IDUNIDAD'].'\',\'mod_content\');"><img src="'.$_SESSION['url'].'/images/edit.gif"></a></td>';
           echo      '<td><a href="#" onClick="if(confirm(\'Desea eliminar esta unidad?\')) {sendPage(\'null\',\'modules/parametros/unidades/home.php?delete='.$item['IDUNIDAD'].'\',\'mod_content\');}"><img src="'.$_SESSION['url'].'/images/icontrash.gif"></a></td>';
           echo '</tr>';
        }

    ?>
    </table>
</div>


<script type="text/javascript">
    divDestino = 'mod_content';
    formDestino = 'addUnidad';
   <?php
     if(!$modo){
        echo "urlDestino = 'modules/parametros/unidades/home.php?add=1';";
     }else{
       echo "urlDestino = 'modules/parametros/unidades/home.php?editUnidad={$_GET['edit']}';";
     }
    ?>

     var frmvalidator  = new Validator("addUnidad");

     frmvalidator.EnableMsgsTogether();

     frmvalidator.addValidation('nombre','req','Por favor ingrese el nombre.');
     frmvalidator.addValidation('nombre','alphanumeric_space','No se permiten caracteres especiales.');
     frmvalidator.addValidation('simbolo','req','Por favor ingrese el símbolo de la unidad.');
    

 </script>