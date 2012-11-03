<?php
/*
 * vista del modulo de indicadores , añadir editar campos adicionales al indicador
 * jtenorio
 * MUSHOQ
 * 16-03-2011
 */
@session_start();
include($_SESSION['path'].'/modules/indicadores/launch.php');
$indicador = new Indicador();

$lista = $indicador->getCampos();

///////////////////////////////////////////////////////////////////////////////
if(isset($_GET['addTemp'])){
    $indicador->addCampoTemp($_GET['idCampo'], $_GET['valor']);
}else if(isset($_GET['remover'])){
    $indicador->removeCampo($_GET['remover']);
}

?>


    <table>
        <tr class="trPar">
            <td>
                <p>A&ntilde;adir campo</p>
            </td>
            <td>
                <select name="campo" id="campo">
                    <?php
                    foreach ($lista as $value) {
                         echo '<option value="'.$value['IDCAMPO'].'">'.$value['NOMBRE'].'</option>';
                    }
                    ?>
                </select>
            </td>
            <td>
                <input type="text" name="valor" id="valor" value="" size="12" maxlength="50"/>
            </td>
            <td>
                <input type="button" value="Aplicar" name="addNewVal" onclick="
                    if($('#valor').val()== ''){
                        alert('Debe ingresar un valor');
                    }else{
                        sendPage('null','modules/indicadores/camposAdicionales.php?addTemp=1&idCampo='+$('#campo').val()+'&valor='+$('#valor').val(),'camposAdicionales');
                    }
               "/>
            </td>
        </tr>
        <tr class="trImpar">
            <td colspan="4">
                <div id="listAdicionales">

                    <table>
                        <?php
                            //print_r($_SESSION['CAMPOS_TEMPORALES']);

                            $i=0;
                            foreach($_SESSION['CAMPOS_TEMPORALES'] as $value){

                                    if($i%2)
                                        $clase = "trImpar";
                                    else
                                        $clase = "trPar";
                                    

                                $datos = explode('_', $value);
                                if(count($datos == 2)){
                                    
                                    $campo = $indicador->getCampo($datos[0]);
                                    echo  '<tr class="'.$clase.'">';
                                    echo "<td>{$campo[0]['NOMBRE']}</td><td>{$datos[1]}</td>";
                                    echo "<td><a onclick=\"if(confirm('Desea eliminar este campo?')){ sendPage('null','modules/indicadores/camposAdicionales.php?remover=$i','camposAdicionales');}\"><img src=\"{$_SESSION['url']}/images/icontrash.gif\"></a></td>";
                                    echo "</tr>";
                                }

                                $i++;
                            }

                        ?>
                    </table>
                </div>
            </td>
        </tr>
    </table>

