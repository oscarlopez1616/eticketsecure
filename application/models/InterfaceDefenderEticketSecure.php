<?php

class Application_Model_InterfaceDefenderEticketSecure extends Application_Model_InterfaceEticketSecure
{
    private $_controller;
    private $_rest_ful_service;
    private $_cache;
    public function __construct(){
        parent::__construct();
        $this->_controller= "/defender/index/index.php";
        $this->_rest_ful_service = "ServiceDefender";
    }
      
 
    public function getById($id){
        if($this->_flag_web_service){

        }else{
            $Defender = new Application_Model_DbTable_Defender ();
            $data=$Defender->getById($id);
            if ($data==NULL)  throw new Exception('No existe Defender para esa id');
        }
        return $data;
    }
 
    public function getByNombreProceso($nombre_proceso){
        if($this->_flag_web_service){

        }else{
            $Defender = new Application_Model_DbTable_Defender ();
            $data=$Defender->getByNombreProceso($nombre_proceso);
            if ($data==NULL)  throw new Exception('No existe Defender para esa id');
        }
        return $data;
    }
 
    public function getAll(){
        if($this->_flag_web_service){

        }else{
            $Defender = new Application_Model_DbTable_Defender ();
            $data=$Defender->getAll();
            if ($data==NULL)  throw new Exception('No existn Defender');
        }
        return $data;
    }
    
 
    public function addDefender($nombre_proceso,$umbral,$umbral_excedido){
        if($this->_flag_web_service){

        }else{
            $Defender = new Application_Model_DbTable_Defender ();
            $data=$Defender->addDefender($nombre_proceso,$umbral,$umbral_excedido);
            if ($data==NULL)  throw new Exception('No se ha podido hacer el Add de Defender');
        }
        return $data;
    }
    
    public function setDefenderById($id,$nombre_proceso,$umbral,$umbral_excedido){
        if($this->_flag_web_service){

        }else{
            $Defender = new Application_Model_DbTable_Defender ();
            $data=$Defender->setDefenderById($id,$nombre_proceso,$umbral,$umbral_excedido);
            if ($data==NULL)  throw new Exception('No se ha podido hacer el set de Defender para este id_defender');
        }
        return $data;
    }
    
    public function deleteDefenderById($id){
        if($this->_flag_web_service){

        }else{
            $Defender = new Application_Model_DbTable_Defender ();
            $data=$Defender->deleteDefenderById($id);
            if ($data==NULL)  throw new Exception('No se ha podido hacer el delete de Defender para este id_defender');
        }
        return $data;
    }

}

