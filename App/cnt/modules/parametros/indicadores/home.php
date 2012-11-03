<?php
/* 
 * Vista de la configuración de indicadores
 * jtenorio
 * MUSHOQ
 * 22-02-2011
 */
@session_start();
include($_SESSION['path'].'/modules/parametros/indicadores/launch.php');
$config = new ParametrosIndicadores();

if(isset($_GET['edit'])){
    $config->updateValues($_POST);
}


$valores = $config->getValues();
?>
<h2>Parametrizaci&oacute;n de indicadores</h2>
<form name="parametros" id="parametros" method="POST" enctype="multipart/form-data">

    <table>
        <tr class="trImpar">
            <td><p>D&iacute;a del mes que inicia el ingreso de valores ejecutados:</p></td>
            <td><p>
                        <select name="diauno">
                            <?php
                                for($i=1;$i<=31;$i++){
                                    $selected = "";
                                    if($i == $valores[0]['DIA_INICIO'])
                                        $selected = "selected";
                                    echo '<option value="'.$i.'" '.$selected.'>'.$i.'</option>';
                                }
                            ?>
                        </select> de cada mes.
                        </p>
            </td>
        </tr>

        <tr class="trPar">
            <td><p>N&uacute;mero de d&iacute;a para el ingreso de valores ejecutados:</p></td>
            <td><p>
                        <select name="diados">
                            <?php
                                for($i=5;$i<=20;$i++){
                                    $selected = "";
                                    if($i == $valores[0]['DIAS_INGRESO'])
                                        $selected = "selected";
                                    echo '<option value="'.$i.'" '.$selected.'>'.$i.'</option>';
                                }
                            ?>
                        </select> dias.
                        </p>
            </td>
        </tr>
        
        <tr class="trImpar">
            <td><p>N&uacute;mero m&aacute;ximo de d&iacute;a extra para el ingreso de valores ejecutados:</p></td>
            <td><p>
                        <select name="diatres">
                            <?php
                                for($i=0;$i<=10;$i++){
                                    $selected = "";
                                    if($i == $valores[0]['MAXIMO_INGRESO'])
                                        $selected = "selected";
                                    echo '<option value="'.$i.'" '.$selected.'>'.$i.'</option>';
                                }
                            ?>
                        </select> dias.
                        </p>
            </td>
        </tr>
        <tr class="trPar">
            <td colspan="2" align="center"><input type="button" value="Aplicar" name="aplicar" onClick="sendPage('parametros','modules/parametros/indicadores/home.php?edit=1','mod_content');" /></td>
        </tr>
    </table>

</form>