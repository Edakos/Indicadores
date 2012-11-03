<?php
/*
 * Controlador del modulo de cargos
 * Jorge Tenorio
 * 22-02-2011
 * MUSHOQ
 */
@session_start();
require_once($_SESSION['path'].'/conf/includes.php');

class Cargo{

    private $ado;
    private $niveles;

    public function  __construct() {
        $this->ado = new Ado();
        $this->niveles = 0;
    }

    public function getCargos(){
        $sql = "select * from CARGO where ESTADO=1 and IDCARGO > 0";
        $result = $this->ado->query($sql);

        return $result;
    }

    public function addCargo($data){
        
        $sql = "INSERT INTO CARGO(NOMBRE,DESCRIPCION,IDPADRE,ESTADO,RESPONSABLE,EMAIL) VALUES(
                '{$data['nombre']}','{$data['descripcion']}',{$data['depende']},{$data['estado']},'{$data['responsable']}','{$data['email']}')";

        $result = $this->ado->query($sql);
        if(!isset($result['Error'])){

            if(strlen($data['email']))
                $this->notificarResponsable ($data['email'], $data['nombre']);

            echo '<p>Cargo creado satisfactoriamente.</p>';
        }else{
            echo '<p class="error">No se ha podido crear el cargo.</p>';
        }
    }

    public function getCargoTree($id=0){

        //obetener todos los hijos de padre del id

        $sql = "SELECT * FROM CARGO  WHERE IDPADRE=$id";
        $result = $this->ado->query($sql);
        
        foreach($result as $depData){

            $color = $this->color();
            $this->niveles++;
            
            if($this->niveles > 13){
                $colorLetra = "blue";
            }else{
                $colorLetra = "white";
            }
            
            
            echo '<p class="pTree">';

            $deep = $this->profundidad($depData['IDCARGO']);
            for($i=0;$i<$deep;$i++){}
                  
            if(!$this->tieneHijos($depData['IDCARGO']))
                $eliminar= '<a style="float:left;" href="#" onClick="if(confirm(\'Desea eliminar este cargo?\')) {sendPage(\'null\',\'modules/cargos/listCargos.php?delete='.$depData['IDCARGO'].'\',\'mod_content\');}"><img src="'.$_SESSION['url'].'/images/icontrash.gif"></a>';
            else
                $eliminar ='';

            echo '<div class="treeTab" style="width:'.($i*33).'px;">'.$eliminar.'<img src="'.$_SESSION['url'].'/images/fizq.gif"></div>';

            echo '<a href="#" onClick="sendPage(\'null\',\'modules/cargos/addCargo.php?edit='.$depData['IDCARGO'].'\',\'mod_content\');" style="border:#4F8DD2 1px solid; padding:4px; margin-top:2px; background-color:'.$color.'; color:'.$colorLetra.';">'.$depData['NOMBRE'].'</a> </p>';

            
            $this->getCargoTree($depData['IDCARGO']);
        }
        
    }


    private function profundidad($id=0){
        //obtener la profundidad de un padre

        $deep = 0;

        while($id > 0){
            $sql = "SELECT IDPADRE FROM CARGO WHERE ESTADO=1 AND IDCARGO=$id";
            $newId = $this->ado->query($sql);

            $id = $newId[0]['IDPADRE'];
            $deep++;
        }

        return $deep;
    }

    public function getCargoInfo($id){
        $sql = "SELECT * FROM CARGO WHERE IDCARGO=$id";
        $result = $this->ado->query($sql);

        return $result;
    }

    public function updateCargo($id,$data){
        $sql = "UPDATE CARGO SET
                    NOMBRE = '{$data['nombre']}',
                    DESCRIPCION = '{$data['descripcion']}',
                    IDPADRE = {$data['depende']},
                    ESTADO = {$data['estado']},
                    RESPONSABLE = '{$data['responsable']}',
                    EMAIL = '{$data['email']}'
                WHERE
                    IDCARGO = $id
                ";

        $result =  $this->ado->query($sql);
        if(!isset($result['Error'])){
            echo '<p>Cargo actualizado satisfactoriamente.</p>';
        }else{
            echo '<p class="error">No se ha podido actualizar el cargo.</p>';
        }
    }

    private function tieneHijos($id){
        $sql = "SELECT * FROM CARGO WHERE IDPADRE = $id";
        $result = $this->ado->query($sql);

        if(!isset($result['Error'])){
            if(count($result))
                return true;
            else
                return false;
        }else{
            return false;
        }

    }

    public function deleteCargo($id){
        //borrar el nodo hijo
        $sql = "DELETE FROM CARGO WHERE IDCARGO = $id";
        $result =  $this->ado->query($sql);
        
        if(!isset($result['Error'])){
            echo '<p>Cargo eliminado satisfactoriamente.</p>';
        }else{
            echo '<p class="error">No se ha podido eliminar el cargo.</p>';
        }
    }



    private function notificarResponsable($email,$cargo){

                $html='<p><b>SISTEMA DE ADMINISTRACIÓN DE INDICADORES DE LA CNT</b></p>
                        <p>Usted ha sido asignado como responsable del cargo "'.$cargo.'"</p>
                        <p>en el sistema de administración de indicadores.</p>';

                
                 $mail = new Mailer();
                 $mail->sendMail($email,'Notificación de asiganción de responsable de cargo.',$html);
    }

    private function color(){
       $lista = array('#346C99','#346C99','#5886AB','#6993B4','#779DBB','#779DBB','#8FAEC7','#9DB8CE','#ADC3D6','#B9CCDC','#C5D5E2','#D1DEE8','#D1DEE8','#DEE7EF','#E9EFF4','#F3F7F9','#FFFFFF');
       $totalColores = count($lista)-1;

       $color = rand(0, $totalColores);

       return $lista[$this->niveles];

    }

}