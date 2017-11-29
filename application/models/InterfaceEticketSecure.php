<?php

class Application_Model_InterfaceEticketSecure
{
    protected $_domain;
    protected $_ip;
    protected $_id_empresa;
    protected $_flag_web_service;
    protected $_flag_cache;
    
    public function __construct(){      
        $this->_flag_web_service = $reg_webservice=Zend_Registry::get('flag_web_service');
        $this->_flag_cache = $reg_webservice=Zend_Registry::get('flag_cache');
        $reg_webservice=Zend_Registry::get('webservice');
        $this->_domain=$reg_webservice['domain'];
        $this->_ip=$reg_webservice['ip'];
        $this->_id_empresa=$reg_webservice['id_empresa'];
        $this->_api_key = "no_api_key_localhost";
        if($this->_flag_web_service){
            $controller= "/autenticar/index/index.php";
            $webservice=$this->_domain.$controller.'?ip='.$this->_ip.'&id_empresa='.$this->_id_empresa;
            $url= $webservice."&method=autenticar".'&secure='.$reg_webservice['secure'];
            $simple_xml=$this->restFul($url);
            $api_key = $simple_xml->autenticar->api_key; 
            $this->_api_key = $api_key;
        }
    } 
  
    protected function restFul($url){
        $c = curl_init($url);
        curl_setopt($c, CURLOPT_RETURNTRANSFER, true);
        $get_web_service = curl_exec($c);
        curl_close($c);
        if ($get_web_service==NULL || $get_web_service=="")  throw new Exception('No se puede Resolver la Peticion');
        $simple_xml= simplexml_load_string($get_web_service);
        return $simple_xml;
    } 
    
    public function getUrlWebService($controller,$params){
        $webservice=$this->_domain.$controller.'?ip='.$this->_ip.'&id_empresa='.$this->_id_empresa.'&api_key='.$this->_api_key.$params;
        return $webservice;
    }
    
    public function getFlagWebService(){
        return $this->_flag_web_service;
    }
    
    public function getFlagCache(){
        return $this->_flag_cache;
    }

}

