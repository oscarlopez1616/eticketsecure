<?php

class Application_Model_InterfaceACLEticketSecure extends Application_Model_InterfaceEticketSecure
{
    private $_controller;
    private $_rest_ful_service;

    public function __construct(){
        parent::__construct();
        $this->_controller= "/acl/index/index.php";
        $this->_rest_ful_service = "ServiceACL";
    }
      
    public function getACLRoleByIdRole($id_role){
        if($this->_flag_web_service){
            
        }else{
            $dbtable = new Application_Model_DbTable_ACLRoles();
            $roles=$dbtable->getAllACLRoles();
            if ($roles==NULL)  throw new Exception('No existe ACLRoles');
            $data = $roles;
        }
        return $data;
    }
      
    public function getAllACLRoles(){
        if($this->_flag_web_service){
            $webservice=$this->getUrlWebService($this->_controller,"&method=getAllACLRoles");
            $data=$this->restFul($webservice);
            if($data->getAllACLRoles->status == "failed") throw new Exception('InterfaceACLException');
            $data=Eticketsecure_Utils_XML2Array::createArray($data,$this->_rest_ful_service,"getAllACLRoles");
        }else{
            $dbtable = new Application_Model_DbTable_ACLRoles();
            $roles=$dbtable->getAllACLRoles();
            if ($roles==NULL)  throw new Exception('No existe ACLRoles');
            $data = $roles;
        }
        return $data;
    }
      
    public function getAllACLResources(){
        if($this->_flag_web_service){
            $webservice=$this->getUrlWebService($this->_controller,"&method=getAllACLResources");
            $data=$this->restFul($webservice);
            if($data->getAllACLResources->status == "failed") throw new Exception('InterfaceACLException');
            $data=Eticketsecure_Utils_XML2Array::createArray($data,$this->_rest_ful_service,"getAllACLResources");
        }else{
            $dbtable = new Application_Model_DbTable_ACLResources();
            $resources=$dbtable->getAllACLResources();
            if ($resources==NULL)  throw new Exception('No existe ACLResources');
            $data = $resources;
        }
        return $data;
    }
      
    public function getAllACLRolePermissionResources(){
        if($this->_flag_web_service){
            $webservice=$this->getUrlWebService($this->_controller,"&method=getAllACLRolePermissionResources");
            $data=$this->restFul($webservice);
            if($data->getAllACLRolePermissionResources->status == "failed") throw new Exception('InterfaceACLException');
            $data=Eticketsecure_Utils_XML2Array::createArray($data,$this->_rest_ful_service,"getAllACLRolePermissionResources");
        }else{
            $dbtable = new Application_Model_DbTable_ACLRoles();
            $roles=$dbtable->getAllACLRolePermissionResources();
            if ($roles==NULL)  throw new Exception('No existe ACLRolesPermissionResources');
            $data = $roles;
        }
        return $data;
    }
    
}

