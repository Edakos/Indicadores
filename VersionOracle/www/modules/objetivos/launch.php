<?php
/*
 * Controlador del modulo de objetivos
 * Jorge Tenorio
 * 16-02-2011
 * MUSHOQ
 */
@session_start();
require_once($_SESSION['path'].'/conf/includes.php');

class Objetivo{

    private $ado;
    private $niveles;

    public function  __construct() {
        $this->ado = new Ado();
        $this->niveles=0;
    }


    public function getObjetivos($soloPadres= false){
        if($soloPadres)
            $sql = "SELECT * FROM OBJETIVO WHERE IDOBJETIVO > 0 AND OBJETIVO_PADRE=0";
         else
            $sql = "SELECT * FROM OBJETIVO WHERE IDOBJETIVO > 0";
         
        $result = $this->ado->query($sql);
        return $result;
    }

    public function getObjetivo($id){
        $sql = "SELECT * FROM OBJETIVO  WHERE IDOBJETIVO = $id";
        $result = $this->ado->query($sql);
        return $result;
    }

    public function actualizaObjetivo($id,$data){
        $sql = "UPDATE OBJETIVO SET
                CODIGO = '{$data['codigo']}',
                NOMBRE='{$data['nombre']}',
                OBJETIVO_PADRE={$data['padre']},
                SIGNO='{$data['signo']}',
                OPERACION='{$data['operacion']}'
                WHERE IDOBJETIVO = $id";

           $result = $this->ado->query($sql);
           if(!isset($result['Error'])){
                echo '<p>Objetivo actualizado satisfactoriamente.</p>';
           }else{
               echo '<p class="error">No se ha podido actualizar el objetivo</p>';
           }
        

    }

    private function checkCode($code){
        $sql = "SELECT * FROM OBJETIVO WHERE CODIGO='$code'";
        $result = $this->ado->query($sql);

        if(!isset($result['Error'])){
            if(count($result))
                return true;
            else
                return false;
        }else{
            return true;
        }
    }

