<?php

class Application_Model_RelacionDefenderIp
{
    private $_id;
    private $_id_defender;
    private $_ip;
    private $_intentos;
    private $_fecha_ultimo_intento;
    private $_num_umbrales_excedidos;
    private $_InterfaceRelacionDefenderIpEticketSecure;
        
    public function __construct($id=NULL,$id_defender=NULL,$ip=NULL,$intentos=NULL,$fecha_ultimo_intento=NULL,$num_umbrales_excedidos=NULL){
                  
        $this->_InterfaceRelacionDefenderIpEticketSecure= new Application_Model_InterfaceRelacionDefenderIpEticketSecure(); 
       
        $this->_id= $id;
        $this->_id_defender= $id_defender;
        $this->_ip= $ip;
        $this->_intentos= $intentos;
        $this->_fecha_ultimo_intento= $fecha_ultimo_intento;
        $this->_num_umbrales_excedidos= $num_umbrales_excedidos;
        try{
            if($this->esIpBaneada()){
                throw new Exception('DefenderException - Este Usuario Esta Baneado Para Este Proceso',2);
            }
            if($this->esIntentosExcecidos()){
                  $defender = Zend_Registry::get('defender');
                  $time_segundos_lapse_umbral = $defender["time_segundos_lapse_umbral"];
                  $time_minutes = round($time_segundos_lapse_umbral/60,0);
                  throw new Exception('DefenderException - Numero de Intentos Excedidos Vuelve en: '.$time_minutes.' minutos',1);
             }
        } catch (Exception $ex) {}
    }
    
    public function load($id){
        if(!isset($id))    throw new Exception('loadRelacionDefenderIp');
        $this->_id=$id;   
        $data = $this->_InterfaceRelacionDefenderIpEticketSecure->getById($id);
        
        $this->_id_defender= $data["id_defender"];
        $this->_ip= $data["ip"];
        $this->_intentos= $data["intentos"];
        $this->_fecha_ultimo_intento= $data["fecha_ultimo_intento"];
        $this->_num_umbrales_excedidos= $data["num_umbrales_excedidos"];
        if($this->esIpBaneada()){
            throw new Exception('DefenderException - Este Usuario Esta Baneado Para Este Proceso',2);
        }else if($this->esIntentosExcecidos()){
            $defender = Zend_Registry::get('defender');
            $time_segundos_lapse_umbral = $defender["time_segundos_lapse_umbral"];
            $time_minutes = round($time_segundos_lapse_umbral/60,0);
            throw new Exception('DefenderException - Numero de Intentos Excedidos Vuelve en: '.$time_minutes.' minutos',1);
        }

    }
    
    public function getId() {
        return $this->_id;
    }

    public function getIdDefender() {
        return $this->_id_defender;
    }
    
    public function getIp() {
        return $this->_ip;
    }

    public function getIntentos() {
        return $this->_intentos;
    }

    public function getFechaUltimoIntento() {
        return $this->_fecha_ultimo_intento;
    }

    public function getNumUmbralesExcedidos() {
        return $this->_num_umbrales_excedidos;
    }

    private function setIdDefender($id_defender) {
        return $this->_id_defender=$id_defender;
    }
    
    private function setIp($ip) {
        $this->_ip = $ip;
    }

    private function setIntentos($intentos) {
        $this->_intentos = $intentos;
    }

    private function setFechaUltimoIntento($fecha_ultimo_intento) {
        $this->_fecha_ultimo_intento = $fecha_ultimo_intento;
    }

    private function setNumUmbralesExcedidos($num_umbrales_excedidos) {
        $this->_num_umbrales_excedidos = $num_umbrales_excedidos;
    }

    public function incrementaIntentos(){
        if($this->esPermitido()){
            $this->_intentos = $this->_intentos+1;
            $this->_fecha_ultimo_intento = date("Y-m-d H:i:s");
            $flag_exception = false;
            if($this->esIntentosExcecidos()){
                $this->_num_umbrales_excedidos = $this->_num_umbrales_excedidos+1;
                $defender = Zend_Registry::get('defender');
                $time_segundos_lapse_umbral = $defender["time_segundos_lapse_umbral"];
                $time_minutes = round($time_segundos_lapse_umbral/60,0);
                $message_exception = ('DefenderException - Numero de Intentos Excedidos Vuelve en: '.$time_minutes.' minutos');
                $code_exception = 1;
                $flag_exception = true; 
            }
            if($this->esIpBaneada()){//esta excepcion es mas potente que la intentos excedidos asi que la sobreescribe
                $DefenderIpBaneada = new Application_Model_DefenderIpBaneada($this->_ip, date("Y-m-d H:i:m"));
                try{// puede que ya exista si existe no pasa nada seguimos
                    $DefenderIpBaneada->add();
                } catch (Exception $ex) {}

                $message_exception = ('DefenderException - Este Usuario Esta Baneado Para Este Proceso');
                $code_exception = 2;
                $flag_exception = true;
            }

            try{// lo escribimos
                $this->write();
            } catch (Exception $e) {////si no existe excepcion del interfaz, creamos la RelacionDefenderIp
                $this->add();
            }
            if($flag_exception)  throw new Exception($message_exception,$code_exception);
        }        
    }
    
