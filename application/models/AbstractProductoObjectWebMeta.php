<?php

abstract class Application_Model_AbstractProductoObjectWebMeta
{
    protected $_id;
    protected $_id_categoria;
    protected $_pvp;
    protected $_codigo_referencia;
    protected $_iva;
    protected $_sef_arr;
    protected $_MetaTag;
    protected $_InterfaceProductoEticketSecure;

    public function __construct($id=NULL,$id_categoria=NULL,$pvp=NULL,$codigo_referencia=NULL,$iva=NULL,$MetaTag=NULL,$sef_arr=array()){
        $this->_InterfaceProductoEticketSecure = new Application_Model_InterfaceProductoEticketSecure();  
        $this->_id=(int)$id;
        $this->_id_categoria=(int)$id_categoria;
        $this->_pvp= (float)$pvp;
        $this->_codigo_referencia= (string)$codigo_referencia;
        $this->_iva= (float)$iva;
        $this->_MetaTag= $MetaTag;
        $this->_sef_arr= $sef_arr;
    }

    /**
     * Este metodo abstracto hara el load 
     * que implemente esta clase abstracta (en representacion_xml en eticketsecure2)
     * @param simple_xml_object $producto_simple_xml
     */
    abstract protected function loadAtributosRepresentacionXml($producto_simple_xml);
    
    
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
    abstract protected function addSpecificProducto($params_arr,$last_insert_id);   
    
    /**
    * @param array[] $params_arr  parametros que puede necesitar el metodo protected writeSpecificArtefacto($params_arr)
    * Este metodo abastracto
    * Permite implementar logicas adicionales de write()
    */  
    abstract protected function writeSpecificProducto($params_arr);   
    
    /**
    * @param array[] $params_arr parametros que puede necesitar el metodo protected deleteSpecificArtefacto($params_arr)
    * Este metodo abastracto
    * Permite implementar logicas adicionales de delete()
    */  
    abstract protected function deleteSpecificProducto($params_arr);   
    
    /**
     * 
     * @param int $id
     * @param int $id_categoria_artefacto
     */
    public function load($id){
        if(!isset($id))    throw new Exception('loadAbstractArtefactoMenu');
        
        $idioma_arr = $reg_webservice=Zend_Registry::get('idioma');
        $num_idiomas = $idioma_arr["count"];    
        $this->_id=$id;   
        $data = $this->_InterfaceProductoEticketSecure->getProductoById($id);
        $this->_iva =  $data["iva"];
        $this->_codigo_referencia = $data["codigo_referencia"];
        $data = $data["representacion_xml"];
        $producto_simple_xml= simplexml_load_string($data);
        
        $this->_pvp = (float)$producto_simple_xml->pvp;
        
        for($i=0;$i<$num_idiomas;$i++){              
            $idioma= $idioma_arr[$i];
            $meta_title_arr[$idioma] =(string)$producto_simple_xml->meta_tag->$idioma->meta_title;
            $meta_description_arr[$idioma] =(string)$producto_simple_xml->meta_tag->$idioma->meta_description;
            $meta_keywords_arr[$idioma] =(string)$producto_simple_xml->meta_tag->$idioma->meta_keywords;

        } 
        
        $this->_MetaTag= new Application_Model_Metatag($meta_title_arr, $meta_description_arr, $meta_keywords_arr);
        
        $this->_sef_arr= array();
        for($i=0;$i<$num_idiomas;$i++){              
            $idioma= $idioma_arr[$i];
            $this->_sef_arr[$idioma]= (string)$producto_simple_xml->sef->$idioma;
        } 
        
        $this->loadAtributosRepresentacionXml($producto_simple_xml); 
  
    }
    
    public function getId(){
        return $this->_id;
    }
    
    public function getIdCategoria(){
        return $this->_id_categoria;
    }
    
    protected function setIdCategoria($id_categoria){
        return $this->_id_categoria=$id_categoria;
    }
    
    public function getPvp() {
        return $this->_pvp;
    }

    public function setPvp($pvp) {
        $this->_pvp = $pvp;
    }

    public function getCodigoReferencia() {
        return $this->_codigo_referencia;
    }

    public function setCodigoReferencia($codigo_referencia) {
        $this->_codigo_referencia = $codigo_referencia;
    }

    public function getIva() {
        return $this->_iva;
    }

    public function setIva($iva) {
        $this->_iva = $iva;
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
        $string_xml="<producto>\n";
        
        $string_xml.=$this->getAsXmlAtributosRepresentacionXml();

        $string_xml.="\t<pvp>".$this->_pvp."</pvp>\n";
        $string_xml.="\t<codigo_referencia>".$this->_pvp."</codigo_referencia>\n";
        
        $string_xml.="\t<sef>\n";
        for($i=0;$i<$num_idiomas;$i++){              
            $idioma= $idioma_arr[$i];
            $string_xml.="\t\t<".$idioma.">";
            $string_xml.=htmlentities($this->_sef_arr[$idioma], ENT_XML1);
            $string_xml.="</".$idioma.">\n";
        }          
        $string_xml.="\t</sef>\n";
        
        $string_xml.= $this->_MetaTag->getAsXml();
        
        $string_xml.="</producto>"; 
        return $string_xml;
    }   
    
    /**
    * Añade un nuevo AbstractProductoMenu
    * @$params_arr parametros que puede necesitar el metodo protected deleteSpecificProducto($params_arr)
    * @throws Exception addAbstractProductoWebMetaException
    * @throws Exception InterfaceProductoException
    * @return mixed(integer,simple_xml_object)
    */  
    public function add($params_arr=NULL){
        if(!isset($this->_id_categoria)) throw new Exception("addAbstractProductoWebMetaException");
        $representacion_xml = $this->getAsXml();
        $data=$this->_InterfaceProductoEticketSecure->addProducto($this->_id_categoria,$representacion_xml, $this->_iva); 
        $this->_id=$data;
        $this->addSpecificProducto($params_arr,$this->_id);
        return $data;    
    }
    

    /**
    * Escribe la información del AbstractProductoMenu
    * @$params_arr parametros que puede necesitar el metodo protected writeSpecificProducto($params_arr)
    * @throws Exception writeAbstractProductoWebMetaException
    * @throws Exception InterfaceProductoException
    * @return mixed(integer,simple_xml_object)
    */  
    public function write($params_arr=NULL){
        if(!isset($this->_id)) throw new Exception("writeAbstractProductoWebMetaException");
        $this->writeSpecificProducto($params_arr);
        $representacion_xml = $this->getAsXml();
        $data = $this->_InterfaceProductoEticketSecure->setProductoById($this->_id, $representacion_xml, $this->_iva);
        return $data; 
    }  
    
    /**
    * Borra el AbstractProducto
    * @$params_arr parametros que puede necesitar el metodo protected deleteSpecificProducto($params_arr)
    * @throws Exception deleteAbstractProductoWebMetaException
    * @throws Exception InterfaceProductoException
    * @return mixed(integer,simple_xml_object)
    */      
    public function delete($params_arr=NULL){
        if(!isset($this->_id)) throw new Exception("deleteAbstractProductoWebMetaException");
        $this->deleteSpecificProducto($params_arr);
        $data=$this->_InterfaceArtefactoEticketSecure->deleteArtefactoById($this->_id);
        return $data;     
    } 
    
}
