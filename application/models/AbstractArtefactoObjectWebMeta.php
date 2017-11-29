<?php

abstract class Application_Model_AbstractArtefactoObjectWebMeta
{
    protected $_id;
    protected $_id_categoria_artefacto;
    protected $_sef_arr;
    protected $_MetaTag;
    protected $_InterfaceArtefactoEticketSecure;

    
    public function __construct($id=NULL,$id_categoria_artefacto=NULL,$MetaTag=NULL,$sef_arr=array()){
        $this->_InterfaceArtefactoEticketSecure = new Application_Model_InterfaceArtefactoEticketSecure();  
        $this->_id=(int)$id;
        $this->_id_categoria_artefacto=(int)$id_categoria_artefacto;
        $this->_MetaTag= $MetaTag;
        $this->_sef_arr= $sef_arr;
    }

    /**
     * Este metodo abstracto hara el load 
     * que implemente esta clase abstracta (en representacion_xml en eticketsecure2)
     * @param simple_xml_object $artefacto_simple_xml
     */
    abstract protected function loadAtributosRepresentacionXml($artefacto_simple_xml);
    
    
    /**
    * Este metodo abastracto retorna
    * la Serializacion como XML de los atributos de la clase que implemente la clase abstracta
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
     */
    public function load($id){
        if(!isset($id))    throw new Exception('loadAbstractArtefactoObjectWebMeta');
        
        $idioma_arr = $reg_webservice=Zend_Registry::get('idioma');
        $num_idiomas = $idioma_arr["count"];    
        $this->_id=$id;   
        $data = $this->_InterfaceArtefactoEticketSecure->getArtefactoById($id); 
        $data = $data["representacion_xml"];
        $artefacto_simple_xml= simplexml_load_string($data);
        
        for($i=0;$i<$num_idiomas;$i++){              
            $idioma= $idioma_arr[$i];
            $meta_title_arr[$idioma] =(string)$artefacto_simple_xml->meta_tag->$idioma->meta_title;
            $meta_description_arr[$idioma] =(string)$artefacto_simple_xml->meta_tag->$idioma->meta_description;
            $meta_keywords_arr[$idioma] =(string)$artefacto_simple_xml->meta_tag->$idioma->meta_keywords;

        } 
        
        $this->_MetaTag= new Application_Model_Metatag($meta_title_arr, $meta_description_arr, $meta_keywords_arr);
        
        $this->_sef_arr= array();
        for($i=0;$i<$num_idiomas;$i++){              
            $idioma= $idioma_arr[$i];
            $this->_sef_arr[$idioma]= (string)$artefacto_simple_xml->sef->$idioma;
        } 
        
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
    
    public function getSefArr($idioma) {
        return $this->_sef_arr[$idioma];
    }

    public function setSefArr($sef,$idioma) {
        $this->_sef_arr[$idioma] = $sef;
    }
    
    /**
    * Retorna el Objeto MetaTag
    * @return Application_Model_Metatag
    */  
    public function getMetaTag(){
        return $this->_MetaTag;
    } 

    
    public function setMetaTag($MetaTag){
        $this->_MetaTag = $MetaTag;    
    }     
    
    /**
    * la Serializacion como XML
    * @return string
    */  
    public function getAsXml(){
        $idioma_arr = $reg_webservice=Zend_Registry::get('idioma');
        $num_idiomas = $idioma_arr["count"];
        $string_xml="<artefacto>\n";
        
        $string_xml.=$this->getAsXmlAtributosRepresentacionXml();

        $string_xml.="\t<sef>\n";
        for($i=0;$i<$num_idiomas;$i++){              
            $idioma= $idioma_arr[$i];
            $string_xml.="\t\t<".$idioma.">";
            $string_xml.=htmlentities($this->_sef_arr[$idioma], ENT_XML1);
            $string_xml.="</".$idioma.">\n";
        }          
        $string_xml.="\t</sef>\n";
        
        $string_xml.= $this->_MetaTag->getAsXml();
        
        $string_xml.="</artefacto>"; 
        return $string_xml;
    }   
    
    /**
    * Añade un nuevo AbstractArtefactoMenu
    * @$params_arr parametros que puede necesitar el metodo protected addSpecificArtefacto($params_arr)
    * @throws Exception addAbstractObjectWebMetaException
    * @throws Exception InterfaceArtefactoException
    * @return mixed(integer,simple_xml_object)
    */  
    public function add($params_arr=NULL){
        if(!isset($this->_id_categoria_artefacto)) throw new Exception("addAbstractObjectWebMetaException");
        $representacion_xml = $this->getAsXml();
        $data=$this->_InterfaceArtefactoEticketSecure->addArtefacto($this->_id_categoria_artefacto,$representacion_xml); 
        $this->_id=$data;
        $this->addSpecificArtefacto($params_arr,$this->_id);
        return $data;     
    }
    

    /**
    * Escribe la información del AbstractArtefactoMenu
    * @$params_arr parametros que puede necesitar el metodo protected writeSpecificArtefacto($params_arr)
    * @throws Exception writeAbstractObjectWebMetaException
    * @throws Exception InterfaceArtefactoException
    * @return mixed(integer,simple_xml_object)
    */  
    public function write($params_arr=NULL){
        if(!isset($this->_id)) throw new Exception("writeAbstractObjectWebMetaException");
        $this->writeSpecificArtefacto($params_arr);
        $representacion_xml = $this->getAsXml();
        $data = $this->_InterfaceArtefactoEticketSecure->setArtefactoById($this->_id, $representacion_xml, false);
        return $data;   
    }  
    
    /**
    * Borra el AbstractArtefacto
    * @$params_arr parametros que puede necesitar el metodo protected deleteSpecificArtefacto($params_arr)
    * @throws Exception InterfaceArtefactoException
    * @throws Exception deleteAbstractArtefactoObjectWebMetaException
    * @return mixed(integer,simple_xml_object)
    */      
    public function delete($params_arr=NULL){
        if(!isset($this->_id)) throw new Exception("deleteAbstractArtefactoObjectWebMetaException");
        $this->deleteSpecificArtefacto($params_arr);
        $data=$this->_InterfaceArtefactoEticketSecure->deleteArtefactoById($this->_id);
        return $data;     
    } 
    
}
