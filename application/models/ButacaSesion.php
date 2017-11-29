<?php

class Application_Model_ButacaSesion
{
    private $_id_zona;
    private $_id_butaca;
    private $_estado;

    public function __construct($id_zona=NULL,$id_butaca=NULL,$estado=NULL){  
        
        $this->_id_zona=(int)$id_zona;
        $this->_id_butaca=(int)$id_butaca;
        $this->_estado=(string)$estado;
        
    } 

    public function getIdZona(){
        return $this->_id_zona;
    } 

    public function getIdButaca(){
        return $this->_id_butaca;
    } 

    public function getEstado(){
        return $this->_estado;
    }
    
    /**
    * Estados permitidos:
    * 0: "vendida_venta_propia"
    * 1: "vendida_venta_canales"
    * 2: "reservada"
    * 3: "libre"
    * 4: "bloqueada"
    * 5: "bloqueada_time**dateTime" ejemplo: bloqueada_time**2013-07-25 11:00:12
    * 6: "venta_canales"
    * 7: "venta_propia"
    */  
    public function setEstado($index_butaca_estado){
        $butaca_estado_arr = $reg_webservice=Zend_Registry::get('butaca_estado');
        if($index_butaca_estado==5){//los estados que tienen tiempo now
            $flag_estado= $butaca_estado_arr[$index_butaca_estado]."**".date('Y-m-d H:m:i');
            $this->_estado=$flag_estado;  
        }else{
            $this->_estado=$butaca_estado_arr[$index_butaca_estado];  
        }
    }
    
    public function getFlagNumerada() {
        return $this->_flag_numerada;
    }

    public function setFlagNumeradaTrue() {
        $this->_flag_numerada = 1;
    }

    public function setFlagNumeradaFalse() {
        $this->_flag_numerada = 0;
    }

    /**
    * Retorna la Serializacion como XML de un Objeto ButacaSesion
    * @return XML
    */  
    public function getAsXml()
    { 
        $string_xml ="\t\t<butaca_sesion id_zona=\"".$this->_id_zona."\" id_butaca=\"".$this->_id_butaca."\">\n";
        $string_xml.="\t\t\t<estado>".$this->_estado."</estado>\n";
        $string_xml.="\t\t</butaca_sesion>\n";
        return $string_xml;
    }     
}







