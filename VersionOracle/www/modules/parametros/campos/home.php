<?php
/*
 * Vista del modulo de frecuencias de indicadores
 * Jorge Tenorio
 * 21-02-2011
 * MUSHOQ
 */
@session_start();
include($_SESSION['path'].'/modules/parametros/campos/launch.php');

$campos = new Campos();


if(isset($_GET['add'])){
    $campos->addCampo($_POST);
}else if(isset($_GET['delete'])){
   $campos->deleteCampo($_GET['delete']);
}

///////////////////////////////////////////////////////////////////////////
if(isset($_GET['edit'])){    
    $modo = "1";
}else{
    $modo = "0";
}

$lista = $campos->getCampos();
?>
<h2>Campos adicionales para indicadores</h2>
<p>Campos opcionales para filtrado en los indicadores.</p>
<div id="lisFrecuencia">
    <h3>Listado de campos</h3>
    <table>
        <tr class="tableTitle">
            <td>Nombre</td>
           
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
            echo "<td><p>{$item['NOMBRE']}</p></td>";
           //echo      '<td><a href="#" onClick="sendPage(\'null\',\'modules/parametros/frecuencias/home.php?edit='.$item['IDFRECUENCIA'].'\',\'mod_content\');"><img src="'.$_SESSION['url'].'/images/edit.gif"></a></td>';
           echo      '<td><a href="#" onClick="if(confirm(\'Desea eliminar este campo?\')) {sendPage(\'null\',\'modules/parametros/campos/home.php?delete='.$item['IDCAMPO'].'\',\'mod_content\');}"><img src="'.$_SESSION['url'].'/images/icontrash.gif"></a></td>';
           echo '</tr>';
        }

    ?>
        
    </table>
</div>

<div id="addFrecuencia">
    <h3>Crear una nuevo campo</h3>
    <form name="addFrecuenci" id="addFrecuenci" method="POST" enctype="multipart/form-data">
        <table>
            <tr>
                <td><p>Nombre:</p></td>
                <td><p><input type="text" name="nombre" value="" size="15" /></p></td>
            </tr>
            
            <tr>
                <td colspan="2"><input type="submit" value="Aplicar" name="aplicar" onClick="" /></td>
            </tr>
        </table>
    </form> 
</div>

<script type="text/javascript">
    divDestino = 'mod_content';
    formDestino = 'addFrecuenci';
   <?php
     if(!$modo){
        echo "urlDestino = 'modules/parametros/campos/home.php?add=1';";
     }else{
       echo "urlDestino = 'modules/parametros/campos/home.php?editFreq={$_GET['edit']}';";
     }
    ?>

     var frmvalidator  = new Validator("addFrecuenci");

     frmvalidator.EnableMsgsTogether();

     frmvalidator.addValidation('nombre','req','Por favor ingrese el nombre.');
     
    
 </script>