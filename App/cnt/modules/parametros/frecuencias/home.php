<?php
/*
 * Vista del modulo de frecuencias de indicadores
 * Jorge Tenorio
 * 21-02-2011
 * MUSHOQ
 */
@session_start();
include($_SESSION['path'].'/modules/parametros/frecuencias/launch.php');

$frecuencia = new Frecuencia();


if(isset($_GET['add'])){
    $frecuencia->addFrecuencia($_POST);
}else if(isset($_GET['delete'])){
    $frecuencia->deleteFrecuencia($_GET['delete']);
}

///////////////////////////////////////////////////////////////////////////
if(isset($_GET['edit'])){    
    $modo = "1";
}else{
    $modo = "0";
}

$lista = $frecuencia->getFrecuencias();
?>
<h2>Frecuencias</h2>
<div id="lisFrecuencia">
    <h3>Listado de frecuencias</h3>
    <table >
        <tr class="tableTitle">
            <td>Nombre</td>
            <td>Per&iacute;odo</td>
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
                      <td><p>{$item['MESES']}</p></td>";
           //echo      '<td><a href="#" onClick="sendPage(\'null\',\'modules/parametros/frecuencias/home.php?edit='.$item['IDFRECUENCIA'].'\',\'mod_content\');"><img src="'.$_SESSION['url'].'/images/edit.gif"></a></td>';
           echo      '<td><a href="#" onClick="if(confirm(\'Desea eliminar esta frecuencia?\')) {sendPage(\'null\',\'modules/parametros/frecuencias/home.php?delete='.$item['IDFRECUENCIA'].'\',\'mod_content\');}"><img src="'.$_SESSION['url'].'/images/icontrash.gif"></a></td>';
           echo '</tr>';
        }

    ?>
        
    </table>
</div>

<div id="addFrecuencia">
    <h3>Crear una nueva frecuencia</h3>
    <form name="addFrecuenci" id="addFrecuenci" method="POST" enctype="multipart/form-data">
        <table>
            <tr>
                <td><p>Nombre:</p></td>
                <td><p><input type="text" name="nombre" value="" size="15" /></p></td>
            </tr>
             <tr>
                 <td><p>Per&iacute;odo:</p></td>
                <td><p>
                    <select name="meses">
                        <?php
                            for($i=1;$i<=24;$i++){
                                echo '<option value="'.$i.'">'.$i.'</option>';
                            }
                        ?>
                    </select> meses.
                    </p>
                </td>
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
        echo "urlDestino = 'modules/parametros/frecuencias/home.php?add=1';";
     }else{
       echo "urlDestino = 'modules/parametros/frecuencias/home.php?editFreq={$_GET['edit']}';";
     }
    ?>

     var frmvalidator  = new Validator("addFrecuenci");

     frmvalidator.EnableMsgsTogether();

     frmvalidator.addValidation('nombre','req','Por favor ingrese el nombre.');
     frmvalidator.addValidation('nombre','alphanumeric_space','No se permiten caracteres especiales.');
     frmvalidator.addValidation('meses','req','Por favor ingrese el simbolo de la unidad.');
     frmvalidator.addValidation('meses','num','El campo meses solo permite números.');

 </script>