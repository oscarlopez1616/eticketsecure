<?php

abstract class Application_Model_AbstractArtefactoObject
{
    protected $_id;
    protected $_id_categoria_artefacto;

    protected $_InterfaceArtefactoEticketSecure;

    
    public function __construct($id=NULL,$id_categoria_artefacto=NULL){
        $this->_InterfaceArtefactoEticketSecure = new Application_Model_InterfaceArtefactoEticketSecure();  
        $this->_id=$id;
        $this->_id_categoria_artefacto=$id_categoria_artefacto;
    }

    
    /**
     * Este metodo abstracto hara el load 
     * que implemente esta clase abstracta (en representacion_xml en eticketsecure2)
     * @param simple_xml_object $artefacto_simple_xml
     */
    abstract protected function loadAtributosRepresentacionXml($artefacto_simple_xml);
    
    
    /**
    * Este metodo abastracto retorna
    * la Serializacion como XML de los atributos de la clase que implemente la clase abstracta LineaCarritoCompra
    * @return string
    */  
    abstract protected function getAsXmlAtributosRepresentacionXml(); 
    
    /**
    * @param integer $last_insert_id el id donde se inserto el artefacto
    * @param array[] $params_arr parametros que puede necesitar el metodo protected addSpecificArtefacto($params_arr)
    * Este metodo abastracto
    * Permite implementar logicas adicionales de write()
    */  
    abstract protected function addSpecificArtefacto($params_arr,$last_insert_id);   
    
    /**
    * @param array[] $params_arr  parametros que puede necesitar el metodo protected writeSpecificArtefacto($params_arr)
    * Este metodo abastracto
    * Permite implementar logicas adicionales de write()
    */  
    abstract protected function writeSpecificArtefacto($params_arr);   
    
    /**
    * @param array[] $params_arr parametros que puede necesitar el metodo protected deleteSpecificArtefacto($params_arr)
    * Este metodo abastracto
    * Permite implementar logicas adicionales de delete()
    */  
    abstract protected function deleteSpecificArtefacto($params_arr);   
    
    /**
     * 
     * @param int $id
     * @param int $id_categoria_artefacto
     */
    public function load($id){
        if(!isset($id))    throw new Exception('loadAbstractArtefactoMenu');
         
        $this->_id=$id;   
        $data = $this->_InterfaceArtefactoEticketSecure->getArtefactoById($id); 
        $data = $data["representacion_xml"];
        $artefacto_simple_xml= simplexml_load_string($data);
        $this->loadAtributosRepresentacionXml($artefacto_simple_xml); 
    }
    
    public function getId(){
        return $this->_id;
    }
    
    public function getIdCategoriaArtefacto(){
        return $this->_id_categoria_artefacto;
    }
    
    protected function setIdCategoriaArtefacto($id_categoria_artefacto){
        return $this->_id_categoria_artefacto=$id_categoria_artefacto;
    }
    
    /**
    * la Serializacion como XML
    * @return string
    */  
    public function getAsXml(){
        $string_xml="<artefacto>\n";
        
        $string_xml.=$this->getAsXmlAtributosRepresentacionXml();
        
        $string_xml.="</artefacto>"; 
        return $string_xml;
    }   
    
    /**
    * Añade un nuevo AbstractArtefactoMenu
    * @$params_arr parametros que puede necesitar el metodo protected addSpecificArtefacto($params_arr)
    * @throws Exception addAbstractArtefactoMenuException
    * @throws Exception InterfaceArtefactoException
    * @return mixed(integer,simple_xml_object)
    */  
    public function add($params_arr=NULL){
        if(!isset($this->_id_categoria_artefacto)) throw new Exception("addAbstractArtefactoObjectException");
        $representacion_xml = $this->getAsXml();
        $data=$this->_InterfaceArtefactoEticketSecure->addArtefacto($this->_id_categoria_artefacto,$representacion_xml); 
        $this->_id=$data;
        $this->addSpecificArtefacto($params_arr,$this->_id);
        return $data;   
    }
    

    /**
    * Escribe la información del AbstractArtefactoMenu
    * @$params_arr parametros que puede necesitar el metodo protected writeSpecificArtefacto($params_arr)
    * @throws Exception writeAbstractArtefactoObjetcException
    * @throws Exception InterfaceArtefactoException
    * @return mixed(integer,simple_xml_object)
    */  
    public function write($params_arr=NULL){
        if(!isset($this->_id)) throw new Exception("writeAbstractArtefactoObjectException");
        $this->writeSpecificArtefacto($params_arr);
        $representacion_xml = $this->getAsXml();
        $data = $this->_InterfaceArtefactoEticketSecure->setArtefactoById($this->_id, $representacion_xml, false);
        return $data; 
    }  
    
    /**
    * Borra el AbstractArtefacto
    * @$params_arr parametros que puede necesitar el metodo protected deleteSpecificArtefacto($params_arr)
    * @throws Exception deleteAbstractArtefactoObjectException
    * @throws Exception InterfaceArtefactoException
    * @return mixed(integer,simple_xml_object)
    */      
    public function delete($params_arr=NULL){
        if(!isset($this->_id)) throw new Exception("deleteAbstractArtefactoObjectException");
        $this->deleteSpecificArtefacto($params_arr);
        $data=$this->_InterfaceArtefactoEticketSecure->deleteArtefactoById($this->_id);
        return $data;     
    } 
    
}

