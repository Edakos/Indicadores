<?php
/*
 * Vinculación de objetivos
 * Jorge Tenorio
 * 16-02-2011
 * MUSHOQ
 */


@session_start();
include($_SESSION['path'].'/modules/objetivos/launch.php');
include($_SESSION['path'].'/modules/perspectivas/launch.php');

$objetivo = new Objetivo();
$departamento = new Perspectiva();

if(isset($_GET['vincular'])){
    $objetivo->vincular($_POST);
}else if(isset($_GET['delete'])){
    $objetivo->deleteVinculo($_GET['delete']);
}



//////////////////////////////////////////////////////////////////
$lista = $objetivo->getObjetivos(true);
$listaDep = $departamento->getDepartamentos();
?>

<div id="vincular">
    <form name="frmVincular" id="frmVincular" method="POST">
        <p>
            Vincular <select name="departamento">
                <?php
                            foreach($listaDep as $dep){
                                echo '<option value="'.$dep['IDDEPARTAMENTO'].'" >'.$dep['NOMBRE'].'</option>';
                            }
                  ?>
              
            </select> al objetivo :
            <select name="objetivo">
                 <?php
                            foreach($lista as $obj){
                                echo '<option value="'.$obj['IDOBJETIVO'].'" >'.$obj['NOMBRE'].'</option>';
                            }
                  ?>
            </select>
            <input type="button" value="Vincular" name="vincular" onClick="sendPage('frmVincular','modules/objetivos/vincularObjetivo.php?vincular=1','mod_content');"/>
        </p>
    </form>
</div>
<p></p>
<h2>V&iacute;nculos existentes</h2>
<p></p>
<div id="listaVinculos">
    <?php
        echo $objetivo->listaVinculados();
    ?>
</div>

