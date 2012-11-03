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
        <li><a href="#" onClick="sendPage('null','modules/perspectivas/listDepartamentos.php','mod_content');">Departamentos</a></li>
        <li><a href="#" onClick="sendPage('null','modules/perspectivas/addDepartamento.php','mod_content');">Crear Departamento</a></li>        
    </ul>
</div>

<div id="mod_content">
    
</div>

<script type="text/javascript">
    sendPage('null','modules/perspectivas/listDepartamentos.php','mod_content');
</script>