<?php
/*
 * Listado de objetivos
 * Jorge Tenorio
 * 16-02-2011
 * MUSHOQ
 */


@session_start();
include($_SESSION['path'].'/modules/objetivos/launch.php');

$objetivo = new Objetivo();


if(isset($_GET['delete'])){
    $objetivo->deleteObjetivo($_GET['delete']);
}

/////////////////////////////////////////////////////
$lista = $objetivo->getObjetivos();
?>

<table cellpadding="12">
    <tr class="tableTitle">
        <td><b>C&Oacute;DIGO</b></td>
        <td><b>NOMBRE</b></td>
        <td><b>SIGNO</b></td>
        <td><b>OPERACI&Oacute;N</b></td>
    </tr>
    <?php
    //imprimir la lista de objetivos
        $i=0;
        foreach($lista as $item){
            if($i%2)
                $clase = "trImpar";
            else
                $clase = "trPar";
            $i++;
            echo '<tr class="'.$clase.'">';
            echo "<td><p>{$item['CODIGO']}</p></td>
                      <td><p>{$item['NOMBRE']}</p></td>
                      <td><p>{$item['SIGNO']}</p></td>
                      <td><p>{$item['OPERACION']}</p></td>";
           echo      '<td><a href="#" onClick="sendPage(\'null\',\'modules/objetivos/addObjetivo.php?edit='.$item['IDOBJETIVO'].'\',\'mod_content\');"><img src="'.$_SESSION['url'].'/images/edit.gif"></a></td>';
           //echo      '<a href="#" onClick="if(confirm(\'Se desvincularan los indicadores vinculados a este objetivo, desea continuar?\')) {sendPage(\'null\',\'modules/objetivos/listObjetivos.php?delete='.$item['IDOBJETIVO'].'\',\'mod_content\');}"><img src="'.$_SESSION['url'].'/images/icontrash.gif"></a>';

        }

    ?>
</table>


