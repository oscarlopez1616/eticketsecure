<?php

class Application_Model_InterfaceDefenderIpBaneadaEticketSecure extends Application_Model_InterfaceEticketSecure
{
    private $_controller;
    private $_rest_ful_service;
    private $_cache;
    public function __construct(){
        parent::__construct();
        $this->_controller= "/defender/index/index.php";
        $this->_rest_ful_service = "ServiceDefender";
    }
      
 
    public function getByIp($ip){
        if($this->_flag_web_service){

        }else{
            $DefenderIpBaneada = new Application_Model_DbTable_DefenderIpBaneada ();
            $data=$DefenderIpBaneada->getByIp($ip);
            if ($data==NULL)  throw new Exception('No existe DefenderBaneadaIp para esta ip');
        }
        return $data;
    }
 
    public function getAll(){
        if($this->_flag_web_service){

        }else{
            $DefenderIpBaneada = new Application_Model_DbTable_DefenderIpBaneada ();
            $data=$DefenderIpBaneada->getAll();
            if ($data==NULL)  throw new Exception('No existen DefenderBaneadaIp');
        }
        return $data;
    }
    
 
    public function addDefender($ip,$fecha_baneo){
        if($this->_flag_web_service){

        }else{
            $DefenderIpBaneada = new Application_Model_DbTable_DefenderIpBaneada ();
            $data=$DefenderIpBaneada->addDefenderIpBaneada($ip,$fecha_baneo);
            if ($data==NULL)  throw new Exception('No se ha podido hacer el Add de DefenderBaneadaIp');
        }
        return $data;
    }
    
    public function setDefenderByIp($ip,$fecha_baneo){
        if($this->_flag_web_service){

        }else{
            $DefenderIpBaneada = new Application_Model_DbTable_DefenderIpBaneada ();
            $data=$DefenderIpBaneada->setDefenderByIp($ip,$fecha_baneo);
            if ($data==NULL)  throw new Exception('No se ha podido hacer el set de DefenderBaneadaIp para esta ip');
        }
        return $data;
    }
    
    public function deleteDefenderByIp($ip){
        if($this->_flag_web_service){

        }else{
            $DefenderIpBaneada = new Application_Model_DbTable_DefenderIpBaneada ();
            $data=$DefenderIpBaneada->deleteDefenderByIp($id);
            if ($data==NULL)  throw new Exception('No se ha podido hacer el delete de DefenderBaneadaIp para esta ip');
        }
        return $data;
    }

}

