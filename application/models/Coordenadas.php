<?php

class Application_Model_Coordenadas
{
    private $_latitud;
    private $_longitud;
    
    public function __construct($latitud=NULL,$longitud=NULL){
        $this->_latitud= $latitud;
        $this->_longitud= $longitud;
    }
    
    public function getLatitud() {
        return $this->_latitud;
    }

    public function setLatitud($latitud) {
        $this->_latitud = $latitud;
    }

    public function getLongitud() {
        return $this->_longitud;
    }

    public function setLongitud($longitud) {
        $this->_longitud = $longitud;
    }   
    
    public function getAsXml(){
        $string_xml ="\t<coordenadas>\n";

        $string_xml.="\t\t<latitud>";
        $string_xml.=htmlentities($this->_latitud, ENT_XML1);
        $string_xml.="</latitud>\n";

        $string_xml.="\t\t<longitud>";
        $string_xml.=htmlentities($this->_longitud, ENT_XML1);
        $string_xml.="</longitud>\n";
        
        $string_xml.="\t</coordenadas>\n";
        
        return $string_xml;
    }

}


