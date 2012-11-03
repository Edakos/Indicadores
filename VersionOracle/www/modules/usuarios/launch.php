<?php
/* 
 * Controlador del modulo de usuarios
 * Jorge Tenorio
 * 11-02-2011
 * MUSHOQ
 */
@session_start();
require_once($_SESSION['path'].'/conf/includes.php');


class Usuarios{

   private $ado;
   
   
   public function  __construct() {
        $this->ado = new Ado();
    }


    //obtiene el cuadro completo de usuarios del sistema
    public function getUsersList(){
        $sql = "select IDUSUARIO,NOMBRE,APELLIDO,EMAIL,ESTADO,TIPO from USUARIO where ELIMINADO=0 order by APELLIDO";
        $data = $this->ado->query($sql);

        $html = '<table>';
        $html.= '<tr class="tableTitle"><td>Nombre</td><td>Apellido</td><td>e-mail</td><td>Estado</td><td>Tipo</td><td></td><td></td></tr>';
        $i = 0;
        foreach($data as $row){

            if($i%2)
                $clase = "trImpar";
            else
                $clase = "trPar";
            $i++;
            
            $html .= '<tr class="'.$clase.'">';
            $html .= "<td>{$row['NOMBRE']}</td>";
            $html .= "<td>{$row['APELLIDO']}</td>";
            $html .= "<td>{$row['EMAIL']}</td>";

            if($row['ESTADO'] == '1')
                $html .= "<td>Activo</td>";
            else
                $html .= "<td>Inactivo</td>";

            if($row['TIPO'] == 'A')
                $html .= "<td>ADMINISTRADOR</td>";
            else
                $html .= "<td>Facilitador</td>";

            $html .= "<td><a  onclick=\"sendPage('null','modules/usuarios/formUser.php?edit={$row['IDUSUARIO']}','main_content');\"><img src=\"{$_SESSION['url']}/images/edit.gif\"></a></td>";
            $html .= "<td><a onclick=\"if(confirm('Desea eliminar a este usuario?')){ sendPage('null','modules/usuarios/userList.php?delete={$row['IDUSUARIO']}','main_content');}\"><img src=\"{$_SESSION['url']}/images/icontrash.gif\"></a></td>";
            $html .= '</tr>';
        }
        $html.= '</table>';

        return $html;

    }

    public function addUser($formulario){
        if(!$this->validaExiste($formulario['email'])){
             $logitud = 8;
             $psswd = substr(md5(microtime()), 1, $logitud);
             $psswd2 = sha1($psswd);

             $sql = "insert into USUARIO(NOMBRE,APELLIDO,EMAIL,PWD,PWD_INIT,ESTADO,TIPO,ELIMINADO)
                            VALUES('{$formulario['nombre']}','{$formulario['apellido']}','{$formulario['email']}','$psswd2','1','{$formulario['estado']}','{$formulario['tipo']}',0)";

             $result = $this->ado->query($sql);

             $tipoUser = "ADMINISTRADOR";
             if($formulario['tipo']=='F')
                  $tipoUser = "FACILITADOR";

             if(!isset($result['Error'])){

                 $html='<p><b>BIENVENIDO AL SISTEMA DE ADMINISTRACI&Oacute;N DE INDICADORES DE LA CNT</b></p>
                        <p>Usted ha sido registrado como usuario '.$tipoUser.'</p>
                        <p>Su user-name es: '.$formulario['email'].'</p>';

                 $html.='<p><b>Contrase&ntilde;a temporal: '.$psswd.'</b></p>';

                 //validar si el user existe

                 $mail = new Mailer();
                 $mail->sendMail($formulario['email'],'Nuevo usuario del sistema de administración de indicadores.',$html);

                 echo '<p>Usuario creado satisfactoriamente.</p>';
             }else{
                 echo '<p class="error">Error creando el usuario.</p>';
             }

        }else{
            echo '<p class="error">El e-mail ingresado ya existe.</p>';
            
        }        
    }


    private function validaExiste($email){

        $sql = "select * from USUARIO where EMAIL='$email' and ELIMINADO=0";

        $result = $this->ado->query($sql);

        if(count($result))
            return true;
        else
            return false;
        
    }


    public function getUser($id){
        $sql = "select * from USUARIO where IDUSUARIO=$id";
        $result = $this->ado->query($sql);
        return $result;
    }
    

    public function editUser($id,$formulario){
        $sql = "update USUARIO set
                NOMBRE = '{$formulario['nombre']}',
                APELLIDO = '{$formulario['apellido']}',
                EMAIL = '{$formulario['email']}',
                TIPO = '{$formulario['tipo']}',
                ESTADO = '{$formulario['estado']}'
                where
                IDUSUARIO = $id
            ";
                
        $result = $this->ado->query($sql);
        if(!isset($result['Error'])){
            echo '<p>Usuario actualizado satisfactoriamente.</p>';
        }else{
            echo '<p class="error">Error actualizando el usuario.</p>';
        }
    }

    public function resetPwd($id){

            $user = $this->getUser($id);


             $logitud = 8;
             $psswd = substr(md5(microtime()), 1, $logitud);
            
             $psswd2 = sha1($psswd);
             $sql = "update USUARIO set PWD='{$psswd2}', PWD_INIT='1' where EMAIL='{$user[0]['EMAIL']}'";
             $result = $this->ado->query($sql);

             

             $html='<p><b>CAMBIO DE CONTRASE&Ntilde;A</b></p>
                    <p>Nos ha solicitado recordar su contrase&ntilde;a.</p>
                    <p>Por motivos de seguridad hemos cambiado la misma.</p>';

             $html.='<p><b>Contrase&ntilde;a temporal: '.$psswd.'</b></p>';


             $mail = new Mailer();
             $mail->sendMail($user[0]['EMAIL'],'Solicitud de cambio de contraseña.',$html);

             echo '<p>Clave reiniciada satisfactoriamente, la nueva clave se ha enviado a: '.$user[0]['EMAIL'].'</p>';
    }

    public function delteUser($id){
        $sql = "update USUARIO set ELIMINADO=1, ESTADO='0' where IDUSUARIO=$id";
        $result = $this->ado->query($sql);

        if(!isset($result['Error'])){
            echo '<p class="error">El usuario ha sido eliminado.</p>';
        }else{
            echo '<p class="error">El usuario no puede ser eliminado en este momento.</p>';
        }
        
    }

    public function getFacilitadores(){
        $sql = "SELECT * FROM USUARIO WHERE TIPO='F' AND ELIMINADO = 0";
        $result = $this->ado->query($sql);

        return $result;
    }

     public function getAllUsers(){
        $sql = "SELECT * FROM USUARIO WHERE ELIMINADO = 0";
        $result = $this->ado->query($sql);

        return $result;
    }
}

