<?php

class Application_Model_Canal extends Application_Model_AbstractArtefactoObject
{
    
    private $_nombre;
    private $_descuento;
    private $_integracion_disponible;
    private $_CanalIntegracion;

    public function __construct($id=NULL,$nombre=NULL,$descuento=NULL,$integracion_disponible=NULL,$CanalIntegracion=NULL){       
        
        $id_categoria_artefacto = 8;// es el $id_categoria_artefacto de Canal
        parent::__construct($id,$id_categoria_artefacto);
     
        $this->_id=(int)$id;
        $this->_nombre=$nombre;
        $this->_descuento=(float)$descuento;   
        $this->_integracion_disponible=(float)$integracion_disponible;   
        $this->_CanalIntegracion=$CanalIntegracion;   
    } 
    
    protected function loadAtributosRepresentacionXml($artefacto_simple_xml){ 

        $this->_nombre = (string)$artefacto_simple_xml->nombre;
        $this->_descuento = (string)$artefacto_simple_xml->descuento;
        $this->_integracion_disponible = (int)$artefacto_simple_xml->integracion_disponible;
             
        //Crear objeto CanalIntegracion
        $web_service_push=(string)$artefacto_simple_xml->canal_integracion->web_service_push;
        $web_service_pop=(string)$artefacto_simple_xml->canal_integracion->web_service_pop;
        $user=(string)$artefacto_simple_xml->canal_integracion->user;
        $password=(string)$artefacto_simple_xml->canal_integracion->password;
        $api_key=(string)$artefacto_simple_xml->canal_integracion->api_key;
        
        $this->_CanalIntegracion = new Application_Model_CanalIntegracion($web_service_push, $web_service_pop, $user, $password, $api_key);
    } 

    public function getNombre(){
        return $this->_nombre;
    } 
    
    public function setNombre($nombre){
        $this->_nombre=$nombre;
    } 
    
    public function getDescuento(){
        return $this->_descuento;
    }
    
    public function setDescuento($descuento){
        $this->_descuento=$descuento;
    } 
    
    public function getIntegracionDisponible(){
        return $this->_integracion_disponible;
    }
    
    public function setIntegracionDisponible($integracion_disponible){
        $this->_integracion_disponible= $integracion_disponible;
    }
    
    public function getCanalIntegracion(){
        return $this->_CanalIntegracion;
    }
    
    public function setCanalIntegracion($CanalIntegracion){
        $this->_CanalIntegracion= $CanalIntegracion;
    }

    /**
    * Retorna la Serializacion como XML de un Objeto Canal
    * @return XML
    */  
    protected function getAsXmlAtributosRepresentacionXml()
    {      

        $string_xml ="\t\t\t<nombre>".$this->_nombre."</nombre>\n";
        $string_xml.="\t\t\t<descuento>".$this->_descuento."</descuento>\n";
        $string_xml.="\t\t\t<integracion_disponible>".$this->_integracion_disponible."</integracion_disponible>\n";
        $string_xml.= $this->_CanalIntegracion->getAsXml();
        return $string_xml;
    } 
    
    protected function addSpecificArtefacto($params_arr,$last_insert_id){}
    
    protected function writeSpecificArtefacto($params_arr){}  
    
    protected function deleteSpecificArtefacto($params_arr){}
    
}