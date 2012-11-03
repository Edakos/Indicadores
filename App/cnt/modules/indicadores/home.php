<?php
/* 
 * vista del modulo de indicadores
* jtenorio
 * MUSHOQ
 * 24-02-2011
 */

?>
<div id="left_menu">
    <ul>
        <li><a href="#" onClick="sendPage('null','modules/indicadores/listIndicadores.php','mod_content');">Lista de Indicadores</a></li>
        <li><a href="#" onClick="sendPage('null','modules/indicadores/addIndicador.php','mod_content');">Crear Indicador</a></li>
        <li><a href="#" onClick="sendPage('null','modules/indicadores/controlCambios.php','mod_content');">Control de Cambios</a></li>
    </ul>
</div>

<div id="mod_content">
    
</div>

<script type="text/javascript">
    sendPage('null','modules/indicadores/listIndicadores.php','mod_content');
</script>