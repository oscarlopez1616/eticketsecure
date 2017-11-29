<?php

class Application_Model_UsuarioDireccion
{  
    private $_pais;
    private $_provincia;
    private $_poblacion;
    private $_cp;
    private $_direccion;
    private $_numero;
    private $_piso;
    private $_escalera;
    private $_telf_movil;
    private $_telf;
    
    public function __construct($pais=NULL,$provincia=NULL,$poblacion=NULL,$cp=NULL,$direccion=NULL,
            $numero=NULL,$piso=NULL,$escalera=NULL,$telf_movil=NULL,$telf=NULL){
        $this->_pais=$pais;
        $this->_provincia=$provincia;
        $this->_poblacion=$poblacion;
        $this->_cp=$cp;
        $this->_direccion=$direccion;
        $this->_numero=$numero;
        $this->_piso=$piso;
        $this->_escalera=$escalera;
        $this->_telf_movil=$telf_movil;
        $this->_telf=$telf;
    }

    public function getPais(){
        return $this->_pais;
    } 
    
    public function setPais($pais){
        $this->_pais= $pais;   
    }    
    
    public function getProvincia(){
        return $this->_provincia;
    } 
    
    public function setProvincia($provincia){
        $this->_provincia= $provincia;   
    } 
    
    public function getPoblacion(){
        return $this->_poblacion;
    } 

    public function setPoblacion($poblacion){
        $this->_poblacion= $poblacion;   
    }     
    
    public function getCp(){
        return $this->_cp;
    }
    
    public function setCp($cp){
        $this->_cp= $cp;  
    }  
    
    public function getDireccion(){
        return $this->_direccion;
    } 
    
    public function setDireccion($direccion){
        $this->_direccion= $direccion;    
    } 
    public function getNumero() {
        return $this->_numero;
    }

    public function setNumero($numero) {
        $this->_numero = $numero;
    }

    public function getPiso() {
        return $this->_piso;
    }

    public function setPiso($piso) {
        $this->_piso = $piso;
    }

    public function getEscalera() {
        return $this->_escalera;
    }

    public function setEscalera($escalera) {
        $this->_escalera = $escalera;
    }

    public function getTelfMovil() {
        return $this->_telf_movil;
    }

    public function setTelfMovil($telf_movil) {
        $this->_telf_movil = $telf_movil;
    }

    public function getTelf() {
        return $this->_telf;
    }

    public function setTelf($telf) {
        $this->_telf = $telf;
    }

    public function getAsXml(){
        $xml="\t<direccion>";   
        $xml.="\t\t<pais>".$this->_pais."</pais>\n";   
        $xml.="\t\t<provincia>".$this->_provincia."</provincia>\n";   
        $xml.="\t\t<poblacion>".$this->_poblacion."</poblacion>\n";   
        $xml.="\t\t<cp>".$this->_cp."</cp>\n";   
        $xml.="\t\t<direccion>".$this->_direccion."</direccion>\n";
        $xml.="\t\t<numero>".$this->_numero."</numero>\n";
        $xml.="\t\t<piso>".$this->_piso."</piso>\n";
        $xml.="\t\t<escalera>".$this->_escalera."</escalera>\n";
        $xml.="\t\t<telf_movil>".$this->_telf_movil."</telf_movil>\n";
        $xml.="\t\t<telf>".$this->_telf."</telf>\n";
        $xml.="\t</direccion>";   
        return $xml;
    } 
}

