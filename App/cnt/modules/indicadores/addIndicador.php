<?php
/*
 * vista del modulo de indicadores , crear o editar indicador
 * jtenorio
 * MUSHOQ
 * 24-02-2011
 */

@session_start();
include($_SESSION['path'].'/modules/indicadores/launch.php');
$indicador = new Indicador();

if(isset($_GET['add'])){

    //si la formula es otra q o este predefinida
    if($_POST['formula']=='extra'){
        $_POST['formula'] = $_POST['formula_extra'];
    }

    $indicador->addIndicador($_POST);
}else if(isset($_GET['editInd'])){
    //si la formula es otra q o este predefinida
    if($_POST['formula']=='extra'){
        $_POST['formula'] = $_POST['formula_extra'];
    }
    $indicador->editIndicador($_GET['editInd'], $_POST);
    $_GET['edit'] = $_GET['editInd'];
}

////////////////////////////////////////////////////////////
if(isset($_GET['edit'])){
    $modo = "1";
    $data = $indicador->getIndicador($_GET['edit']);
}else{
    $modo = "0";
}

?>


<script language="JavaScript" type="text/JavaScript">
    $(function() {
       $('#vigentehasta').datepick({minDate: new Date(),dateFormat: 'yyyy-mm-dd'});
      
          
    })
</script>


