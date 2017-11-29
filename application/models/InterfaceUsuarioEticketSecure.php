<?php

class Application_Model_InterfaceUsuarioEticketSecure extends Application_Model_InterfaceEticketSecure
{
    private $_controller;
    private $_rest_ful_service;
    
    public function __construct(){
        parent::__construct();
        $this->_controller= "/usuario/index/index.php";
        $this->_rest_ful_service = "ServiceUsuario";
    }
    
    
    public function getRoleIdByIdUsuario($id_usuario){
        if($this->_flag_web_service){
            $url ="&id_usuario=".$id_usuario; 
            $webservice=$this->getUrlWebService($this->_controller,"&method=getRoleIdByIdUsuario".$url);
            $data=$this->restFul($webservice);
            if($data->getRoleIdByIdUsuario->status == "failed") throw new Exception('InterfaceUsuarioException');
            $data=Eticketsecure_Utils_XML2Array::createArray($data,$this->_rest_ful_service,"getRoleIdByIdUsuario");
        }else{
            $usuarios = new Application_Model_DbTable_Usuario ();
            $data=$usuarios->getRoleIdByIdUsuario($id_usuario);
            if ($data==NULL)  throw new Exception('No existe rol para Este id de usuario');
        }
        return $data;  
    }
    
    /**
    * Devuelve el id
    * @param string $email email que identifica al usuario
    * @throws Exception No existe este usuario o Ha habrido un Error de InterfaceUsuario'
    * @return array retorna una array array["id"], array["representacion_xml"] 
    */  
    public function getIdByEmail($email){
        if($this->_flag_web_service){
            $url ="&email=".$email; 
            $webservice=$this->getUrlWebService($this->_controller,"&method=getIdAndRepresentacionXmlByEmail".$url);
            $simple_xml=$this->restFul($webservice);
            $data = array();
            if($simple_xml->getIdAndRepresentacionXmlByEmail->status == "success"){
               $data=Eticketsecure_Utils_XML2Array::createArray($data,$this->_rest_ful_service,"getRoleIdByIdUsuario");   
            }else{
               throw new Exception('No existe este usuario o Ha habido un Error de InterfaceUsuario');
            }  
        }else{
            $usuarios = new Application_Model_DbTable_Usuario ();
            $data=$usuarios->getIdByEmail($email);
        }
        if ($data==NULL)  throw new Exception('No existe este usuario o Ha habido un Error de InterfaceUsuario');
        return $data;
    }
    
    
    public function getUsuarioById($id){        
        if($this->_flag_web_service){
            $url ="&id=".$id; 
            $webservice=$this->getUrlWebService($this->_controller,"&method=getUsuarioById".$url);
            $data=$this->restFul($webservice);
            if($data->getUsuarioById->status == "success") throw new Exception('InterfaceUsuarioException');
            $data=Eticketsecure_Utils_XML2Array::createArray($data,$this->_rest_ful_service,"getRoleIdByIdUsuario");
        }else{
            $usuarios = new Application_Model_DbTable_Usuario ();
            $data=$usuarios->getUsuarioById($id);
            if ($data==NULL)  throw new Exception('No existe este usuario o Ha habido un Error de InterfaceUsuario');
        }
        return $data;  
    }
    
    public function getUsuarioByEmail($email){        
        if($this->_flag_web_service){

        }else{
            $usuarios = new Application_Model_DbTable_Usuario ();
            $data=$usuarios->getUsuarioByEmail($email);
        }
        return $data;  
    }
      

    public function addUsuario($email, $id_role, $password, $fecha_alta,$fecha_ultimo_login,$idioma_predefinido,$flag_opt_in,$activo,$representacion_xml){
        if($this->_flag_web_service){
            
        }else{
            $usuarios = new Application_Model_DbTable_Usuario ();
            $data=$usuarios->addUsuario($this->_id_empresa,$email, $id_role, $password, $fecha_alta,$fecha_ultimo_login,$idioma_predefinido,$flag_opt_in,$activo,$representacion_xml);
            if ($data==NULL)  throw new Exception('No se ha podido ADD  este usuario o Ha habido un Error de InterfaceUsuario');
        }
        return $data;
    }

    public function setUsuarioById($id, $email, $id_role, $password,$fecha_alta,$fecha_ultimo_login,$idioma_predefinido,$flag_opt_in,$activo,$representacion_xml){
        if($this->_flag_web_service){
            
        }else{
            $usuarios = new Application_Model_DbTable_Usuario ();
            $data=$usuarios->setUsuarioById($id, $email, $id_role, $password,$fecha_alta,$fecha_ultimo_login,$idioma_predefinido,$flag_opt_in,$activo,$representacion_xml);
            if ($data==NULL)  throw new Exception('setUsuarioById');
        }
        return $data;
    }
    
    public function deleteUsuarioById($id){
        if($this->_flag_web_service){
            
        }else{
            $usuarios = new Application_Model_DbTable_Usuario ();
            $data=$usuarios->deleteUsuario($id);
            if ($data==NULL)  throw new Exception('No se ha podido DELETE este usuario o Ha habido un Error de InterfaceUsuario');
        }
        return $data;
    }
    
    

}

