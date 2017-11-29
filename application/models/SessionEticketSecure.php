<?php

class Application_Model_SessionEticketSecure
{
    private $_session_eticket;
    
    /**
     * 
     * @param boolean $flag_reinicia_compra_time para procesos que no necesitan tiempo en la compra como el regalo
     * @param int $id_usuario esl identificador del usuario que este logueado si no esta logueado es Null y rol_id_es "guest", para el reinicio es null ya que no se usa
     */
    public function __construct($flag_reinicia_compra_time=true,$id_usuario=NULL){     
        $this->_session_eticket = new Zend_Session_Namespace('ticketex');
        $this->_session_eticket->id_usuario=$id_usuario;
        if($id_usuario!=NULL){
            $InterfaceUsuario = new Application_Model_InterfaceUsuarioEticketSecure();
            $rol_id = $InterfaceUsuario->getRoleIdByIdUsuario($id_usuario);
            $this->_session_eticket->rol_id=$rol_id;
        }else{
            $this->_session_eticket->rol_id="guest";  
        }
 
        if(!isset($this->_session_eticket->id_session)){//la primera que se crea
            $this->reCreaSessionEticketSecure($id_usuario);  
        }else if($flag_reinicia_compra_time){// si ya existe la sesion, el constructor solo necesita entrar aqui para reiniciar al sesion            
            $this->reiniciaCaducadaSessionEticketSecure();
        }
    }
    
    /**
     * nos da otro id_session y reinicia los parametros de sesion no establecerá la sesión como caducada.
     */
    public function reCreaSessionEticketSecure(){
        $this->_session_eticket->id_session = uniqid();
        $this->_session_eticket->fecha_creacion = date("Y-m-d H:i:s");
        $this->_session_eticket->anterior_era_caducada = 0;

        ///aqui los campos que no se reinician
        $this->_session_eticket->json_add_butacas_sesion_to_carrito = NULL;
        $this->_session_eticket->backup_Request = new Zend_Controller_Request_Http();
        $this->_session_eticket->user_data_arr = array();
    }
    
    /**
     * marcará la sesion como caducada y nos dara otro id_session pero sin restablecer los parametros de sesion.
     */
    public function reiniciaCaducadaSessionEticketSecure(){
        //esta fecha no puede diferir mas de time con la fecha now si difiere pido otro id_session y refresco la fecha
        $cron=Zend_Registry::get('cron');
        $time_seconds = $cron["reinicia_compra_time"]["clean"]["time"];
        $time_minutes = round($time_seconds/60,0);
        $DateTime_fecha_creacion = new DateTime($this->_session_eticket->fecha_creacion);            
        $DateTime_now = new DateTime(date("Y-m-d H:i:s"));
        $interval = date_diff($DateTime_fecha_creacion, $DateTime_now);
        //print_r($interval); 
        if($interval->format('%a')>=1 || $interval->format('%h')>=1 || $interval->format('%i')>=$time_minutes || $interval->format('%s')>=$time_seconds){
           $MantenimientoTicketing = new Eticketsecure_Utils_MantenimientoTicketing();
           $MantenimientoTicketing->reiniciaCompraTime();//si esta caducada la sesion reiniciamos compra
           $this->_session_eticket->id_session = uniqid();
           $this->_session_eticket->fecha_creacion = date("Y-m-d H:i:s");
           $this->_session_eticket->anterior_era_caducada = 1;// reiniciamos sesion y marcamos la anterior a 1
        }else{
           $this->_session_eticket->anterior_era_caducada = 0; // si volvemos a entrar ya no
        }
    }
    
    public function getIdSession(){
       return  $this->_session_eticket->id_session; 
    } 
    
    public function getFechaCreacion(){
       return  $this->_session_eticket->fecha_creacion; 
    } 

    /**
     * 
     * @return Zend_Controller_Request_Http
     */
    public function getBackupRequest(){
       return  $this->_session_eticket->backup_Request; 
    } 
    
    /**
     * 
     * @param Zend_Controller_Request_Http
     * @return array
     */
    public function setBackupRequest($Request){
       return  $this->_session_eticket->backup_Request=$Request; 
    } 

    /**
     * 
     * @return user_data
     */
    public function getUserDataArr(){
       return  $this->_session_eticket->user_data_arr; 
    } 
    
    /**
     * 
     * @param string $index
     * @param string $default_return_error_param
     * @return string | -1
     */
    public function getValueUserDataArrByIndex($index, $default_return_error_param){
       if(isset($this->_session_eticket->user_data_arr[$index])){
        $value =  $this->_session_eticket->user_data_arr[$index]; 
       }else{
           $value = $default_return_error_param;
       }
       return $value; 
    } 
    
    /**
     * Realiza un array merge entre $this->_session_eticket->user_data_arr y $user_data_elements_indexed_arr
     * @param array elementos indexados del estilo $user_data_element_indexed_arr["id_compra"]=>1
     * @return array
     */
    public function setUserDataArr($user_data_elements_indexed_arr){
       $this->_session_eticket->user_data_arr = array_merge($this->_session_eticket->user_data_arr,$user_data_elements_indexed_arr); 
    } 
    
    /**
     * Lo usamos para el paso 2 para meterlo en carrito
     * @param string $json
     * @return string
     */
    public function getJsonAddButacasSesionToCarrito(){
       return  $this->_session_eticket->json_add_butacas_sesion_to_carrito; 
    } 
    
    public function setJsonAddButacasSesionToCarrito($json){
       $this->_session_eticket->json_add_butacas_sesion_to_carrito=$json;
    } 
    
    /**
     * Este metodo nos dice si tenemos que mostrar el mensaje de Session caducada
     * @return int 
     * 0 si no era caducada la anterior
     * 1 si la anterior era caducada
     */
    public function getAnteriorEraCaducada(){
       return  $this->_session_eticket->anterior_era_caducada; 
    } 
    
    public function getIdUsuario(){
       return  $this->_session_eticket->id_usuario; 
    } 
    
    public function setUsuarioGuest(){
        $this->_session_eticket->id_usuario=NULL; 
        $this->_session_eticket->rol_id="guest"; 
    }
    
    public function setIdUsuario($id_usuario){
        $this->_session_eticket->id_usuario=$id_usuario; 
    } 
    
    public function getRolId(){
       return  $this->_session_eticket->rol_id; 
    } 
    
    public function setRolId($rol_id){
       $this->_session_eticket->rol_id=$rol_id; 
    }
    
    public function esLogueado(){
        if($this->_session_eticket->id_usuario!= NULL) return true;
        return false;
    }
    
}




