<?php
/*
 * listado de los cambios registrados en el sistema
 * Jorge Tenorio
 * 21-03-2011
 * MUSHOQ
 */

@session_start();
include($_SESSION['path'].'/modules/controlCambios/launch.php');
include($_SESSION['path'].'/modules/indicadores/launch.php');
include($_SESSION['path'].'/modules/usuarios/launch.php');

$cc = new ControlCambios();
$indicadoe = new Indicador();
$user = new Usuarios();


if(isset($_GET['from'])){
    $from = $_GET['from'];
}else{
    $from = 0;
}

$dia = date('d');
$mes = date('m');
$anio = date('Y');


if(isset($_GET['fdesde'])){
    $fdesde = $_GET['fdesde'];
}else{
   if($dia > 1) 
    $fdesde =$anio.'-'.$mes.'-'.($dia-1);
   else{
       if($mes > 1)
        $fdesde =$anio.'-'.($mes-1).'-30'; 
       else
           $fdesde =($anio-1).'-12'.$dia; 
   }
}

if(isset($_GET['fhasta'])){
    $fhasta =$_GET['fhasta'];
}else{
    
    $fhasta =  $anio.'-'.$mes.'-'.($dia +3);
}

if(!isset($_GET['filtrar'])){
$lista = $cc->getCambios($from,$fdesde,$fhasta);

}else{
    $filtro = $_POST['filtrotipo'];
    
    switch ($filtro){
        case 'IDUSUARIO':
            $valor = $_POST['usuarios'];
            break;
        case 'IDINDICADOR':
            $valor = $_POST['indicador'];
            break;
    }

    $lista = $cc->getCambiosFiltros($filtro,$valor, $fdesde, $fhasta);
}
?>
<script language="JavaScript" type="text/JavaScript">
    $(function() {
        $('#desde').datepick({dateFormat: 'yyyy-mm-dd'});
        $('#hasta').datepick({dateFormat: 'yyyy-mm-dd'});
    })
</script>

<h2>Control de Cambios de valores en Indicadores</h2>
<form name="filtrar" id="filtrar" method="POST" enctype="multipart/form-data">

<table>
    <tr>
        <td>Filtro:</td>
        <td><select name="filtrotipo" onchange="
                switch(this.value){
                    case 'N':
                        $('#tdfilUser').fadeOut(150);
                        $('#tdfilIndic').fadeOut(150);
                        break;
                   case 'IDUSUARIO':
                        $('#tdfilUser').fadeIn(150);
                        $('#tdfilIndic').fadeOut(150);
                        break;    
                   case 'IDINDICADOR':
                        $('#tdfilUser').fadeOut(150);
                        $('#tdfilIndic').fadeIn(150);
                        break;         
                }
            ">
                <option value="N">Ninguno</option>
                <option value="IDUSUARIO">Por Usuario</option>
                <option value="IDINDICADOR">Por Indicador</option>
            </select></td>
            <td align="center"><input type="button" value="Filtrar" name="filtrar" onClick="sendPage('filtrar','modules/indicadores/controlCambios.php?filtrar=1','mod_content');"/></td>
    </tr>
    
    <tr id="tdfilUser" style="display: none;" class="trPar">
        <td>Usuarios:</td>
        <td>
            <select name="usuarios">
               <?php
                $listUsers = $user->getAllUsers();
             
                foreach($listUsers as $item){
                    echo '<option value="'.$item['IDUSUARIO'].'">'.$item['APELLIDO'].' '.$item['NOMBRE'].'</option>';
                }
               ?>
            </select>
        </td>
        <td></td>
    </tr>
    <tr id="tdfilIndic" style="display: none;" class="trPar">
        <td>Indicador:</td>
        <td>
            <select name="indicador">
                 <?php
                $listInd = $indicadoe->getIndicadores();

                foreach($listInd as $item){
                    echo '<option value="'.$item['IDINDICADOR'].'">'.$item['NOMBRE'].'</option>';
                }
               ?>
            </select>
        </td>
        
    </tr>

    <tr>
        <td><p>Desde: <input type="text" name="desde" id="desde" value="<?php echo $fdesde;?>" size="10" /></p></td>
        <td><p>Hasta: <input type="text" name="hasta" id="hasta" value="<?php echo $fhasta;?>" size="10" /></p></td>
        <td><input type="button" value="Ver Rango" name="mostrar"  onClick="sendPage('null','modules/indicadores/controlCambios.php?fdesde='+$('#desde').val()+'&fhasta='+$('#hasta').val(),'mod_content');"/></td>
    </tr>

    

</table>
</form>
<p></p>
<p></p>
<p></p>

<table>
    <tr class="tableTitle">
        <td>Fecha</td>
        <td>Usuario</td>
        <td>Indicador</td>
        <td>Cambio</td>
        <td>Valor Colocado</td>
        <td>Justificaci&oacute;n</td>
        <td>Archivo</td>
    </tr>
    <?php
        
    if(count($lista)){

        
        $i=0;
        foreach($lista as $item){

            $indName = $indicadoe->getIndicador($item['IDINDICADOR']);
            $userName = $user->getUser($item['IDUSUARIO']);


            if($i%2)
                $clase = "trImpar";
            else
                $clase = "trPar";
            $i++;

            echo '<tr class="'.$clase.'">';
            echo "  <td>{$item['FECHA']}</td>
                    <td>{$userName[0]['APELLIDO']} {$userName[0]['NOMBRE']}</td>
                    <td>{$indName[0]['NOMBRE']}</td>
                    <td>{$item['ACCION']}</td>
                    <td>{$item['VALOR_NUEVO']}</td>
                    <td>{$item['COMENTARIO']}</td>";
                  if(strlen($item['ARCHIVO']))  {
                    echo '<td><b><a href="modules/controlCambios/files/'.$item['ARCHIVO'].'" target="blank">Arhivo disponible</a></b></td>';
                  }else{
                      echo "<td>Sin Archivo</td>";
                  }
                  echo "</tr>";
        }

    }

    

    if($from > 10){
        $fromPrev = $from - 10;
        $linkPrev = '<a href="#" onClick="sendPage(\'null\',\'modules/indicadores/controlCambios.php?from='.$fromPrev.'\',\'mod_content\');"><= Anteriores registros</a>';
    }else if($from == 10){
        $linkPrev = '<a href="#" onClick="sendPage(\'null\',\'modules/indicadores/controlCambios.php?from=0\',\'mod_content\');"><= Anteriores registros</a>';
    }

    if(count($lista) == 10){
        $from = $from +10;
        $linkNext = '<a href="#" onClick="sendPage(\'null\',\'modules/indicadores/controlCambios.php?from='.$from.'\',\'mod_content\');">Siguientes registros =></a>';
    }
    ?>
    <tr>
        <td colspan="7" align="center">
            <?php echo $linkPrev .' | '.$linkNext;?>
        </td>
    </tr>
</table>