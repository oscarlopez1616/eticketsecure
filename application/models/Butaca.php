<?php

class Application_Model_Butaca
{
    private $_id_zona;
    private $_id;
    private $_num;
    private $_fila;
    private $_altura;
    private $_x_pos;
    private $_y_pos;
    /**
     * @param string $_estado valores permitidos "butaca_normal" , butaca_silla_ruedas" .
     * Nota: "no_disponible" noe s un estado no existen butacas con ese estado para ocupar menos memoria no 
     * las creamos pero lo tenemos en cuenta para dibujar en la interfaz y etc...
     */
    private $_tipo;
    private $_InterfaceArtefactoEticketSecure;
    
    /**
    * Para cargarlo de base de datos hace falta $id_zona y $id
    */  
    public function __construct($id_zona=NULL,$id=NULL,$num=NULL,$fila=NULL,$altura=NULL,$x_pos=NULL,$y_pos=NULL,$tipo=NULL){     
        
        $this->_InterfaceArtefactoEticketSecure = new Application_Model_InterfaceArtefactoEticketSecure();

        $this->_id_zona=(int)$id_zona;
        $this->_id=(int)$id;
        $this->_num=(string)$num;
        $this->_fila=(string)$fila;
        $this->_altura = (int)$altura;
        $this->_x_pos = (int)$x_pos;
        $this->_y_pos = (int)$y_pos;
        $this->_tipo = (int)$tipo;
    }
    
    
    public function load($id_zona,$id){
        if(!isset($id))    throw new Exception('load');
        $this->_id_zona=$id_zona;           
        $this->_id=$id;           
        $data = $this->_InterfaceArtefactoEticketSecure->getArtefactoById($id_zona);
        $flag_webservice = $reg_webservice=Zend_Registry::get('flag_web_service');
        if($flag_webservice){
            $data  = $data->getArtefactoById->representacion_xml;
        }else{
            $data = $data["representacion_xml"];
        }
        $artefacto_simple_xml= simplexml_load_string($data);
        
        $this->loadAtributosRepresentacionXml($artefacto_simple_xml); 
    }
    
    private function loadAtributosRepresentacionXml($artefacto_simple_xml){
        $butaca = null;
        foreach($artefacto_simple_xml->butacas->butaca as $butaca_temp){
            $id_xml = (int)$butaca_temp->attributes()["id_butaca"];
            if($this->_id==$id_xml) $butaca = $butaca_temp;
        }

        if($butaca == null)  throw new Exception('ButacaIdNoExiste');

        $this->_num=(string)$butaca->num;
        $this->_fila=(string)$butaca->fila;
        $this->_altura = (int)$butaca->altura;
        $this->_x_pos = (int)$butaca->x_pos;
        $this->_y_pos = (int)$butaca->y_pos;          
        $this->_tipo=(string)$butaca->tipo;       
    }
    
    
    public function getIdZona(){
        return $this->_id_zona;
    } 
    
    public function getId(){
        return $this->_id;
    } 

    public function getNum(){
        return $this->_num;
    } 
    
    public function setNum($num){
        return $this->_num=$num;
    } 

    public function getFila(){
        return $this->_fila;
    } 
    
    public function setFila($fila){
        return $this->_fila=$fila;
    }     
    
    public function getAltura(){
        return $this->_altura;
    } 
    
    public function setAltura($altura){
        return $this->_altura=$altura;
    } 
    
    public function getXPos(){
        return $this->_x_pos;
    } 
    
    public function setXPos($x_pos){
        return $this->_x_pos= $x_pos;
    } 
    
    public function getYPos(){
        return $this->_y_pos;
    } 
    
    public function setYPos($y_pos){
        return $this->_y_pos=$y_pos;
    } 
    
    public function getTipo(){
        return $this->_tipo;
    } 
    
    /**
    * Tipos permitidos:
    * 0: "butaca_normal"
    * 1: "butaca_silla_ruedas"
    */  
    public function setTipo($index_butaca_tipo){  
        $butaca_tipo_arr = $reg_webservice=Zend_Registry::get('butaca_tipo');
        $this->_tipo=$butaca_tipo_arr[$index_butaca_tipo];
    } 

    /**
    * Retorna la Serializacion como XML de un Objeto Butaca
    * @return XML
    */  
    public function getAsXml()
    {
        $string_xml="<butaca id_butaca=".$this->getId().">\n"; 
        $string_xml.="\t<num>".htmlentities($this->_num,ENT_XML1)."</num>\n"; 
        $string_xml.="\t<fila>".htmlentities($this->_fila,ENT_XML1)."</fila>\n"; 
        $string_xml.="\t<x_pos>".$this->_x_pos."</x_pos>\n"; 
        $string_xml.="\t<y_pos>".$this->_y_pos."</y_pos>\n";
        $string_xml.="\t<altura>".htmlentities($this->_altura,ENT_XML1)."</altura>\n";
        $string_xml.="\t<tipo>".htmlentities($this->_tipo,ENT_XML1)."</tipo>\n"; 
        $string_xml.="</butaca>"; 
        return $string_xml;
    }
    
    public function delete ($id_zona){
        $Zona = new Application_Model_Zona();
        $Zona->load($id_zona);
        $Zona->deleteRefButaca($this->_id);
        $Zona->write();
    } 
}




