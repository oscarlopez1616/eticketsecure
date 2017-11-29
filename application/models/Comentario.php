<?php

class Application_Model_Comentario  extends Application_Model_AbstractArtefactoObject
{
    private $_nombre;
    private $_e_mail;
    private $_comentario;
    
    public function __construct($id=NULL,$nombre=NULL,$e_mail=NULL,$comentario=NULL){
        
        $id_categoria_artefacto = 6;// es el $id_categoria_artefacto de Comentario
        parent::__construct($id,$id_categoria_artefacto);

        $this->_id=(int)$id;
        $this->_nombre = (string)$nombre;
        $this->_e_mail = (string)$e_mail;
        $this->_comentario = (string)$comentario;

    } 

    protected function loadAtributosRepresentacionXml($artefacto_simple_xml){
        $this->_nombre = (string)$artefacto_simple_xml->nombre;
        $this->_e_mail = (string)$artefacto_simple_xml->e_mail;
        $this->_comentario = (string)$artefacto_simple_xml->comentario;    
    }    

    public function getNombre(){
        return $this->_nombre;
    }
    
    public function setNombre($nombre){
        $this->_nombre=$nombre;
    }
    
    public function getEmail(){
        return $this->_e_mail;
    }
    
    public function setEmail($e_mail){
        $this->_e_mail=$e_mail;
    } 
    
    public function getComentario(){
        return $this->_comentario;
    }
    
    public function setComentario($comentario){
        $this->_comentario=$comentario;
    } 

    protected function getAsXmlAtributosRepresentacionXml()
    {
        $string_xml="\t<nombre>".htmlentities($this->_nombre, ENT_XML1)."</nombre>\n";
        $string_xml.="\t<e_mail>".$this->_e_mail."</e_mail>\n";
        $string_xml.="\t<comentario>".htmlentities($this->_comentario, ENT_XML1)."</comentario>\n";
        return $string_xml;
    }   
    
    /**
    * @param integer $last_insert_id el id donde se inserto el artefacto
    * @param array[] $params_arr tiene que contener, $params_arr["id_obra"]
    * Este metodo abastracto
    * Permite implementar logicas adicionales de write()
    */  
    protected function addSpecificArtefacto($params_arr,$last_insert_id){
        $Obra = new Application_Model_Obra();
        $Obra->load($params_arr["id_obra"]);
        $Obra->addRefComentario($last_insert_id);
        $Obra->write(); 
    }
    
    protected function writeSpecificArtefacto($params_arr){} 
    
    /**
    * @param array[] $params_arr tiene que contener, $params_arr["id_obra"]
    * Este metodo abastracto
    * Permite implementar logicas adicionales de delete()
    */   
    protected function deleteSpecificArtefacto($params_arr){
        $Obra = new Application_Model_Obra();
        $Obra->load($params_arr["id_obra"]);
        $Obra->deleteRefComentario($this->_id);
        $Obra->write();  
    }
    
}





