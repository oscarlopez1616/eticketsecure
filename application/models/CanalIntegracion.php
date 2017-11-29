<?php

class Application_Model_CanalIntegracion
{
    private $_web_service_push;
    private $_web_service_pop;
    private $_user;
    private $_password;
    private $_api_key;

    public function __construct($web_service_push=NULL,$web_service_pop=NULL,$user=NULL,$password=NULL,$api_key=NULL){  
        $this->_web_service_push=(string)$web_service_push;
        $this->_web_service_pop=(string)$web_service_pop;
        $this->_user=(string)$user;
        $this->_password=(string)$password;
        $this->_api_key=(string)$api_key;
    } 

    public function getWebServicePush(){
        return $this->_web_service_push;
    } 
    
    public function setWebServicePush($web_service_push){
        return $this->_web_service_push=$web_service_push;
    } 

    public function getWebServicePop(){
        return $this->_web_service_pop;
    } 

    public function setWebServicePop($web_service_pop){
        return $this->_web_service_pop=$web_service_pop;
    } 

    public function getUser(){
        return $this->_user;
    } 
    
    public function setUser($user){
        return $this->_user=$user;
    } 

    public function getPassword(){
        return $this->_password;
    } 
    
    public function setPassword($password){
        return $this->_password=$password;
    } 

    public function getApiKey(){
        return $this->_api_key;
    } 
    
    public function setApiKey($api_key){
        return $this->_api_key=$api_key;
    } 
        

    /**
    * Retorna la Serializacion como XML de un Objeto CanalIntegracion
    * @return XML
    */  
    public function getAsXml()
    {
        $string_xml ="\t\t<canal_integracion>\n";
        $string_xml.="\t\t\t<web_service_push>".$this->_web_service_push."</web_service_push>\n";
        $string_xml.="\t\t\t<web_service_pop>".$this->_web_service_pop."</web_service_pop>\n";
        $string_xml.="\t\t\t<user>".$this->_user."</user>\n";
        $string_xml.="\t\t\t<password>".$this->_password."</password>\n";
        $string_xml.="\t\t\t<api_key>".$this->_api_key."</api_key>\n";
        $string_xml.="\t\t</canal_integracion>\n";
        return $string_xml;
    } 
    
}










