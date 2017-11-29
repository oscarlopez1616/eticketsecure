<?php

class Application_Model_Descuento extends Application_Model_AbstractArtefactoObject
{
    private $_nombre_arr;
    private $_descuento;
    private $_orden;
    private $_role_id;
    private $_flag_club_grup_teatreneu;

    public function __construct($id=NULL,$nombre_arr=array(),$descuento=NULL,$orden=NULL,$role_id=NULL, $_flag_club_grup_teatreneu=NULL){  
             
        $id_categoria_artefacto = 7;// es el $id_categoria_artefacto de Descuento
        parent::__construct($id,$id_categoria_artefacto);
        
        $this->_nombre_arr=$nombre_arr;
        $this->_descuento=(float)$descuento;   
        $this->_orden=(int)$orden;   
        $this->_role_id=(string)$role_id;   
        $this->_flag_club_grup_teatreneu=(int)$_flag_club_grup_teatreneu;   
    } 
    
    protected function loadAtributosRepresentacionXml($artefacto_simple_xml){
        $idioma_arr = $reg_webservice=Zend_Registry::get('idioma');
        $num_idiomas = $idioma_arr["count"];    
        
        $this->_nombre_arr= array();
        for($i=0;$i<$num_idiomas;$i++){              
            $idioma= $idioma_arr[$i];
            $this->_nombre_arr[$idioma]= (string)$artefacto_simple_xml->nombre->$idioma;
        } 
        
        $this->_descuento = (string)$artefacto_simple_xml->descuento;
        $this->_orden = (int)$artefacto_simple_xml->orden;
        $this->_role_id = (string)$artefacto_simple_xml->role_id;
        $this->_flag_club_grup_teatreneu = (int)$artefacto_simple_xml->flag_club_grup_teatreneu;
    } 
    
    public function getDescuento(){
        return $this->_descuento;
    }
    
    
    public function setDescuento($descuento){
        $this->_descuento= $descuento;
    }
    
    public function getOrden(){
        return $this->_orden;
    }
    
    
    public function setOrden($orden){
        $this->_orden= $orden;
    }
    
    public function getRoleId(){
        return $this->_role_id;
    }
    
    
    public function setRoleId($role_id){
        $this->_role_id= $role_id;
    }
     
    public function getNombreArr($idioma){
        return $this->_nombre_arr[$idioma];
    } 
    
    public function setNombreArr($nombre,$idioma){
        $this->_nombre_arr[$idioma]=$nombre;
    } 

    public function getFlagClubGrupTeatreneu() {
        return $this->_flag_club_grup_teatreneu;
    }

    public function setFlagClubGrupTeatreneu($flag_club_grup_teatreneu) {
        $this->_flag_club_grup_teatreneu = $flag_club_grup_teatreneu;
    }

    /**
    * Retorna la Serializacion como XML de un Objeto Descuento
    * @return XML
    */  
    protected function getAsXmlAtributosRepresentacionXml()
    {
        $idioma_arr = $reg_webservice=Zend_Registry::get('idioma');
        $num_idiomas = $idioma_arr["count"];        
        $string_xml ="\t\t\t<nombre>\n";
        for($i=0;$i<$num_idiomas;$i++){              
            $idioma= $idioma_arr[$i];
            $string_xml.="\t\t\t\t<".$idioma.">";
            $string_xml.=htmlentities($this->_nombre_arr[$idioma], ENT_XML1);
            $string_xml.="</".$idioma.">\n";
        }          
        $string_xml.="\t\t\t</nombre>\n";
        $string_xml.="\t\t\t<descuento>".$this->_descuento."</descuento>\n";
        $string_xml.="\t\t\t<orden>".$this->_orden."</orden>\n";
        $string_xml.="\t\t\t<role_id>".$this->_role_id."</role_id>\n";
        $string_xml.="\t\t\t<flag_club_grup_teatreneu>".$this->_flag_club_grup_teatreneu."</flag_club_grup_teatreneu>\n";
        
        return $string_xml;
    } 
    
    protected function addSpecificArtefacto($params_arr,$last_insert_id){}
    
    protected function writeSpecificArtefacto($params_arr){}  
    
    protected function deleteSpecificArtefacto($params_arr){}
 
}











