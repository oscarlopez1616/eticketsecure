<?php

class Application_Model_DbTable_Usuario extends Zend_Db_Table_Abstract
{

    protected $_name = 'usuario';
     
    
    public function deleteUsuario($id)
    {
        $sql = "delete from usuario where id =".$id;
        $db = $this->getAdapter();
      
        $res = $db->query($sql);
        return $res->rowCount();
    } 
    
    public function addUsuario($id_empresa,$email,$id_role,$password,$fecha_alta,$fecha_ultimo_login,$idioma_predefinido,$flag_opt_in,$activo,$representacion_xml)
    {
        $data = array(
                'id_empresa' => $id_empresa,
                'email'=>$email,
                'id_role'=>$id_role,
                'password'=>$password,
                'fecha_alta'=>$fecha_alta,
                'fecha_ultimo_login'=>$fecha_ultimo_login,
                'idioma_predefinido'=>$idioma_predefinido,
                'flag_opt_in'=>$flag_opt_in,
                'activo'=>$activo,
                'representacion_xml'=>$representacion_xml
                );
        $c=$this->insert($data);
        return $c;
    }

    
    public function setUsuarioById($id,$email,$id_role,$password,$fecha_alta,$fecha_ultimo_login,$idioma_predefinido,$flag_opt_in,$activo,$representacion_xml)
    {       
        $data = array(
                'email'=>$email,
                'id_role'=>$id_role,
                'password'=>$password,
                'fecha_alta'=>$fecha_alta,
                'fecha_ultimo_login'=>$fecha_ultimo_login,
                'idioma_predefinido'=>$idioma_predefinido,
                'flag_opt_in'=>$flag_opt_in,
                'activo'=>$activo,
                'representacion_xml'=>$representacion_xml
                );
        $res = $this->update($data, 'id = ' . $id);
        return $res;
    }

    public function getUsuarioById($id)
    {
        $sql = "select * from usuario where id=".$id;
        $db = $this->getAdapter();
        $res = $db->query($sql);
        $usuario = $res->fetchAll();
        return $usuario[0];
    }

    public function getUsuarioByEmail($email)
    {
        $sql = "select * from usuario where email='".$email."'";
        $db = $this->getAdapter();
        $res = $db->query($sql);
        $usuario = $res->fetchAll();
        if (!isset($usuario[0]))  throw new Exception('No existe usuario identificado con el email: '.$email.' o Ha habido un Error de InterfaceUsuario');
        return $usuario[0];
    }

    public function getRoleIdByIdUsuario($id_usuario)
    {
        $sql = "select b.name as role_id from usuario as a 
                join acl_roles as b 
                on a.id_role=b.id
                where a.id=".$id_usuario;
        $db = $this->getAdapter();
        $res = $db->query($sql);
        $role = $res->fetch();
        return $role["role_id"];
    }
    
    

    
    public function getIdByEmail($email)
    {
        $sql = "select id from usuario where email='".$email."'";
        $db = $this->getAdapter();
        $res = $db->query($sql);
        $id = $res->fetchAll();
        if(!isset($id[0])){
          return $id = null;  
        }
        return $id[0];
    } 


}

