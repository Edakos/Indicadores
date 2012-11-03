<?php
/*
 * Controlador del modulo de cargos
 * Jorge Tenorio
 * 22-02-2011
 * MUSHOQ
 */
@session_start();
require_once($_SESSION['path'].'/conf/includes.php');


class Despliegue{

    private $ado;
    private $deepArray;
    private $niveles;
   

    public function  __construct() {
        $this->ado = new Ado();
        $this->deepArray = array();
        $this->niveles = 0;
        
    }

    public function getDivisiones(){
        $sql = "select * from DESPLIEGUE";
        $result = $this->ado->query($sql);

        return $result;
    }

    public function addDivision($padre=0,$data){
        
        $sql = "INSERT INTO DESPLIEGUE(NOMBRE,DESCRIPCION,IDPADRE,CODIGO) VALUES(
                '{$data['nombre']}','{$data['descripcion']}',$padre,'{$data['codigo']}')";

        $result = $this->ado->query($sql);
        if(!isset($result['Error'])){     
            echo '<p>Divisi&oacute;n creada satisfactoriamente.</p>';
        }else{
            echo '<p class="error">No se ha podido crear la divisi&oacute;n.</p>';
        }
    }

    public function getDespliegueTree($id=0){

        //obetener todos los hijos de padre del id

        $sql = "SELECT * FROM DESPLIEGUE  WHERE IDPADRE=$id ";
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

            $deep = $this->profundidad($depData['IDDESPLIEGUE']);
            for($i=0;$i<$deep;$i++){}
                

           

            if(!$this->tieneHijos($depData['IDDESPLIEGUE']))
                $eliminar= '<a style="float:left;" href="#" onClick="sendPage(\'null\',\'modules/despliegue/addDivision.php?padre='.$depData['IDDESPLIEGUE'].'\',\'mod_content\');"><img src="'.$_SESSION['url'].'/images/agregar.gif"></a><a style="float:left;" href="#" onClick="if(confirm(\'Desea eliminar esta division?\')) {sendPage(\'null\',\'modules/despliegue/listDivision.php?delete='.$depData['IDDESPLIEGUE'].'\',\'mod_content\');}"><img src="'.$_SESSION['url'].'/images/icontrash.gif"></a>';
            else
                $eliminar ='<a style="float:left;" href="#" onClick="sendPage(\'null\',\'modules/despliegue/addDivision.php?padre='.$depData['IDDESPLIEGUE'].'\',\'mod_content\');"><img src="'.$_SESSION['url'].'/images/agregar.gif"></a>';

            echo '<div class="treeTab" style="width:'.($i*33).'px;">'.$eliminar.'<img src="'.$_SESSION['url'].'/images/fizq.gif"></div>';

            echo '<a href="#" onClick="sendPage(\'null\',\'modules/despliegue/addDivision.php?edit='.$depData['IDDESPLIEGUE'].'\',\'mod_content\');" style="border:#4F8DD2 1px solid; padding:4px; margin-top:2px; background-color:'.$color.'; color:'.$colorLetra.';">'.$depData['NOMBRE'].'</a> </p>';

            
            $this->getDespliegueTree($depData['IDDESPLIEGUE']);
        }
        
    }


    private function profundidad($id=0){
        //obtener la profundidad de un padre

        $deep = 0;

        while($id > 0){
            $sql = "SELECT IDPADRE FROM DESPLIEGUE WHERE IDDESPLIEGUE=$id";
            $newId = $this->ado->query($sql);

            $id = $newId[0]['IDPADRE'];
            $deep++;
        }

        return $deep;
    }

    public function getDivisionInfo($id){
        $sql = "SELECT * FROM SEGMENTACION WHERE NIVEL=$id";
        $result = $this->ado->query($sql);

        return $result;
    }

    public function updateDvision($id,$data){
        $sql = "UPDATE DESPLIEGUE SET
                    NOMBRE = '{$data['nombre']}',
                    DESCRIPCION = '{$data['descripcion']}',                    
                    CODIGO = {$data['codigo']}
                    
                WHERE
                    IDDESPLIEGUE = $id
                ";

        $result =  $this->ado->query($sql);
        if(!isset($result['Error'])){
            echo '<p>Divisi&oacute;n actualizada satisfactoriamente.</p>';
        }else{
            echo '<p class="error">No se ha podido actualizar la divisi&oacute;n.</p>';
        }
    }

    private function tieneHijos($id){

        if($id==1)
            return true;

        $sql = "SELECT * FROM DESPLIEGUE WHERE IDPADRE = $id";
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

    public function deleteDivision($id){
       
        //borrar el nodo hijo
        $sql = "DELETE FROM DESPLIEGUE WHERE IDDESPLIEGUE = $id";
        $result =  $this->ado->query($sql);
        
        if(!isset($result['Error'])){          
            echo '<p>Divisi&oacute;n eliminada satisfactoriamente.</p>';
        }else{
            echo '<p class="error">No se ha podido eliminar la divisi&oacute;n.</p>';
        }
    }


    public function numberOfLevels($startLevel = 0,$deep=0,$newlevel=true){

        $sql = "SELECT IDDESPLIEGUE FROM DESPLIEGUE WHERE IDPADRE=$startLevel";
        $result = $this->ado->query($sql);

        foreach($result as $item){
            if($newlevel){
                $deep++;
                array_push($this->deepArray, $deep);
                $newlevel = false;
            }

            $this->numberOfLevels($item['IDDESPLIEGUE'], $deep,true);
        }

         //buscar el maximo valor en el array
         $maximo = 0;
         foreach($this->deepArray as $valor){
             if($valor > $maximo){
                $maximo = $valor;
             }
         }

         return $maximo;
    }


    public function processLevels($data){

        //procesar el post
        foreach($data as $name => $value){
            $partes = explode('nivel', $name);
            $nivel = $partes[1];
            if(strlen($value)){

                $sql = "INSERT INTO SEGMENTACION VALUES($nivel,'$value')";
                $result = $this->ado->query($sql);

                if(isset($result['Error'])){
                        $sql = "UPDATE SEGMENTACION SET NOMBRE = '$value' WHERE NIVEL = $nivel";
                        $result = $this->ado->query($sql);
                        if(!isset($result['Error'])){
                            echo '<p>Segmento '.$nivel.' actualizado correctamente.</p>';
                        }else{
                            echo '<p class="error">Error procesando el segmento '.$nivel.'.</p>';
                        }
                }else{
                    echo '<p>Segmento '.$nivel.' procesado correctamente.</p>';
                }
            }else{
                echo '<p class="error">Segmento '.$nivel.' sin nombre, no se ha modificado.</p>';
            }
        }

    }


    public function getSegmentacion(){
        $sql = "SELECT * FROM SEGMENTACION";
        $result = $this->ado->query($sql);

        $actual = $this->numberOfLevels();

        if(count($result) > $actual){
            $sql = "DELETE FROM SEGMENTACION WHERE NIVEL = (SELECT MAX(NIVEL) FROM SEGMENTACION)";
            $result = $this->ado->query($sql);
            echo "<script>alert('Se ha eliminado 1 nivel de segmentacion.');</script>";

        }
            

        return $result;
    }


    public function getNodesByLevel($level){

        //obtener los hijos del primer nivel
        
        $currentLevel= 0;
        $padres = "0";

        while ($currentLevel < $level){
            $sql = "SELECT IDDESPLIEGUE FROM DESPLIEGUE WHERE IDPADRE IN ($padres)";
            $result = $this->ado->query($sql);

            //reiniciar los padres

            $padres = "";

            foreach($result as $item){
                $padres .= $item['IDDESPLIEGUE'].',';
            }

            //eliminar la ultima coma
            $padres = substr($padres,0,  strlen($padres)-1);
            ++$currentLevel;
        }

        return $padres;

    }


    private function color(){
       $lista = array('#346C99','#346C99','#5886AB','#6993B4','#779DBB','#779DBB','#8FAEC7','#9DB8CE','#ADC3D6','#B9CCDC','#C5D5E2','#D1DEE8','#D1DEE8','#DEE7EF','#E9EFF4','#F3F7F9','#FFFFFF');
       $totalColores = count($lista)-1;

       $color = rand(0, $totalColores);

       return $lista[$this->niveles];

    }
}