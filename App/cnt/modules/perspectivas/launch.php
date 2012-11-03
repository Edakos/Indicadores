<?php
/*
 * Controlador del modulo perspectivas
 * Jorge Tenorio
 * 15-02-2011
 * MUSHOQ
 */
@session_start();
require_once($_SESSION['path'].'/conf/includes.php');

class Perspectiva{

    private $ado;
    private $niveles;

    public function  __construct() {
        $this->ado = new Ado();
        $this->niveles = 0;
    }

    public function getDepartamentos(){
        $sql = "select * from DEPARTAMENTO where ESTADO=1 and IDDEPARTAMENTO > 0";
        $result = $this->ado->query($sql);

        return $result;
    }

    public function addDepartamento($data){
        $sql = "INSERT INTO DEPARTAMENTO(NOMBRE,DESCRIPCION,IDPADRE,ESTADO) VALUES(
                '{$data['nombre']}','{$data['descripcion']}',{$data['depende']},{$data['estado']})";

        $result = $this->ado->query($sql);
        if(!isset($result['Error'])){
            echo '<p>Departamento creado satisfactoriamente.</p>';
        }else{
            echo '<p class="error">No se ha podido crear el departamento.</p>';
        }
    }

    public function getDepTree($id=0){

        //obetener todos los hijos de padre del id

        $sql = "SELECT * FROM DEPARTAMENTO WHERE IDPADRE=$id";
        $result = $this->ado->query($sql);
        $this->niveles ++;

        foreach($result as $depData){

            $color = $this->color();
             if($this->niveles > 13){
                $colorLetra = "blue";
            }else{
                $colorLetra = "white";
            }
            
            echo '<p class="pTree">';
        
            if(!$this->tieneHijos($depData['IDDEPARTAMENTO']))
                $eliminar= '<a style="float:left;" href="#" onClick="if(confirm(\'Se desvincularán los objetivos vinculados a este departamento, desea continuar?\')) {sendPage(\'null\',\'modules/perspectivas/listDepartamentos.php?delete='.$depData['IDDEPARTAMENTO'].'\',\'mod_content\');}"><img src="'.$_SESSION['url'].'/images/icontrash.gif"></a>';
            else
                $eliminar ='';

            

            $deep = $this->profundidad($depData['IDDEPARTAMENTO']);
            for($i=0;$i<$deep;$i++){}

            echo '<div class="treeTab" style="width:'.($i*33).'px;">'.$eliminar.'<img src="'.$_SESSION['url'].'/images/fizq.gif"></div>';

            echo '<a href="#" onClick="sendPage(\'null\',\'modules/perspectivas/addDepartamento.php?edit='.$depData['IDDEPARTAMENTO'].'\',\'mod_content\');" style="border:#4F8DD2 1px solid; padding:4px; margin-top:2px; background-color:'.$color.'; color:'.$colorLetra.';">'.$depData['NOMBRE'].'</a> </p>';

            
            $this->getDepTree($depData['IDDEPARTAMENTO']);
        }
        
    }


    private function profundidad($id=0){
        //obtener la profundidad de un padre

        $deep = 0;

        while($id > 0){
            $sql = "SELECT IDPADRE FROM DEPARTAMENTO WHERE ESTADO=1 AND IDDEPARTAMENTO=$id";
            $newId = $this->ado->query($sql);

            $id = $newId[0]['IDPADRE'];
            $deep++;
        }

        return $deep;
    }

    public function getDepInfo($id){
        $sql = "SELECT * FROM DEPARTAMENTO WHERE IDDEPARTAMENTO=$id";
        $result = $this->ado->query($sql);

        return $result;
    }

    public function updateDep($id,$data){
        $sql = "UPDATE DEPARTAMENTO SET
                    NOMBRE = '{$data['nombre']}',
                    DESCRIPCION = '{$data['descripcion']}',
                    IDPADRE = {$data['depende']},
                    ESTADO = {$data['estado']}
                WHERE
                    IDDEPARTAMENTO = $id
                ";

        $result =  $this->ado->query($sql);
        if(!isset($result['Error'])){
            echo '<p>Departamento actualizado satisfactoriamente.</p>';
        }else{
            echo '<p class="error">No se ha podido actualizar el departamento.</p>';
        }
    }

    private function tieneHijos($id){
        $sql = "SELECT * FROM DEPARTAMENTO WHERE IDPADRE = $id";
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

    public function deleteDep($id){
        //borrar el nodo hijo
        $sql = "DELETE FROM DEPARTAMENTO WHERE IDDEPARTAMENTO = $id";
        $result =  $this->ado->query($sql);
        
        if(!isset($result['Error'])){
            echo '<p>Departamento eliminado satisfactoriamente.</p>';
        }else{
            echo '<p class="error">No se ha podido eliminar el departamento.</p>';
        }
    }

    private function color(){
       $lista = array('#346C99','#346C99','#5886AB','#6993B4','#779DBB','#779DBB','#8FAEC7','#9DB8CE','#ADC3D6','#B9CCDC','#C5D5E2','#D1DEE8','#D1DEE8','#DEE7EF','#E9EFF4','#F3F7F9','#FFFFFF');
       $totalColores = count($lista)-1;

       $color = rand(0, $totalColores);

        return $lista[$this->niveles];

    }
}