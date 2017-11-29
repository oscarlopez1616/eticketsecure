<?php

class Application_Model_DefenderIpBaneada
{
    private $_ip;
    private $_fecha_baneo;
    private $_InterfaceDefenderIpBaneadaEticketSecure;
        
    public function __construct($ip=NULL,$fecha_baneo=NULL){
            
        $this->_InterfaceDefenderIpBaneadaEticketSecure= new Application_Model_InterfaceDefenderIpBaneadaEticketSecure();
        $this->_ip= $ip;
        $this->_fecha_baneo= $fecha_baneo;
    }
    
    public function load($ip){
        if(!isset($ip))    throw new Exception('loadDefenderIpBaneada');
        $this->_ip=$ip;   
        $data = $this->_InterfaceDefenderIpBaneadaEticketSecure->getByIp($ip);
        $this->_fecha_baneo =  $data["fecha_baneo"];
    }
    
    public function getIp() {
        return $this->_ip;
    }

    public function getFechaBaneo() {
        return $this->_fecha_baneo;
    }

    public function setFechaBaneo($fecha_baneo) {
        $this->_fecha_baneo = $fecha_baneo;
    }

    
    public function write(){
        if(!isset($this->_ip)) throw new Exception("writeDefenderIpBaneadaException");
        $fecha_baneo = $this->_fecha_baneo;
        $data=$this->_InterfaceDefenderIpBaneadaEticketSecure->setDefenderByIp($this->_ip,$fecha_baneo);
        return $data; 
    } 
    
    /**
     * 
     * @param string $ip
     * array()
     */
    public function add(){
        $ip = $this->_ip;
        $fecha_baneo = $this->_fecha_baneo;

        $data=$this->_InterfaceDefenderIpBaneadaEticketSecure->addDefender($ip, $fecha_baneo);
        return $data; 
    } 

    public function delete(){
        if(!isset($this->_ip)) throw new Exception("deleteDefenderIpBaneadaException");
        $data=$this->_InterfaceDefenderIpBaneadaEticketSecure->deleteDefenderByIp($this->_ip);
        return $data;     
    }  
}