    public function addObjetivo($data){
        //verificar q el codigo aun no exista
        if(!$this->checkCode($data['codigo'])){
            $sql = "INSERT INTO OBJETIVO(CODIGO,NOMBRE,OBJETIVO_PADRE,SIGNO,OPERACION) VALUES(
                    '{$data['codigo']}','{$data['nombre']}',{$data['padre']},'{$data['signo']}','{$data['operacion']}')";

            $result = $this->ado->query($sql);
           if(!isset($result['Error'])){
                echo '<p>Objetivo agregado satisfactoriamente.</p>';
           }else{
               echo '<p class="error">No se ha podido agregar el objetivo</p>';
           }
            

        }else{
            echo '<p class="error">El c&oacute;digo ya ha sido asignado a otro objetivo.</p>';
        }
        
    }

    public function deleteObjetivo($id){
        //borrar el nodo hijo
        $sql = "DELETE FROM OBJETIVO WHERE IDOBJETIVO = $id";
        $result =  $this->ado->query($sql);

        if(!isset($result['Error'])){
            echo '<p>Objetivo eliminado satisfactoriamente.</p>';
        }else{
            echo '<p class="error">No se ha podido eliminar el objetivo.</p>';
        }
    }


    public function getObjetivosTree($id=0){

        //obetener todos los hijos de padre del id

        $sql = "SELECT * FROM OBJETIVO WHERE OBJETIVO_PADRE=$id";
        $result = $this->ado->query($sql);
         $color = $this->color();
         
        foreach($result as $depData){
            
            if($this->niveles > 13){
                $colorLetra = "blue";
            }else{
                $colorLetra = "white";
            }
            
            echo '<p class="pTree">';
           
            $deep = $this->profundidad($depData['IDOBJETIVO']);
             for($i=0;$i<$deep;$i++){}

            

            if(!$this->tieneHijos($depData['IDOBJETIVO']))
                $eliminar= '<a style="float:left;" href="#" onClick="if(confirm(\'Se desvincularán los indicadores vinculados a este objetivo, desea continuar?\')) {sendPage(\'null\',\'modules/objetivos/arbol.php?delete='.$depData['IDOBJETIVO'].'\',\'mod_content\');}"><img src="'.$_SESSION['url'].'/images/icontrash.gif"></a>';
            else
                $eliminar ='';

            echo '<div class="treeTab" style="width:'.($i*33).'px;">'.$eliminar.'<img src="'.$_SESSION['url'].'/images/fizq.gif"></div>';

            echo '<a href="#" onClick="sendPage(\'null\',\'modules/objetivos/addObjetivo.php?edit='.$depData['IDOBJETIVO'].'\',\'mod_content\');" style="border:#4F8DD2 1px solid; padding:4px; margin-top:2px; background-color:'.$color.'; color:'.$colorLetra.';">'.$depData['NOMBRE'].' ('.$depData['CODIGO'].')</a> </p>';

            $this->niveles++;
            $this->getObjetivosTree($depData['IDOBJETIVO']);
        }

    }


    private function profundidad($id=0){
        //obtener la profundidad de un padre

        $deep = 0;

        while($id > 0){
            $sql = "SELECT OBJETIVO_PADRE FROM OBJETIVO WHERE IDOBJETIVO=$id";
            $newId = $this->ado->query($sql);

            $id = $newId[0]['OBJETIVO_PADRE'];
            $deep++;
        }

        return $deep;
    }

    private function tieneHijos($id){
        $sql = "SELECT * FROM OBJETIVO WHERE OBJETIVO_PADRE = $id";
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



    public function vincular($data){
        $dep = $data['departamento'];
        $obj = $data['objetivo'];

        //verificar que no existe el vinculo
        if(!$this->existeVinculo($dep, $obj)){
            //insertar el vinculo
            $sql = "INSERT INTO DEPARTAMENTO_OBJETIVO(IDDEPARTAMENTO,IDOBJETIVO) VALUES(
                    $dep,$obj)";
            $result = $this->ado->query($sql);
            if(!isset($result['Error'])){
                    echo '<p>V&iacute;nculo creado satisfactoriamente.</p>';
                }else{
                    echo '<p class="error">No se ha podido crear el v&iacute;nculo.</p>';
                }

            }else{
                 echo '<p class="error">El v&iacute;nculo entre el departamento y objetivo selecciondao ya existe.</p>';
            }
    }


    private function existeVinculo($dep,$obj){
        $sql = "SELECT * FROM DEPARTAMENTO_OBJETIVO WHERE IDDEPARTAMENTO=$dep AND IDOBJETIVO=$obj";
        $result = $this->ado->query($sql);

        if(!isset($result['Error'])){
            if(count($result))
                return true;
            else
                return false;
        }else{
            return true;
        }
    }

    public function listaVinculados(){        
        $sql = "SELECT IDDEPARTAMENTO,IDOBJETIVO,ID from DEPARTAMENTO_OBJETIVO ORDER BY IDDEPARTAMENTO";
        $result = $this->ado->query($sql);

        $lastDep = null;
        foreach($result as $item){
            //camnbio de departamento

            if($item['IDDEPARTAMENTO'] != $lastDep){
                //cambio de departamento
                echo '<p>'.$this->getDepName($item['IDDEPARTAMENTO']).'</p>';
                $lastDep = $item['IDDEPARTAMENTO'];

                ///////////////////////////////
                echo '<p>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;|__';
                echo $this->getObjName($item['IDOBJETIVO']);
                echo '<a href="#" onClick="if(confirm(\'Desea elminar este vínculo?\')) {sendPage(\'null\',\'modules/objetivos/vincularObjetivo.php?delete='.$item['ID'].'\',\'mod_content\');}"><img src="'.$_SESSION['url'].'/images/activo0.gif"></a>';
                echo '</p>';
            }else{
                //iprimir los objetivos                
                echo '<p>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;|__';
                echo $this->getObjName($item['IDOBJETIVO']);
                echo '<a href="#" onClick="if(confirm(\'Desea elminar este vínculo?\')) {sendPage(\'null\',\'modules/objetivos/vincularObjetivo.php?delete='.$item['ID'].'\',\'mod_content\');}"><img src="'.$_SESSION['url'].'/images/activo0.gif"></a>';
                echo '</p>';
            }

        }

    }


    private function getDepName($id){
        $sql = "SELECT NOMBRE FROM DEPARTAMENTO WHERE IDDEPARTAMENTO = $id";
        $result = $this->ado->query($sql);

        return $result[0]['NOMBRE'];
    }

    private function getObjName($id){
        $sql = "SELECT NOMBRE FROM OBJETIVO WHERE IDOBJETIVO = $id";
        $result = $this->ado->query($sql);

        return $result[0]['NOMBRE'];
    }

    
    public function deleteVinculo($id){
        $sql = "DELETE FROM DEPARTAMENTO_OBJETIVO WHERE ID=$id";
        $result = $this->ado->query($sql);
            if(!isset($result['Error'])){
                echo '<p>V&iacute;nculo eliminado satisfactoriamente.</p>';
            }else{
                echo '<p class="error">No se ha podido eliminar el v&iacute;nculo.</p>';
            }
    }

     private function color(){
       $lista = array('#346C99','#346C99','#5886AB','#6993B4','#779DBB','#779DBB','#8FAEC7','#9DB8CE','#ADC3D6','#B9CCDC','#C5D5E2','#D1DEE8','#D1DEE8','#DEE7EF','#E9EFF4','#F3F7F9','#FFFFFF');
       $totalColores = count($lista)-1;

       $color = rand(0, $totalColores);

       return $lista[$this->niveles];

    }
}