<?php
/*
 * nombre de divisiones
 * jtenorio
 * MUSHOQ
 * 25-02-2011
 */

@session_start();
include($_SESSION['path'].'/modules/despliegue/launch.php');
$despliegue = new Despliegue();

if(isset($_GET['process'])){
    $despliegue->processLevels($_POST);
}


$niveles = $despliegue->numberOfLevels();
$lista = $despliegue->getSegmentacion();

?>

<h2>Segmentaci&oacute;n</h2>
<p>Su despliegue geogr&aacute;fico est&aacute; dividido en <?php echo $niveles;?> segmentos.</p>
<p></p>

<form name="addNiveles" id="addNiveles" method="POST" enctype="multipart/form-data">

    <table>

            <tr class="tableTitle">
                <th><p>Segmento / Nivel</p></th>
                <th><p>Nombre</p></th>
            </tr>
        <?php
        for($i=1;$i<=$niveles;$i++){
        $valor = "";

        if($i%2)
            $clase = "trImpar";
        else
            $clase = "trPar";
        

        if(isset($lista[$i-1]))
            $valor = $lista[$i-1]['NOMBRE'];
         echo '<tr class="'.$clase.'">
                <td><p>'.$i.'</p></td>
                <td><input type="text" name="nivel'.$i.'" value="'.$valor.'" size="10" /></td>
               </tr>';

        }



       ?>
            <tr>
                <td colspan="2" align="center"><input type="button" value="Aplicar" name="aplicar" onClick="sendPage('addNiveles','modules/despliegue/nameDivision.php?process=1','mod_content');" /></td>
            </tr>
    </table>
</form>

