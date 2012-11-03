<?php
/**
 * CLASE QUE INICIA EL MODULO DE ADODB
 * @autor Jorge Tenorio
 * @since 24/03/2010
 */

 class Ado{

    private $server;
    private $user;
    private $pwd;
    private $dbname;
    private $connSQL;
    private $engine;

    public function __construct(){
        $this->loadCredentials();
        //$this->connect();
    }

    private function loadCredentials(){
        //cargar el archivo /conf/dataBase.conf
       $data = file($_SESSION["path"].'/conf/dataBase.conf');
       $campo =0;
       for($i=0;$i<count($data);$i++){
           if(substr($data[$i],0,1)!='#'){
               $data[$i] = trim($data[$i]);
                switch($campo){
                    case 0:
                        $this->server = $data[$i];
                        break;
                    case 1:
                        $this->user = $data[$i];
                        break;
                    case 2:
                        $this->pwd = $data[$i];
                        break;
                    case 3:
                        $this->dbname = $data[$i];
                        break;
                    case 4:
                        $this->engine = $data[$i];
                        break;
                }
                $campo++;
           }
       }
    }


       

    public function query($sql, $showError = TRUE){
		
            $sql = (trim($sql));
            
            $this->connSQL = oci_connect($this->user,  $this->pwd, $this->server.'/'.$this->dbname,'AL32UTF8');
            if (!$this->connSQL) {
                $e = oci_error();
                die('<p class="error">Error de al conectar a la base de datos.</p>');
            }    
            
            $resultSet = oci_parse($this->connSQL, $sql);
            oci_execute($resultSet);
            
            $retornar = array();
            $i=0;
            while ($row = oci_fetch_array($resultSet, OCI_ASSOC+OCI_RETURN_NULLS)) {
                foreach ($row as $col => $item) {                    
                $retornar[$i][$col] = $item;                
                //echo $item;
                }
                $i++;
            }
            //oci_close($this->connSQL);
            return $retornar;

    }


 
 }

?>
