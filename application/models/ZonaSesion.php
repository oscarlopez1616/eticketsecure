<?php

class Application_Model_ZonaSesion
{
    private $_id_zona;
    private $_id_agrupador;
    /**
     *
     * @var integer solo puede ser 0 o 1 
     */
    private $_flag_numerada;
    private $_pvp;
    /**
     *
     * @var array(0=>array("id_descuento" =>int,"id_agrupador"=>int),1=>array("id_descuento" =>int,"id_agrupador"=>int)) 
     */
    private $_id_descuento_arr;

    public function __construct($id_zona=NULL,$id_agrupador=NULL,$flag_numerada=NULL,$pvp=NULL,$id_descuento_arr=array()){  
        $this->_id_zona=$id_zona;
        $this->_id_agrupador=$id_agrupador;
        $this->_flag_numerada = $flag_numerada;
        $this->_pvp=$pvp;     
        $this->_id_descuento_arr=$id_descuento_arr;
    } 

    public function getIdZona(){
        return $this->_id_zona;
    }

    public function getIdAgrupador() {
        return $this->_id_agrupador;
    }
    
    public function getIdAgrupadorByIdDescuento($id_descuento){
        $index = $this->buscarIndexInIdDescuentoArr($id_descuento);
        $id_descuento_data = $this->_id_descuento_arr[$index];
        return $id_descuento_data["id_agrupador"];
    }

    public function setIdAgrupador($id_agrupador) {
        $this->_id_agrupador = $id_agrupador;
    }

    /**
     * 
     * @return array(0=>array("id_descuento" =>int,"id_agrupador"=>int),1=>array("id_descuento" =>int,"id_agrupador"=>int)) 
     */
    public function getIdDescuentoArr(){
        return $this->_id_descuento_arr;
    } 
    
    /**
    * Añade una referencia id_descuento_arr a ZonaSesion
    */ 
    public function addRefIdDescuento($id_descuento,$id_agrupador){
        $this->_id_descuento_arr[] =  array("id_descuento"=>$id_descuento,"id_agrupador"=>$id_agrupador);
        array_unique($this->_id_descuento_arr);
    }     
    
    /**
    * Borra la referencia de id_descuento_arr identificada con id_descuento en ZonaSesion
    * identicado con $id_zona atraves del interfaz
    * @throws Exception deleteRefIdDescuentoArr
    */  
    public function deleteRefIdDescuentoArr($id_descuento){
        $index = $this->buscarIndexInIdDescuentoArr($id_descuento);
        unset ($this->_id_descuento_arr[$index]);
        //reindexo para evitar que me queden posiciones vacias en el array
        $this->_id_descuento_arr = array_values($this->_id_descuento_arr);
    }  
    
    /**
    * @throws Exception buscarIndexInIdDescuentoArr
    */    
    private function buscarIndexInIdDescuentoArr($index){
        foreach ($this->_id_descuento_arr as $index_temp =>$id_descuento){
            if($index == $id_descuento["id_descuento"]){
                $index= $index_temp;
                break;
            }
        }
        if ($index!=-1){
            return $index;
        }else{
            throw new Exception('buscarIndexInIdDescuentoArr');
        }
    } 

    public function getFlagNumerada(){
        return $this->_flag_numerada;
    }
    
    public function setFlagNumeradaTrue() {
        $this->_flag_numerada = 1;
    }

    public function setFlagNumeradaFalse() {
        $this->_flag_numerada = 0;
    }
    
    public function getPvp() {
        return $this->_pvp;
    }

    public function setPvp($pvp) {
        $this->_pvp = $pvp;
    }

    /**
    * Retorna la Serializacion como XML de un Objeto ZonaSesion
    * @return XML
    */  
    public function getAsXml()
    { 
        $string_xml ="\t\t<zona_sesion id_zona=\"".$this->_id_zona."\" id_agrupador=\"".$this->_id_agrupador."\">\n";
        $string_xml.="\t\t\t<flag_numerada>".$this->_flag_numerada."</flag_numerada>\n";
        $string_xml.="\t\t\t<pvp>".$this->_pvp."</pvp>\n";
        $string_xml.="\t\t\t<descuentos>\n";
        foreach($this->_id_descuento_arr as $data_descuento ){ 
            $string_xml.="\t\t\t\t<descuento id_agrupador=\"".$data_descuento["id_agrupador"]."\">\n";
            $string_xml.="\t\t\t\t\t<id_descuento>".$data_descuento["id_descuento"]."</id_descuento>\n";
            $string_xml.="\t\t\t\t</descuento>\n";
        }
        $string_xml.="\t\t\t</descuentos>\n";      
        $string_xml.="\t\t</zona_sesion>\n";
        return $string_xml;
    } 
      
}