    /**
     * Retorna true o false segun se intentos haya supero el umbral o no
     * @return boolean
     */
    public function esPermitido(){
        $flag_permitido = true;
        $flag_intentos_excecidos = $this->esIntentosExcecidos();
        $flag_ip_baneada = $this->esIpBaneada();
        $flag_permitido = $flag_intentos_excecidos&&$flag_ip_baneada;
        $flag_permitido = !$flag_permitido;
        return $flag_permitido;
    }
    
    public function esIntentosExcecidos(){
        $Defender = new Application_Model_Defender();
        $Defender->load($this->_id_defender);
        $umbral_defender = $Defender->getUmbral();
        $flag_permitido = false;
        if($this->_intentos>=$umbral_defender){// se supera el umbral de intentos
            $flag_permitido = true;
        }
        return $flag_permitido;
    }
    
    public function esIpBaneada(){
        $Defender = new Application_Model_Defender();
        $Defender->load($this->_id_defender);
        $flag_permitido = false;
        $umbral_excedido_defender = $Defender->getUmbralExcedido();
        $DefenderIpBaneada = new Application_Model_DefenderIpBaneada();
        try{
            $DefenderIpBaneada->load($this->_ip);
            return true;
        } catch (Exception $ex) {
            $flag_permitido = false;
        }
        if($this->_num_umbrales_excedidos>=$umbral_excedido_defender) return true;
        return $flag_permitido;
    }
    
    
    
    /**
     * Reinicia los umbrales de tiempo de espera defender.time_segundos_lapse_umbral
     */
    public function desbloqueaTimeLapseIntentosRelacionDefenderIp(){       
        if($this->esIntentosExcecidos() && !$this->esIpBaneada()){//para reiniciar la ip tiene que ser excecido intentos pero no tiene que estar baneada.
            $defender = Zend_Registry::get('defender');
            $time_segundos_lapse_umbral = $defender["time_segundos_lapse_umbral"];
            $time_minutes = round($time_segundos_lapse_umbral/60,0);
            $DateTime_fecha_creacion = new DateTime($this->_fecha_ultimo_intento);            
            $DateTime_now = new DateTime(date("Y-m-d H:i:s"));
            $interval = date_diff($DateTime_fecha_creacion, $DateTime_now);
            if($interval->format('%a')>=1 || $interval->format('%h')>=1 || $interval->format('%i')>=$time_minutes || $interval->format('%s')>=$time_segundos_lapse_umbral){
                $this->setIntentos(0);
                $this->setFechaUltimoIntento(date("Y-m-d H:i:s"));
                try{// lo escribimos
                    $this->write();
                } catch (Exception $e) {////si no existe excepcion del interfaz, creamos la RelacionDefenderIp
                    $this->add();
                }
            }
        }
    }
    
    private function write(){
        if(!isset($this->_id)) throw new Exception("writeRelacionDefenderIpException");
        $id_defender = $this->_id_defender;
        $ip = $this->_ip;
        $intentos = $this->_intentos ;
        $fecha_ultimo_intento = $this->_fecha_ultimo_intento ;
        $num_umbrales_excedidos= $this->_num_umbrales_excedidos;
        $data=$this->_InterfaceRelacionDefenderIpEticketSecure->setRelacionDefenderIpById($this->_id,$id_defender,$ip, $intentos, $fecha_ultimo_intento, $num_umbrales_excedidos);
        return $data;  
    } 
    
    private function add(){
        $id_defender = $this->_id_defender;
        $ip = $this->_ip;
        $intentos = $this->_intentos ;
        $fecha_ultimo_intento = $this->_fecha_ultimo_intento ;
        $num_umbrales_excedidos= $this->_num_umbrales_excedidos;
        $data=$this->_InterfaceRelacionDefenderIpEticketSecure->addRelacionDefenderIp($id_defender,$ip, $intentos, $fecha_ultimo_intento, $num_umbrales_excedidos);
        $this->_id=$data;
        return $data;  
    } 
    
    public function delete(){
        if(!isset($this->_id)) throw new Exception("deleteRelacionDefenderIpException");
        $data=$this->_InterfaceDefenderEticketSecure->deleteRelacionDefenderIpById($this->_id);
        return $data;     
    }  
}