<h2>Crear / Editar Indicadores</h2>
<form name="addIndicador" id="addIndicador" method="POST" enctype="multipart/form-data">
<table border="1" style="border-color: #0078a3;">
    <tr>
        <td><p>C&oacute;digo</p></td>
        <td><p><input type="text" name="codigo" value="<?php if($modo){ echo $data[0]['CODIGO'];}?>" size="24" maxlength="50" /></p></td>
    </tr>

    <tr>
        <td><p>Nivel Estrat&eacute;gico</p></td>
        <td><p><select name="nivel">
                    <option value="1" <?php if($modo){ if($data[0]['NIVEL_ESTRATEGICO']==1){echo "selected";}}?>>1</option>
                    <option value="2" <?php if($modo){ if($data[0]['NIVEL_ESTRATEGICO']==2){echo "selected";}}?>>2</option>
                    <option value="3" <?php if($modo){ if($data[0]['NIVEL_ESTRATEGICO']==3){echo "selected";}}?>>3</option>
                    <option value="4" <?php if($modo){ if($data[0]['NIVEL_ESTRATEGICO']==4){echo "selected";}}?>>4</option>
                    <option value="5" <?php if($modo){ if($data[0]['NIVEL_ESTRATEGICO']==5){echo "selected";}}?>>5</option>
                </select></p></td>
    </tr>
    <tr>
        <td><p>Lectura</p></td>
        <td><p><select name="signo">
                    <option value="+" <?php if($modo){ if($data[0]['SIGNO']=='+'){echo "selected";}}?>>+</option>
                    <option value="-" <?php if($modo){ if($data[0]['SIGNO']=='-'){echo "selected";}}?>>-</option>
                   
                </select></p></td>
    </tr>
    <tr>
        <td><p>* Nombre</p></td>
        <td><p><input type="text" name="nombre" value="<?php if($modo){ echo $data[0]['NOMBRE'];}?>" size="60" maxlength="120"/></p></td>
    </tr>

    <tr>
        <td><p>Unidad</p></td>
        <td><p><select name="unidad">
                    <?php
                    include ($_SESSION['path'].'/modules/parametros/unidades/launch.php');
                    $unidad = new Unidades();
                    $lista = $unidad->getUnidades();

                    foreach($lista as $item){
                        $selected = "";
                        if($modo){
                         if($item['IDUNIDAD'] == $data[0]['UNIDAD'])
                             $selected = "selected";
                        }
                        echo '<option value="'.$item['IDUNIDAD'].'" '.$selected.'>'.$item['NOMBRE'].'</option>';
                    }
                    ?>
                </select></p>
        </td>
    </tr>

    <tr>
        <td><p>* Definici&oacute;n</p></td>
        <td><p><textarea name="definicion" rows="4" cols="60" maxlength="200"><?php if($modo){ echo $data[0]['DEFINICION'];}?></textarea></p>
        </td>
    </tr>

    <tr>
        <td><p>* F&oacute;rmula</p></td>
        <td>
            <p><select name="formula" onchange="
                if(this.value == 'extra'){
                       $('#formula_extra').fadeIn(1000); 
                       //frmvalidator.addValidation('formula_extra','req','Por favor defina la fórmula.');
                }else{
                    $('#formula_extra').fadeOut(1000);
                }
                   ">
                  <?php
                        include ($_SESSION['path'].'/modules/parametros/formula/launch.php');
                        $formula = new Formula();
                        $lista = $formula->getFormulas();
                    
                        $isSelected = FALSE;
                        foreach($lista as $item){
                            $selected = "";
                            if($modo){
                             if($item['IDFORMULA'] === $data[0]['FORMULA']){
                                 $selected = "selected";
                                 $isSelected = TRUE;
                             }
                            }
                           echo '<option value="'.$item['IDFORMULA'].'" '.$selected.'>'.$item['NOMBRE'].'</option>';
                       }

                       $selected = "";
                       if(!$isSelected && $modo){
                           $selected = "selected";
                           $estilo = "display:block!important;";
                       }else{
                           $estilo =  "display:none;";
                       }
                    ?>
                    <option value="extra"   <?php echo $selected;?>>Otra (especificar)</option>
                </select>
            </p>
            
            <p><textarea style="<?php echo $estilo;?>"  name="formula_extra" id="formula_extra" rows="4" cols="60" maxlength="200"><?php if($modo){ echo $data[0]['FORMULA'];}?></textarea>
                </p></td>
    </tr>

    <tr>
        <td><p>Segmento</p></td>
        <td><p><select name="segmento">
                     <?php
                        include ($_SESSION['path'].'/modules/parametros/segmentos/launch.php');
                        $segmento = new Productos2();
                        $lista = $segmento->getProductos();

                        foreach($lista as $item){
                            $selected = "";
                        if($modo){
                         if($item['IDSEGMENTO'] == $data[0]['SEGMENTO'])
                             $selected = "selected";
                        }
                            echo '<option value="'.$item['IDSEGMENTO'].'" '.$selected.'>'.$item['NOMBRE'].'</option>';
                        }
                    ?>
                </select>
            </p></td>
    </tr>

    <tr>
        <td><p>Producto</p></td>
        <td><p><select name="producto">
                    <?php
                        include ($_SESSION['path'].'/modules/parametros/productos/launch.php');
                        $producto = new Productos();
                        $lista = $producto->getProductos();
                        
                        
                        foreach($lista as $item){
                            $selected = "";
                            if($modo){
                             if($item['IDPRODUCTO'] == $data[0]['PRODUCTO'])
                                 $selected = "selected";
                            }
                           echo '<option value="'.$item['IDPRODUCTO'].'" '.$selected.'>'.$item['NOMBRE'].'</option>';
                       }
                    ?>
                </select>
            </p></td>
    </tr>

    <tr>
        <td><p>Tipo</p></td>
        <td><p><select name="tipo">
                    <option value="Resultado" <?php if($modo){ if($data[0]['TIPO']=='Resultado'){echo "selected";}}?>>Resultado</option>
                    <option value="Inductor" <?php if($modo){ if($data[0]['TIPO']=='Inductor'){echo "selected";}}?>>Inductor</option>
                </select>
            </p></td>
    </tr>

    <tr>
        <td><p>Despliegue</p></td>
        <td><p><select name="despliegue">
                   <?php
                        include ($_SESSION['path'].'/modules/despliegue/launch.php');
                       $desp = new Despliegue();
                        $lista = $desp->getSegmentacion();

                        foreach($lista as $item){
                            $selected = "";
                            if($modo){
                             if($item['NIVEL'] == $data[0]['DESPLIEGUE'])
                                 $selected = "selected";
                            }
                           echo '<option value="'.$item['NIVEL'].'" '.$selected.'>'.$item['NOMBRE'].'</option>';
                       }
                    ?>
                </select>
            </p></td>
    </tr>

    <tr>
        <td><p>Frecuencia</p></td>
        <td><p><select name="frecuencia">
                     <?php
                        include ($_SESSION['path'].'/modules/parametros/frecuencias/launch.php');
                        $frec = new Frecuencia();
                        $lista = $frec->getFrecuencias();

                        foreach($lista as $item){
                           $selected = "";
                            if($modo){
                             if($item['IDFRECUENCIA'] == $data[0]['FRECUENCIA'])
                                 $selected = "selected";
                            }
                           echo '<option value="'.$item['IDFRECUENCIA'].'" '.$selected.'>'.$item['NOMBRE'].'</option>';
                       }
                    ?>
                </select>
            </p></td>
    </tr>

    <tr>
        <td><p>Responsable Administraci&oacute;n</p></td>
        <td><p><select name="cargo">
                  <?php
                        include ($_SESSION['path'].'/modules/cargos/launch.php');
                        $cargo = new Cargo();
                        $lista = $cargo->getCargos();
                                               
                        foreach($lista as $item){
                            $selected = "";
                            if($modo){
                             if($item['IDCARGO'] == $data[0]['RESPONSABLE'])
                                 $selected = "selected";
                            }
                           echo '<option value="'.$item['IDCARGO'].'" '.$selected.'>'.$item['NOMBRE'].'</option>';
                       }
                    ?>
                </select>
            </p></td>
    </tr>

    <tr>
        <td><p>Responsable Gesti&oacute;n</p></td>
        <td><p><select name="gestion">
                  <?php
                        include ($_SESSION['path'].'/modules/cargos/launch.php');
                        $cargo = new Cargo();
                        $lista = $cargo->getCargos();
                                                
                        foreach($lista as $item){
                            $selected = "";
                            if($modo){
                             if($item['IDCARGO'] == $data[0]['RESPONSABLE_GERENCIA'])
                                 $selected = "selected";
                            }
                           echo '<option value="'.$item['IDCARGO'].'" '.$selected.'>'.$item['NOMBRE'].'</option>';
                       }
                    ?>
                </select>
            </p></td>
    </tr>

    <tr>
        <td><p>Facilitador</p></td>
        <td><p><select name="facilitador">
                     <?php
                        include ($_SESSION['path'].'/modules/usuarios/launch.php');
                        $user = new Usuarios();
                        $lista = $user->getFacilitadores();

                        foreach($lista as $item){
                            $selected = "";
                            if($modo){
                             if($item['IDUSUARIO'] == $data[0]['FACILITADOR'])
                                 $selected = "selected";
                            }
                           echo '<option value="'.$item['IDUSUARIO'].'" '.$selected.'>'.$item['NOMBRE'].' '.$item['APELLIDO'].'</option>';
                       }
                    ?>
                </select>
            </p></td>
    </tr>

    <tr>
        <td><p>Vigente</p></td>
        <td><p><select name="vigente">
                    <option value="1" <?php if($modo){ if($data[0]['VIGENTE']==1){echo "selected";}}?>>Si</option>
                    <option value="0" <?php if($modo){ if($data[0]['VIGENTE']==0){echo "selected";}}?>>No</option>
                </select></p></td>
    </tr>
    <tr>
        <td><p>Vigente hasta</p></td>
        <td><p><input type="text" name="vigentehasta" id="vigentehasta" value="<?php if($modo){ echo $data[0]['VIGENTE_HASTA'];}?>" size="18" maxlength="50"/></p></td>
    </tr>

    <tr>
        <td><p>Estado</p></td>
        <td><p><select name="estado">
                    <option value="1" <?php if($modo){ if($data[0]['ESTADO']==1){echo "selected";}}?>>Activo</option>
                    <option value="0" <?php if($modo){ if($data[0]['ESTADO']==0){echo "selected";}}?>>Inactivo</option>
                </select>
            </p></td>
    </tr>


    <tr>
        <td><p>Tipo</p></td>
        <td><p><select name="manual">
                    <option value="1" <?php if($modo){ if($data[0]['MANUAL']==1){echo "selected";}}?>>Manual</option>
                    <option value="0" <?php if($modo){ if($data[0]['MANUAL']==0){echo "selected";}}?>>Autom&aacute;tico</option>
                </select>
            </p></td>
    </tr>

    <tr>

        <td><p>Campos adicionales</p></td>
        <td><div id="camposAdicionales">
                <script type="text/javascript">
                    sendPage('null','modules/indicadores/camposAdicionales.php','camposAdicionales');
                </script>
            </div></td>

    </tr>
     


    <tr>
        <td><p>Comentarios</p></td>
        <td><p><textarea name="comentario" rows="4" cols="60"><?php if($modo){ echo $data[0]['COMENTARIO'];}?></textarea></p></td>
    </tr>

    <tr>
            <td colspan="2" align="center"><input type="submit" value="Aplicar" name="aplicar" onClick="" /></td>
    </tr>

</table>
</form>

<script type="text/javascript">
    divDestino = 'mod_content';
    formDestino = 'addIndicador';
   <?php
     if(!$modo){
        echo "urlDestino = 'modules/indicadores/addIndicador.php?add=1';";
     }else{
       echo "urlDestino = 'modules/indicadores/addIndicador.php?editInd={$_GET['edit']}';";
     }
     ?>

     var frmvalidator  = new Validator("addIndicador");

     frmvalidator.EnableMsgsTogether();

     frmvalidator.addValidation('nombre','req','Por favor ingrese el nombre.');
     frmvalidator.addValidation('definicion','req','Por favor ingrese la definición.');
     
     

 </script>