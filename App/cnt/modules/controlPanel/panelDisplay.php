<?php
/**
 * ARCHIVO QUE MUESTRA EL MENU DE ACCESO A LOS MODULOS
 * CONTIENE LA PLANTILLA GENERAL DE LA APLICACION
 * PHP version 5
 *
 * @autor Jorge Tenorio
 * @since 25/03/2010
 * @return
 * @uses
 */


if(!isset($_SESSION['USER_id']))
    session_start();
    
//print_r($_SESSION);
include_once($_SESSION['path'].'/modules/controlPanel/launch.php');

$panel = new ControlPanel();
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
   <meta http-equiv="Content-Type" content="text/html; charset=UTF8"></meta>
   
<title>CNT :: BIENVENIDOS :: Administrador de Indicadores</title>
<script src="js/jquery.js" type="text/javascript"></script>
<script src="js/functions.js" type="text/javascript"></script>
<script src="js/ajaxupload.js" type="text/javascript"></script>
<script src="js/gen_validatorv4.js" type="text/javascript"></script>
<script type="text/javascript" src="js/datapick/jquery.datepick.js"></script>
<script type="text/javascript" src="js/jquery.timers.js"></script>
<!--<script type="text/javascript" src="js/jquery.dropshadow.js"></script>
<script type="text/javascript" src="js/mbTooltip.js"></script>-->

<link href="js/datapick/jquery.datepick.css" rel="stylesheet" type="text/css"></link>
<link href="css/reset.css" media="all" type="text/css" rel="stylesheet"></link>
<link href="css/style.css" media="all" type="text/css" rel="stylesheet"></link>
<link rel="stylesheet" type="text/css" href="css/mbTooltip.css" title="style1"  media="screen"></link>

<link href="css/datePicker.css" media="all" type="text/css" rel="stylesheet"></link>

<!--[if IE]>
    <link href="css/ie.css" media="all" type="text/css" rel="stylesheet"></link>
<![endif]-->


<script type="text/javascript">
function resize(){
    var dimensions1 = $('#main_content').height();
   

   if(dimensions1 < 450){
       $('#main_content').height(450);

   }

   if($('#mod_content')){
       if($('#mod_content').height() < 450){
           $('#mod_content').height(450);
       }
   }

 }

 $(function(){
      $("[title]").mbTooltip({ // also $([domElement]).mbTooltip
        opacity : .10,       //opacity
        wait:0,           //before show
        cssClass:"default",  // default = default
        timePerWord:1,      //time to show in milliseconds per word
        hasArrow:false,                 // if you whant a little arrow on the corner
        hasShadow:true,
        imgPath:"images/",
        anchor:"mouse", //or "parent" you can ancor the tooltip to the mouse  or to the element
        shadowColor:"black", //the color of the shadow
        mb_fade:1 //the time to fade-in
      });
    });
</script>

</head>

    <body onkeypress="
  e = event;      
  tecla=(document.all) ? e.keyCode : e.which;
  if(tecla == 13){
   
    return false;
    
  }">

        <div id="top">

            <a href="index.php" id="topLogo"></a>

            <?php echo $panel->generateTopInfo();?>
            <?php echo $panel->generateMenu();?>

        </div>

        <div id="main_content"><?php echo $panel->generatePanel()?></div>
        <div id="estadoCarga" style=""></div>

        <div id="bottom">
            
        </div>

        
      

        <script type="text/javascript">
            //var y = window.setInterval('resize()', 200);
        </script>
</body>
</html>