<?php

class Application_Model_Defender
{
    private $_id;
    private $_nombre_proceso;
    private $_umbral;
    private $_umbral_excedido;
    private $_InterfaceDefenderEticketSecure;
        
    public function __construct($id=NULL,$nombre_proceso=NULL,$umbral=NULL,$umbral_excedido=NULL){
            
        $this->_InterfaceDefenderEticketSecure= new Application_Model_InterfaceDefenderEticketSecure(); 
        $this->_id= $id;
        $this->_nombre_proceso= $nombre_proceso;
        $this->_umbral= $umbral;
        $this->_umbral_excedido= $umbral_excedido;
    }
    
    public function load($id){
        if(!isset($id))    throw new Exception('loadDefender');
        $this->_id=$id;   
        $data = $this->_InterfaceDefenderEticketSecure->getById($id);
        $this->_nombre_proceso =  $data["nombre_proceso"];
        $this->_umbral =  $data["umbral"];
        $this->_umbral_excedido =  $data["umbral_excedido"];
    }
    
    public function loadByNombreProceso($nombre_proceso){
        if(!isset($nombre_proceso))    throw new Exception('loadDefenderByNombreProceso');
        $data = $this->_InterfaceDefenderEticketSecure->getByNombreProceso($nombre_proceso);
        $id =  $data["id"];
        $this->load($id);
    }
    
    public function getId() {
        return $this->_id;
    }

    public function getNombreProceso() {
        return $this->_nombre_proceso;
    }

    public function getUmbral() {
        return $this->_umbral;
    }

    public function getUmbralExcedido() {
        return $this->_umbral_excedido;
    }

    public function setNombreProceso($nombre_proceso) {
        $this->_nombre_proceso = $nombre_proceso;
    }

    public function setUmbral($umbral) {
        $this->_umbral = $umbral;
    }

    public function setUmbralExcedido($umbral_excedido) {
        $this->_umbral_excedido = $umbral_excedido;
    }
    
    /**
     * Devuelve el manejador de defender RelacionDefenderIp lo creoa o lo carga en funcion de si existia o no
     * @param string $ip
     * @return \Application_Model_RelacionDefenderIp
     */
    public function startManejadorRelacionDefenderIp($ip){
        try{// lo rescatamos si ya existe
            $RelacionDefenderIp = $this->getRelacionDefenderIpByIp($ip);
      } catch (Exception $e) {// si no existe se lanzará una excepcion del interfazRelacionDefenderIp lo capturamos y creamos el manejador RelacionDefenderIp para esta ip y este defender          
          if($e->getCode()!=1 && $e->getCode()!=2){//ver archivo de mapeo de Excepciones
            $id=NULL; 
            $id_defender =$this->_id;
            $ip = $ip;
            $intentos = 0;
            $fecha_ultimo_intento= date("Y-m-d H:i:s");
            $num_umbrales_excedidos=0;
            $RelacionDefenderIp = new Application_Model_RelacionDefenderIp(NULL,$id_defender, $ip, $intentos, $fecha_ultimo_intento, $num_umbrales_excedidos);
          }else{
            throw new Exception($e->getMessage(),$e->getCode());
          }
      }  
      return $RelacionDefenderIp;
    }
    
    /**
     * 
     * @param string $ip
     * @return \Application_Model_RelacionDefenderIp
     */
    private function getRelacionDefenderIpByIp($ip){
        $InterfaceRelacionDefenderIpEticketSecure= new Application_Model_InterfaceRelacionDefenderIpEticketSecure(); 
        $id_relacion_defender_ip = $InterfaceRelacionDefenderIpEticketSecure->getByIdDefenderAndIp($this->_id,$ip);
        $RelacionDefenderIp = new Application_Model_RelacionDefenderIp();
        $RelacionDefenderIp->load($id_relacion_defender_ip["id"]);
        return $RelacionDefenderIp;
    } 
    
    public function write(){
        if(!isset($this->_id)) throw new Exception("writeDefenderException");
        $nombre_proceso = $this->_nombre_proceso;
        $umbral = $this->_umbral;
        $umbral_excedido = $this->_umbral_excedido;
        $data=$this->_InterfaceDefenderEticketSecure->setDefenderById($this->_id,$nombre_proceso, $umbral,$umbral_excedido);
        return $data; 
    } 
    

    public function add(){
        $nombre_proceso = $this->_nombre_proceso;
        $umbral = $this->_umbral;
        $umbral_excedido = $this->_umbral_excedido;
        $data=$this->_InterfaceDefenderEticketSecure->addDefender($nombre_proceso, $umbral,$umbral_excedido);
        $this->_id=$data;
        return $data; 
    } 

    public function delete(){
        if(!isset($this->_id)) throw new Exception("deleteDefenderException");
        $data=$this->_InterfaceDefenderEticketSecure->deleteDefenderById($this->_id);//Tambien se borra el RelacionDefenderIp por la Foreign Key
        return $data;     
    }  
}

