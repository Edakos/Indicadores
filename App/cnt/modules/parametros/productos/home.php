<?php
/*
 * Vista del submodulo de unidades
 * Jorge Tenorio
 * 17-02-2011
 * MUSHOQ
 */

@session_start();
include($_SESSION['path'].'/modules/parametros/productos/launch.php');


$productos = new Productos();

if(isset($_GET['add'])){
    $productos->addProducto($_POST);
}else if(isset($_GET['editProd'])){
    $productos->editProducto($_GET['editProd'], $_POST);
}else if(isset($_GET['delete'])){
    $productos->deleteProducto($_GET['delete']);
}

//////////////////////////////////////////////////////
if(isset($_GET['edit'])){
    $data = $productos->getProducto($_GET['edit']);
    $modo = "1";
}else{
    $modo = "0";
}

$lista = $productos->getProductos();
?>

<h2>Productos</h2>

<div id="addUnidades">
    <p>Agregar nuevo producto</p>
    <form name="addProducto" id="addProducto" method="POST" enctype="multipart/form-data">
        <table>
            <tr>
                <td>Nombre</td>
                <td><input type="text" name="nombre" value="<?php if($modo) echo $data[0]['NOMBRE'];?>" size="15" /></td>
            
                <td>Descripci&oacute;n</td>
                <td><input type="text" name="descripcion" value="<?php if($modo) echo $data[0]['DESCRIPCION'];?>" size="20" /></td>
            </tr>
             <tr>
                <td colspan="4"><input type="submit" value="Aplicar" name="aplicar" onClick="" /></td>
            </tr>
        </table>
    </form>
</div>
<p></p>
<p></p>
<div id="listUnidades">
    <p>Listado de productos existentes</p>
    <p></p>
    <table border="1">
        <tr class="tableTitle">
            <td>NOMBRE</td>
            
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
            echo " <td><p>{$item['NOMBRE']}</p></td>
                     
                      <td><p>{$item['DESCRIPCION']}</p></td>";
           echo      '<td><a href="#" onClick="sendPage(\'null\',\'modules/parametros/productos/home.php?edit='.$item['IDPRODUCTO'].'\',\'mod_content\');"><img src="'.$_SESSION['url'].'/images/edit.gif"></a></td>';
           echo      '<td><a href="#" onClick="if(confirm(\'Desea eliminar este producto?\')) {sendPage(\'null\',\'modules/parametros/productos/home.php?delete='.$item['IDPRODUCTO'].'\',\'mod_content\');}"><img src="'.$_SESSION['url'].'/images/icontrash.gif"></a></td>';
           echo '</tr>';
        }

    ?>
    </table>
</div>


<script type="text/javascript">
    divDestino = 'mod_content';
    formDestino = 'addProducto';
   <?php
     if(!$modo){
        echo "urlDestino = 'modules/parametros/productos/home.php?add=1';";
     }else{
       echo "urlDestino = 'modules/parametros/productos/home.php?editProd={$_GET['edit']}';";
     }
    ?>

     var frmvalidator  = new Validator("addProducto");

     frmvalidator.EnableMsgsTogether();

     frmvalidator.addValidation('nombre','req','Por favor ingrese el nombre.');
     frmvalidator.addValidation('nombre','alphanumeric_space','No se permiten caracteres especiales.');
    
    

 </script>