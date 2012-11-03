<?php
/**
 * Clase para grabar logs
 * @param String    $archivo            Archivo a manipular
 * @param String    $directorio         Directorio de destino del archivo 
 * @param String    $tipoArchivo        Extensi�n que identifica el nombre del archivo
 * @author Absal�n Tixilema
 * @since 24-03-2010
 * @version PHP 5
 */
class Logs {	
	private $archivo;
	private $directorio;	
    private $template;
   
	/**
	 * Constructor de la clase Archivo
	 *
	 * @param string $archivo
	 * @param string $dir	 
	 * @param array $nombre
	 * @param string $template
	 */
	public function __construct()
	{
		$this->archivo			= date("Y-m-d")."_logs.txt";
		$this->directorio		= $_SESSION['path']."/files_logs/";
		$this->template 		= "logs";
	}
	/**
	 * Escribir en el archivo (txt)
	 * @param string $mode
	 * @param string $input
	 */
	public function openFile($mode, $input) 
	{
		$pathFile = $this->directorio.$this->archivo;
	    if ($mode == "READ") {
	        if (file_exists($pathFile)) {
	            $handle = fopen($pathFile, "r");
	            $output = fread($handle, filesize($pathFile));
	            return $output; // output file text
	        } else {
	            return false; // failed.
	        }
	    } elseif ($mode == "WRITE") {
	        $handle = fopen($pathFile, "a+");
	        if (!fwrite($handle, $input)) {
	            return false; // failed.
	        } else {
	            return true; //success.
	        }
	    } elseif ($mode == "READ/WRITE") {       
	        if (file_exists($pathFile) && isset($input)) {
	            $handle = fopen($pathFile, "r+");
	            $read = fread($handle, filesize($pathFile));
	            $data = $read.$input;
	            if (!fwrite($handle, $data)) {
	                return false; // failed.
	            } else {
	                return true; // success.
	            }
	        } else {
	            return false; // failed.
	        }
	    } else {
	        return false; // failed.
	    }
	    fclose($handle);
	}
	/**
	 * Borra el archivo del servidor
	 *
	 * @return boolean
	 */
	public function delFile(){
		if(file_exists($this->directorio.$this->archivo)){
			unlink($this->directorio.$this->archivo);
			return true;
		}else{
			return false;
		}		
	}
}

?>
