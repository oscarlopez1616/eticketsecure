<?php

class Application_Model_Categoria
{
    private $_id;
    private $_publicado; 
    private $_id_parent; 
    private $_altura;
    private $_nombre_arr; 
    private $_descripcion_arr; 
    private $_descripcion2_arr; 
    private $_sef_arr;
    private $_MetaTag;
    private $_InterfaceCategoriaEticketSecure;

    public function __construct($id=NULL,$publicado=null,$id_parent=null,$nombre_arr=NULL,$descripcion_arr=NULL,
            $descripcion2_arr=NULL,$MetaTag=NULL,$sef_arr=array()){
        $this->_InterfaceCategoriaEticketSecure = new Application_Model_InterfaceCategoriaEticketSecure();
        $this->_id=(integer)$id; 
        $this->_publicado=(integer)$publicado; 
        $this->_id_parent=(integer)$id_parent; 
        $this->_altura= 0;
        $this->_nombre_arr=$nombre_arr;    
        $this->_descripcion_arr=$descripcion_arr;    
        $this->_descripcion2_arr=$descripcion2_arr; 
        $this->_MetaTag= $MetaTag;
        $this->_sef_arr= $sef_arr;
    }     
    
    public function load($id){
        if(!isset($id))    throw new Exception('loadCategoria');
        
        $idioma_arr = $reg_webservice=Zend_Registry::get('idioma');
        $num_idiomas = $idioma_arr["count"];   
        $this->_id=$id;
        $data=$this->_InterfaceCategoriaEticketSecure->getCategoriaById($this->_id);
        $this->_publicado=$data["publicado"]; 
        $this->_id_parent=$data["id_parent"];
        $this->_altura= $data["altura"];
        $data = $data["representacion_xml"];

        $temp_xml= simplexml_load_string($data);
        $this->_nombre_arr= array();
        for($i=0;$i<$num_idiomas;$i++){              
            $idioma= $idioma_arr[$i];
            $this->_nombre_arr[$idioma]= (string)$temp_xml->nombre->$idioma;
        }  
        $this->_descripcion_arr= array();
        for($i=0;$i<$num_idiomas;$i++){              
            $idioma= $idioma_arr[$i];
            $this->_descripcion_arr[$idioma]= (string)$temp_xml->descripcion->$idioma;
        }  
        $this->_descripcion2_arr= array();
        for($i=0;$i<$num_idiomas;$i++){              
            $idioma= $idioma_arr[$i];
            $this->_descripcion2_arr[$idioma]= (string)$temp_xml->descripcion2->$idioma;
        }
        
        for($i=0;$i<$num_idiomas;$i++){              
            $idioma= $idioma_arr[$i];
            $meta_title_arr[$idioma] =(string)$temp_xml->meta_tag->$idioma->meta_title;
            $meta_description_arr[$idioma] =(string)$temp_xml->meta_tag->$idioma->meta_description;
            $meta_keywords_arr[$idioma] =(string)$temp_xml->meta_tag->$idioma->meta_keywords;

        } 
        
        $this->_MetaTag= new Application_Model_Metatag($meta_title_arr, $meta_description_arr, $meta_keywords_arr);
        
        $this->_sef_arr= array();
        for($i=0;$i<$num_idiomas;$i++){              
            $idioma= $idioma_arr[$i];
            $this->_sef_arr[$idioma]= (string)$temp_xml->sef->$idioma;
        } 
       
    }
    
    public function getId(){
        return $this->_id;
    } 
    
    public function getAltura(){
        return $this->_altura;
    } 
    
    public function setAltura($altura){
        $this->_altura=$altura;
    } 
    
    public function getPublicado(){
        return $this->_publicado;
    } 
    
    public function setPublicado($publicado){
        $this->_publicado=$publicado;
    } 
    
    public function getIdParent(){
        return $this->_publicado;
    } 
    
    public function setIdParent($id_parent){
        $this->_id_parent=$id_parent;
    } 

    public function getNombreArr($idioma){
        return $this->_nombre_arr[$idioma];
    } 
    
    public function setNombreArr($nombre,$idioma){
        $this->_nombre_arr[$idioma]=$nombre;
    } 
    
    public function getDescripcionArr($idioma){
        return $this->_descripcion_arr[$idioma];
    } 
    
    public function setDescripcionArr($descripcion,$idioma){
        $this->_descripcion_arr[$idioma]=$descripcion;
    } 
    
    public function getDescripcion2Arr($idioma){
        return $this->_descripcion2_arr[$idioma];
    } 
    
    public function setDescripcion2Arr($descripcion2,$idioma){
        $this->_descripcion2_arr[$idioma]=$descripcion2;
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
    
    public function getAsXml(){
        $idioma_arr = $reg_webservice=Zend_Registry::get('idioma');
        $num_idiomas = $idioma_arr["count"]; 
        $xml="<categoria>\n"; 
        $xml.="\t<nombre>\n";
        for($i=0;$i<$num_idiomas;$i++){              
        $idioma= $idioma_arr[$i];
            $xml.="\t\t<".$idioma.">"; 
            $xml.=$this->_nombre_arr[$idioma]; 
            $xml.="</".$idioma.">\n";    
        } 
 
        $xml.="\t</nombre>\n";  
        $xml.="\t<descripcion>\n";
        for($i=0;$i<$num_idiomas;$i++){              
            $idioma= $idioma_arr[$i];
            $xml.="\t\t<".$idioma.">"; 
            $xml.=$this->_descripcion_arr[$idioma]; 
            $xml.="</".$idioma.">\n"; 
        } 
        $xml.="\t</descripcion>\n";  
        $xml.="\t<descripcion2>\n";
        for($i=0;$i<$num_idiomas;$i++){              
            $idioma= $idioma_arr[$i];
            $xml.="\t\t<".$idioma.">"; 
            $xml.=$this->_descripcion2_arr[$idioma]; 
            $xml.="</".$idioma.">\n"; 
        } 
        $xml.="\t</descripcion2>\n"; 

        $string_xml.="\t<sef>\n";
        for($i=0;$i<$num_idiomas;$i++){              
            $idioma= $idioma_arr[$i];
            $string_xml.="\t\t<".$idioma.">";
            $string_xml.=htmlentities($this->_sef_arr[$idioma], ENT_XML1);
            $string_xml.="</".$idioma.">\n";
        }          
        $string_xml.="\t</sef>\n";
        
        $string_xml.= $this->_MetaTag->getAsXml();
        
        $xml.="</categoria>";
        return $xml;
    }

    public function write(){
        $id=$this->_id;
        $representacion_xml=$this->getAsXml();
        $publicado=$this->_publicado;
        $altura=$this->_altura;
        $id_parent=$this->_id_parent;
        $data=$this->_InterfaceCategoriaEticketSecure->setCategoriaById($id, $representacion_xml, $publicado, $altura, $id_parent);
        return $data; 
    } 
    
    public function add(){
        $representacion_xml=$this->getAsXml();
        $publicado=$this->_publicado;
        $id_parent=$this->_id_parent;
        $data=$this->_InterfaceCategoriaEticketSecure->addCategoria($representacion_xml, $publicado,$id_parent);
        $this->_id=$data;
        return $data; 
    } 
    
    public function delete(){
        $id=$this->_id;
        $data=$this->_InterfaceCategoriaEticketSecure->deleteCategoria($id);
        return $data;     
    } 
}


