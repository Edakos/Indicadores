<?php
/**
 * CARGAR DEL PANEL DE CONTROL PRINCIPAL PARA LOS USUARIOS
 * @autor Jorge Tenorio
 * @since 25/03/2010
 */

//require_once($_SESSION['path'].'/modules/errorHandler/launch.php');
require_once($_SESSION['path'].'/conf/includes.php');


class ControlPanel{


    public function __construct(){
        
    }

    public function generateMenu(){
        //obtener los items de menu y devolver el html listo
        
       $data = file($_SESSION["path"].'/conf/modules.conf');
        
        $html = '<div id="topMenu"><ul><li class="top_izq"></li>';
        foreach($data as $module){
             $modParam = explode('|',$module);
             $html .= "<li><a  onclick=\"sendPage('null','modules/{$modParam[1]}','main_content');\">{$modParam[0]}</a></li>";
        }
        $html .= '</ul></div>';

        return $html;
    }
    
    public function generatePanel(){
        //obtener los items de menu y devolver el html listo
        //require_once($_SESSION["path"].'/modules/ado/launch.php');
        


        $data = file($_SESSION["path"].'/conf/modules.conf');



        $html = '<ul id="panelMenu">';
        foreach($data as $module){
            $modParam = explode('|',$module);
            $html .= "<li><a  onclick=\"sendPage('null','modules/{$modParam[1]}','main_content');\"><img src=\"{$_SESSION['url']}/images/{$modParam[2]}\"><p>{$modParam[0]}</p></a></li>";
        }
        $html .= '</ul>';

        return $html;
    }

    public function generateTopInfo(){
        //obtener los items de menu y devolver el html listo
        $facha = $this->actual_date();
        
        $html = '<div id="topInfo"><ul >';
        $html .= '<li id="user">'.htmlentities($_SESSION['USER_nombre']).'</li>';
        if(isset($_SESSION['DISTRIB_nombre'])){
            $html .= '<li id="empresa">'.$_SESSION['DISTRIB_nombre'].'</li>';
        }
        $html .= '<li id="logout"><a href="modules/login/launch.php?logout=1"><img src="images/logOut.png">Salir</a></li>';
        $html .= '<li class="fecha">'.$facha.'</li><li class=""></li></ul></div>';

        return $html;
    }

    private     function actual_date ()  
    {  
        $week_days = array ("Domingo", "Lunes", "Martes", "Miercoles", "Jueves", "Viernes", "Sabado");  
        $months = array ("", "Enero", "Febrero", "Marzo", "Abril", "Mayo", "Junio", "Julio", "Agosto", "Septiembre", "Octubre", "Noviembre", "Diciembre");  
        $year_now = date ("Y");  
        $month_now = date ("n");  
        $day_now = date ("j");  
        $week_day_now = date ("w");  
        $date = $week_days[$week_day_now] . ", " . $day_now . " del " . $months[$month_now] . " de " . $year_now;   
        return $date;    
    }  

   
}



