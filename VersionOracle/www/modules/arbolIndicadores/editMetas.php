<?php
/*
 * vista del modulo del arbol de indicadoresm editar indicadores
* jtenorio
 * MUSHOQ
 * 15-02-2011
 */
@session_start();
require_once($_SESSION['path'].'/modules/arbolIndicadores/launch.php');
require_once($_SESSION['path'].'/modules/indicadores/launch.php');
$arbol = new ArbolIndicadores();

$indicadorc = new Indicador();
$indicador = $_GET['id'];

$dataIndicador = $indicadorc->getIndicador($indicador);

if(!isset($_GET['anio'])){
    $anio = date('Y');
}else{
    $anio = $_GET['anio'];
}

if(isset($_GET['updateMetas'])){
    $arbol->updateMetasValues($_POST, $indicador,$anio);
}


?>

<script type="text/javascript">
		/* Usar ajaxupload para la subida de los archivos */

		var button = $('#button1'), interval;

                var upload1;

		upload1 = new AjaxUpload(button, {
			action: 'modules/controlCambios/uploadFile.php',
			name: 'zipFile',
			onSubmit : function(file, ext){


					/* Setting data */
					//button.text('Seleccione el archivo .zip a cargar');

                                        //alert(settings.action);
                                        this.disable();



                                        $('#button1').html('Cargando');


                                        interval = window.setInterval(function(){
                                                var text = $('#button1').html();
                                                if (text.length < 16){
                                                       $('#button1').html(text + '.');
                                                } else {
                                                       $('#button1').html('Cargando');
                                                }
                                        }, 200);

				// change button text, when user selects file

			},
			onComplete: function(file, response){
				//button.text('Seleccione el archivo .zip a cargar');

				window.clearInterval(interval);

				// enable upload button
				this.enable();
                                //alert(response);

                                $('#button1').html(response);
			}
		});
</script>

<h2>Ingreso / Modificaci&oacute;n de valores meta</h2>
<p></p>
<p class="error">Para Modificar estos valores es necesario justificar el motivo de la modificaci&oacute;n</p>
<p></p>
<p><b>C&Oacute;DIGO <?php echo $dataIndicador[0]['CODIGO'];?></b></p>
<p>Indicador: <?php echo $arbol->getIndicadorName($indicador);?></p>
<table style="width: 300px;">
    <tr>
        <td ><p>A&ntilde;o de ingreso de valores meta</p></td>
        <td><select name="anoIngreso" id="anoIngreso" onchange="
                sendPage('null','modules/arbolIndicadores/editMetas.php?id=<?php echo $indicador;?>&anio='+$('#anoIngreso').val(),'mod_content_user');
            ">
                <option value="<?php echo date('Y')-1;?>" <?php if(date('Y')-1 == $anio) echo "selected";?>><?php echo date('Y')-1;?></option>
                <option value="<?php echo date('Y');?>" <?php if(date('Y') == $anio) echo "selected";?>><?php echo date('Y');?></option>
                <option value="<?php echo date('Y') +1;?>" <?php if(date('Y')+1 == $anio) echo "selected";?>><?php echo date('Y') +1;?></option>
            </select></td>
   </tr>
</table>
<form name="addMetas" id="addMetas" method="POST" enctype="multipart/form-data">

<?php
    $arbol->generarMetasEntryToEdit($indicador,$anio);
?>
    <p></p>
    <p></p>
    <table>
        <tr>
            <td>
                <p class="error">Justificaci&oacute;n del Cambio</p>
            </td>
            <td>
                <textarea name="justificacion" id="justificacion" rows="8" cols="30"></textarea>
            </td>
        </tr>
        <tr>
            <td>
                Archivo adjunto:
            </td>
            <td>
                <div id="button1"><p>Click aqu&iacute; para cargar un archivo.</p></div>
            </td>
        </tr>

    </table>

<p style="text-align: center;"><input type="button" value="Aplicar" name="aplicar" onClick="
                            if(validaInputs('required')){
                                if($('#justificacion').val() != ''){
                                    sendPage('addMetas','modules/arbolIndicadores/editMetas.php?id=<?php echo $indicador;?>&updateMetas=1&anio='+$('#anoIngreso').val(),'mod_content_user');
                                    }else{
                                        alert('Debe ingresar la justificación del cambio antes de continuar.');
                                    }
                                }else{
                                    alert('Debe ingresar los valores meta para cada despliegue geográfico.');
                                }
                            "/>
</p>
</form>