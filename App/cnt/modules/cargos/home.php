<?php
/* 
 * vista del modulo de prespectivas
* jtenorio
 * MUSHOQ
 * 15-02-2011
 */

?>
<div id="left_menu">
    <ul>
        <li><a href="#" onClick="sendPage('null','modules/cargos/listCargos.php','mod_content');">Cargos</a></li>
        <li><a href="#" onClick="sendPage('null','modules/cargos/addCargo.php','mod_content');">Crear Cargo</a></li>
    </ul>
</div>

<div id="mod_content">
    
</div>

<script type="text/javascript">
    sendPage('null','modules/cargos/listCargos.php','mod_content');
</script>