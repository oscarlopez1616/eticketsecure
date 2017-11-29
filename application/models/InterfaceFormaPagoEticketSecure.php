<?php

class Application_Model_InterfaceFormaPagoEticketSecure extends Application_Model_InterfaceEticketSecure
{
    private $_controller;
    
    public function __construct(){
        parent::__construct();
        $this->_controller= "/forma-pago/index/index.php";
    }
    
    public function getFormaPago($id_forma_pago){
        if($this->_flag_web_service){
            $url ="&id_forma_pago=".$id_forma_pago; 
            $webservice=$this->getUrlWebService($this->_controller,"&method=getFormaPago".$url);
            $data=$this->restFul($webservice);  
            if($data->getFormaPago->status == "failed")
                throw new Exception('InterfaceFormaPagoException');
            $data=Eticketsecure_Utils_XML2Array::createArray($data,$this->_rest_ful_service,"getFormaPago");
        }else{
            $forma_pagos = new Application_Model_DbTable_FormaPago ();
            $forma_pago=$forma_pagos->get($id_forma_pago);
            if ($data==NULL)  throw new Exception('No existe XML para Estos Parametros');
        }
        return $data;
    }

    public function getAllFormaPago(){
        if($this->_flag_web_service){  
            $webservice=$this->getUrlWebService($this->_controller,"&method=getAllFormaPago");
            $data=$this->restFul($webservice);
            if($data->getAllFormaPago->status == "failed")
                throw new Exception('InterfaceFormaPagoException');
            $data=Eticketsecure_Utils_XML2Array::createArray($data,$this->_rest_ful_service,"getAllFormaPago");
        }else{
             $forma_pagos = new Application_Model_DbTable_FormaPago ();
             $data=$forma_pagos->getAll($id_empresa);
             if ($data==NULL)  throw new Exception('No existe XML para Estos Parametros');
        }
        return $data; 
    }

    public function setFormaPagoPublicada($id_forma_pago,$publicada){
        if($this->_flag_web_service){  
            $url ="&id_forma_pago=".$id_forma_pago."&publicada=".$publicada; 
            $webservice=$this->getUrlWebService($this->_controller,"&method=setFormaPagoPublicada".$url);
            $simple_xml=$this->restFul($webservice);
            if($simple_xml->setFormaPagoPublicada->status == "failed" && $simple_xml->setFormaPagoPublicada->error!='No se ha editado nada')
                throw new Exception('InterfaceFormaPAgoException');
            $data=Eticketsecure_Utils_XML2Array::createArray($data,$this->_rest_ful_service,"setFormaPagoPublicada");
        }else{
            $forma_pagos = new Application_Model_DbTable_FormaPago ();
            $data=$forma_pagos->setPublicada($id_forma_pago, $publicada);
            if ($data==0) throw new Exception('No se ha editado nada');
        }
        return $data;     
    }

}

