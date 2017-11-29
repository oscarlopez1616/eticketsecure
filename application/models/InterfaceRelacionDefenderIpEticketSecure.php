<?php

class Application_Model_InterfaceRelacionDefenderIpEticketSecure extends Application_Model_InterfaceEticketSecure
{
    private $_controller;
    private $_rest_ful_service;
    private $_cache;
    public function __construct(){
        parent::__construct();
        $this->_controller= "/relacion-defender-ip/index/index.php";
        $this->_rest_ful_service = "ServiceRelacionDefenderIp";
    }
      
 
    public function getAll(){
        if($this->_flag_web_service){

        }else{
            $RelacionDefenderIp = new Application_Model_DbTable_RelacionDefenderIp ();
            $data=$RelacionDefenderIp->getAll();
            if ($data==NULL)  throw new Exception('No existen RelacionDefenderIp');
        }
        return $data;
    }
      
 
    public function getById($id){
        if($this->_flag_web_service){

        }else{
            $RelacionDefenderIp = new Application_Model_DbTable_RelacionDefenderIp ();
            $data=$RelacionDefenderIp->getById($id);
            if ($data==NULL)  throw new Exception('No existe RelacionDefenderIp para esa id');
        }
        return $data;
    }
 
    public function getByIdDefenderAndIp($id_defender,$ip){
        if($this->_flag_web_service){

        }else{
            $RelacionDefenderIp = new Application_Model_DbTable_RelacionDefenderIp ();
            $data=$RelacionDefenderIp->getByIdDefenderAndIp($id_defender,$ip);
        }
        return $data;
    }
 
    public function addRelacionDefenderIp($id_defender,$ip,$intentos,$fecha_ultimo_intento,$num_umbrales_excedidos){
        if($this->_flag_web_service){

        }else{
            $RelacionDefenderIp = new Application_Model_DbTable_RelacionDefenderIp ();
            $data=$RelacionDefenderIp->addRelacionDefenderIp($id_defender,$ip,$intentos,$fecha_ultimo_intento,$num_umbrales_excedidos);
            if ($data==NULL)  throw new Exception('No se ha podido hacer el Add de RelacionDefenderIp');
        }
        return $data;
    }
    
    public function setRelacionDefenderIpById($id,$id_defender,$ip,$intentos,$fecha_ultimo_intento,$num_umbrales_excedidos){
        if($this->_flag_web_service){

        }else{
            $RelacionDefenderIp = new Application_Model_DbTable_RelacionDefenderIp ();
            $data=$RelacionDefenderIp->setRelacionDefenderIpById($id,$id_defender,$ip,$intentos,$fecha_ultimo_intento,$num_umbrales_excedidos);
            if ($data==NULL)  throw new Exception('No se ha podido hacer el set de RelacionDefenderIp para este id_relacion_defender_ip');
        }
        return $data;
    }
    
    public function deleteRelacionDefenderIpById($id){
        if($this->_flag_web_service){

        }else{
            $RelacionDefenderIp = new Application_Model_DbTable_RelacionDefenderIp ();
            $data=$RelacionDefenderIp->deleteRelacionDefenderIpById($id);
            if ($data==NULL)  throw new Exception('No se ha podido hacer el delete de RelacionDefenderIp para este id_relacion_defender_ip');
        }
        return $data;
    }

}

