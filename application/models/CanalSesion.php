<?php

class Application_Model_CanalSesion
{
    private $_id_canal;
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
    /*
     * @var array $this->_id_butaca_arr[] =  array("id_zona" => $id_zona, "id_butaca" => $id_butaca);
     */
    private $_id_butaca_arr;

    public function __construct($id_canal=NULL,$id_agrupador=NULL,$flag_numerada=NULL,$pvp=NULL,$id_descuento_arr=array(),$id_butaca_arr=array()){  
        $this->_id_canal=$id_canal;
        $this->_id_agrupador=$id_agrupador;
        $this->_flag_numerada=$flag_numerada;
        $this->_pvp = $pvp;
        $this->_id_descuento_arr=$id_descuento_arr;     
        $this->_id_butaca_arr=$id_butaca_arr;
    } 

    public function getIdCanal() {
        return $this->_id_canal;
    }

    public function setIdCanal($id_canal) {
        $this->_id_canal = $id_canal;
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
     * 
     * @return array(0=>array("id_descuento" =>int,"id_agrupador"=>int),1=>array("id_descuento" =>int,"id_agrupador"=>int)) 
     */
    public function getIdDescuentoArr(){
        return $this->_id_descuento_arr;
    } 
    
    /**
    * Añade una referencia id_descuento_arr a CanalSesion
    */ 
    public function addRefIdDescuento($id_descuento,$id_agrupador){
        $this->_id_descuento_arr[] =  array("id_descuento"=>$id_descuento,"id_agrupador"=>$id_agrupador);
        array_unique($this->_id_descuento_arr);
    }     
    
    /**
    * Borra la referencia de id_descuento_arr identificada con id_descuento en CanalSesion
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
    
    
    /*
     *@return array[] $this->_id_butaca_arr[0..n] =  array("id_zona" => $id_zona, "id_butaca" => $id_butaca); 
     */
    public function getIdButacaArr(){
        return $this->_id_butaca_arr;
    } 
    
    /**
    * Añade una referencia id_butaca_arr
    */ 
    public function addRefIdButacaArr($id_zona,$id_butaca){
        $this->_id_butaca_arr[] =  array("id_zona" => $id_zona, "id_butaca" => $id_butaca);
        array_unique($this->_id_butaca_arr);
    }     
    
    /**
    * Borra la referencia de id_descuento_arr identificada con id_descuento
    * @throws Exception deleteRefIdDescuento
    */  
    public function deleteRefIdButacaArr($id_zona,$id_butaca){
        $index = -1;
        foreach ($this->_id_butaca_arr as $index_temp => $butaca_temp_arr){
            if($butaca_temp_arr["id_zona"] == $id_zona && $butaca_temp_arr["id_butaca"] == $id_butaca){
                $index= $index_temp;
                break;
            }
        }
        if ($index!=-1){
            unset ($this->_id_butaca_arr[$index]);
            //reindexo para evitar que me queden posiciones vacias en el array
            $this->_id_butaca_arr = array_values($this->_id_butaca_arr);
        }else{
            throw new Exception('deleteRefIdButacaArr');
        }
    } 
    
    
    
    /**
    * Retorna la Serializacion como XML 
    * @return XML
    */  
    public function getAsXml()
    {         
        $string_xml ="\t\t<canal_sesion id_canal=\"".$this->_id_canal."\" id_agrupador=\"".$this->_id_agrupador."\">\n";
        
        $string_xml.="\t\t\t<flag_numerada>".$this->_flag_numerada."</flag_numerada>\n";
        
        $string_xml.="\t\t\t<pvp>".$this->_pvp."</pvp>\n";
        
        $string_xml.="\t\t\t<descuentos>\n";
        foreach($this->_id_descuento_arr as $data_descuento ){ 
            $string_xml.="\t\t\t\t<descuento id_agrupador=\"".$data_descuento["id_agrupador"]."\">\n";
            $string_xml.="\t\t\t\t\t<id_descuento>".$data_descuento["id_descuento"]."</id_descuento>\n";
            $string_xml.="\t\t\t\t</descuento>\n";
        }
        $string_xml.="\t\t\t</descuentos>\n";      
        
        $string_xml.="\t\t\t<id_butacas>\n";
        foreach($this->_id_butaca_arr as $butaca ){              
            $string_xml.="\t\t\t\t<butaca  id_zona=\"".$butaca["id_zona"]."\" id_butaca=\"".$butaca["id_butaca"]."\"  />\n";
        }
        $string_xml.="\t\t\t</id_butacas>\n";  
        
        $string_xml.="\t\t</canal_sesion>\n";
        return $string_xml;
    } 
      
}








