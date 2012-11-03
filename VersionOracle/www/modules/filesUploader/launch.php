<?php
/**
 * CALSE SE ENCARGA DE SUBIR ARCHIVOS AL SERVIDOR
 * @autor Jorge Tenorio
 * @since 29/03/2010
 */


class FilesUploader{


        public function __construct(){
            
        }

        private function return_bytes($val) {
                $val = trim($val);
                $last = strtolower($val[strlen($val)-1]);
                switch($last) {
                    // The 'G' modifier is available since PHP 5.1.0
                    case 'g':
                        $val *= 1024;
                    case 'm':
                        $val *= 1024;
                    case 'k':
                        $val *= 1024;
                }

                return $val;
        }

        public function uploadFile($filename, $FILE_STORAGE_BASE,$name){

                                        /////////////////////UPLOAD
                                        $uploadDir = $FILE_STORAGE_BASE;
                                        //$maxFileSize = 9048576; //20mb
                                        $maxFileSize = $this->return_bytes(ini_get('post_max_size'));

                                        ///////////////////////////////////////


                ////////////////////////////
                //datos del arhivo

                 $nombre_archivo = $filename['name'];
                 $nombre_archivo = str_replace(' ', '_', $nombre_archivo);

                 $tipo_archivo = $filename['type'];
                 $tamano_archivo = $filename['size'];


                $fileInfo = explode('.', $filename['name']);
                $extension = '.'.$fileInfo['1'];
                //print_r($fileInfo);
                //die();
                //echo $uploadDir;

                $fileName = $uploadDir.$name.'_'.$nombre_archivo;

                $nombre_archivo = str_replace($extension, '', $fileName);

                $nombre_nuevo = $nombre_archivo;




                 //ver si hay q cambiar el nombre del archivo (..., xxx_23.doc, xxx_24.doc, ...)



                        if (file_exists($fileName)){
                                $cuenta = 1;
                                do {

                                        $nombre_nuevo = $nombre_archivo.'_'.$cuenta;
                                        $cuenta++;
                                }while (file_exists($nombre_nuevo.$extension));
                        }

                         $fileName = $nombre_nuevo.$extension;


                        //compruebo si las características del archivo son las que deseo
                        if ($tamano_archivo > $maxFileSize) {

                                return 'TO_BIG';



                        }else{
                            if (move_uploaded_file($filename['tmp_name'], $fileName)){
                                $fileName;

                            }else{
                              return 'ERROR';
                            }
                                //die($mensaje.'si entro');
                        }
                        //echo  $mensaje;
                        return basename($fileName);
        }

}
