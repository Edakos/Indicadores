<?php
/*
 * vista del modulo del arbol de indicadores, agregar o editar indicadores en el arbol
* jtenorio
 * MUSHOQ
 * 15-02-2011
 */
@session_start();
require_once($_SESSION['path'].'/modules/arbolIndicadores/launch.php');

$arbol = new ArbolIndicadores();

//////////////////////////////////////////////////////////

if(isset($_GET['addMetas'])){
    //agregar lso nuevos valores para el nuevo indicador del nuevo objetivo

    $arbol->addMetas($_GET['padre'], $_GET['tipo'],$_GET['anio'], $_POST,$_GET['addOther']);
}
//////////////////////////////////////////////////////////

if(isset($_GET['addInd'])){
    $padre = $_GET['addInd'];
    $tipo = "O";
}else if(isset($_GET['addIndHijo'])){
    $padre = $_GET['addIndHijo'];
    $tipo = "I";
}




?>

<h2>Indicadores</h2>

<form name="indicadores" method="POST" id="indicadores" enctype="multipart/form-data">
                <table >
                    <tr>
                        <td colspan="2"><h2>A&ntilde;o vigente <?php echo date('Y');?></h2></td>                        
                    </tr>
                    
                     <tr>
                        <td ><p>A&ntilde;o de ingreso de valores meta</p></td>
                        <td><select name="anoIngreso" id="anoIngreso">
                                <option value="<?php echo date('Y');?>"><?php echo date('Y');?></option>
                                <option value="<?php echo date('Y') +1;?>"><?php echo date('Y') +1;?></option>
                            </select></td>
                    </tr>
                    
                    <tr>
                        <td>
                            <p>Indicador:</p>
                        </td>
                        <td>
                            <select name="indicador" id="indicador" onchange="
                                if(this.value != '-1'){
                                    sendPage('null','modules/arbolIndicadores/metasEntry.php?idIndicador='+this.value+'&anio='+$('#anoIngreso').val(),'metasEntry');
                                     $('#rowbotones').fadeIn(1000);
                                }else{
                                   
                                    $('#rowbotones').fadeOut(1000);
                                }">
                                <option value="-1" style="background-color: red;">Seleccione el indicador</option>
                                <?php
                                    $listaIndi = $arbol->getAvailableIndicadores();

                                    foreach($listaIndi as $indic){

                                        echo '<option value="'.$indic['IDINDICADOR'].'">'.$indic['CODIGO'].'-'.$indic['NOMBRE'].'</option>';
                                    }
                                ?>
                            </select>
                        </td>
                    </tr>
                    <tr>
                        <td colspan="2" align="center">
                            <div id="metasEntry">
                                
                            </div>
                        </td>
                    </tr>
                   
                    <tr id="rowbotones" style="display: none;">
                        <td colspan="1" align="center"><input type="button" value="Finalizar" name="aplicar" onClick="
                            if(validaInputs('required')){
                                sendPage('indicadores','modules/arbolIndicadores/addIndicador.php?addMetas=1&padre=<?php echo $padre;?>&tipo=<?php echo $tipo;?>&addOther=0&anio='+$('#anoIngreso').val(),'mod_content_user');
                                }else{
                                    alert('Debe ingresar los valores meta para cada despliegue geográfico.');
                                }
                            "/></td>
                        <td colspan="1" align="center"><input type="button" value="Agregar otro indicador" name="aplicar2" onClick="
                            if(validaInputs('required')){
                                sendPage('indicadores','modules/arbolIndicadores/addIndicador.php?addMetas=1&padre=<?php echo $padre;?>&tipo=<?php echo $tipo;?>&addOther=1&anio='+$('#anoIngreso').val(),'mod_content_user');
                                }else{
                                    alert('Debe ingresar los valores meta para cada despliegue geográfico.');
                                }
                            "/></td>
                   </tr>

                </table>          
</form>