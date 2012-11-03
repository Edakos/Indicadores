<?php
/*
 * vista del modulo del arbol de indicadoresm editar indicadores
* jtenorio
 * MUSHOQ
 * 15-02-2011
 */
@session_start();
require_once($_SESSION['path'].'/modules/arbolIndicadores/launch.php');
$arbol = new ArbolIndicadores();

$indicador = $_GET['id'];

if(isset($_GET['updateMetas'])){
    $arbol->updateMetasValues($_POST, $indicador);
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
<p>Indicador: <?php echo $arbol->getIndicadorName($indicador);?></p>
<form name="addMetas" id="addMetas" method="POST" enctype="multipart/form-data">

<?php
    $arbol->generarMetasEntry($indicador);
?>
    <p></p>
    <p></p>
    <table>
        <tr>
            <td>
                <p class="error">Justificaci&oacute;n del Cambio</p>
            </td>
            <td>
                <textarea name="justificacion" id="justificacion" rows="8" cols="30">
                </textarea>
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
                            if(validaInputs('text')){
                                if($('#justificacion').val() != ''){
                                    sendPage('addMetas','modules/arbolIndicadores/editMetas.php?id=<?php echo $indicador;?>&updateMetas=1','mod_content_user');
                                    }else{
                                        alert('Debe ingresar la justificación del cambio antes de continuar.');
                                    }
                                }else{
                                    alert('Debe ingresar los valores meta para cada despliegue geográfico.');
                                }
                            "/></p>
</form>