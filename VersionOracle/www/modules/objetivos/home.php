<?php
/*
 * Vista del modulo de objetivos
 * Jorge Tenorio
 * 16-02-2011
 * MUSHOQ
 */

?>

<div id="left_menu">
    <ul>
        <li><a href="#" onClick="sendPage('null','modules/objetivos/listObjetivos.php','mod_content');">Objetivos</a></li>
        <li><a href="#" onClick="sendPage('null','modules/objetivos/arbol.php','mod_content');">Arbol de Objetivos</a></li>
        <li><a href="#" onClick="sendPage('null','modules/objetivos/addObjetivo.php','mod_content');">Crear Objetivo</a></li>
        <li><a href="#" onClick="sendPage('null','modules/objetivos/vincularObjetivo.php','mod_content');">Vincular Objetivo</a></li>
    </ul>
</div>

<div id="mod_content">

</div>

<script type="text/javascript">
    sendPage('null','modules/objetivos/listObjetivos.php','mod_content');
</script>