<?php

class Application_Model_EspecialButacaSesion
{
    private $_id;
    private $_id_agrupador;
    private $_nombre_arr;
    private $_descripcion_arr;
    private $_pvp;
    private $_imagen_principal;    
    private $_catalogo_galeria_arr;
    /**
     *
     * @var array(0=>array("id_descuento" =>int,"id_agrupador"=>int),1=>array("id_descuento" =>int,"id_agrupador"=>int)) 
     */
    private $_id_descuento_arr;
    /*
     * @var array $this->_id_butaca_arr[] =  array("id_zona" => $id_zona, "id_butaca" => $id_butaca);
     */
    private $_id_butaca_arr;
    private $_flag_numerada;

    public function __construct($id=NULL,$id_agrupador=NULL,$nombre_arr=array(),$descripcion_arr=array(),$pvp=NULL,$id_descuento_arr=array(),$id_butaca_arr=array(),$imagen_principal=NULL,$catalogo_galeria_arr=array(), $flag_numerada=NULL){  
        $this->_id=$id;
        $this->_id_agrupador=$id_agrupador;
        $this->_nombre_arr=$nombre_arr;
        $this->_descripcion_arr=$descripcion_arr;
        $this->_pvp = $pvp;
        $this->_id_descuento_arr=$id_descuento_arr;     
        $this->_id_butaca_arr=$id_butaca_arr;
        $this->_imagen_principal= $imagen_principal;
        $this->_catalogo_galeria_arr=$catalogo_galeria_arr;
        $this->_flag_numerada=$flag_numerada;
    } 

    public function getId() {
        return $this->_id;
    }

    public function setId($id) {
        return $this->_id=$id;
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
    
    public function getNombre($idioma){
        return $this->_nombre_arr[$idioma];
    } 

    public function setNombre($idioma,$nombre){
        $this->_nombre_arr[$idioma]=$nombre;
    } 

    public function getDescripcion($idioma){
        return $this->_descripcion_arr[$idioma];
    } 

    public function setDescripcion($idioma,$nombre){
        $this->_descripcion_arr[$idioma]=$nombre;
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
    * Añade una referencia id_descuento_arr a EspecialButacaSesion
    */ 
    public function addRefIdDescuento($id_descuento,$id_agrupador){
        $this->_id_descuento_arr[] =  array("id_descuento"=>$id_descuento,"id_agrupador"=>$id_agrupador);
        array_unique($this->_id_descuento_arr);
    }     
    
    /**
    * Borra la referencia de id_descuento_arr identificada con id_descuento en EspecialButacaSesion
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
    
    
    public function getImagenPrincipal(){
        return $this->_imagen_principal;
    } 
    
    public function setImagenPrincipal($imagen_principal){
        $this->_imagen_principal=$imagen_principal;
    } 
    
    public function getCatalogoGaleriaArr(){
        return $this->_catalogo_galeria_arr;
    } 
    
    public function addImagenCatalogoGaleriaArr($ruta_imagen){
        $this->_catalogo_galeria_arr[]=$ruta_imagen;
    } 
    
    public function deleteImagenCatalogoGaleriaArrByIndex($index){
        unset($this->_catalogo_galeria_arr[$index]);
        $this->_catalogo_galeria_arr= array_values($this->_catalogo_galeria_arr);
    } 
    
    /*
    * @return array con los id_zona que estan en ese EspecialButacaSesion
    */
    public function getIdZonaArr(){
        $id_zona_arr = array();
        foreach($this->_id_butaca_arr as $butaca){
            $id_zona_arr[] = $butaca["id_zona"];
        }
        $id_zona_arr = array_unique($id_zona_arr);
        return $id_zona_arr;
    }
    
    public function getFlagNumerada() {
        return $this->_flag_numerada;
    }

    public function setFlagNumerada($flag_numerada) {
        $this->_flag_numerada = $flag_numerada;
    }

    /**
    * Retorna la Serializacion como XML
    * @return XML
    */  
    public function getAsXml()
    { 
        $idioma_arr = $reg_webservice=Zend_Registry::get('idioma');
        $num_idiomas = $idioma_arr["count"];
        
        $string_xml ="\t\t<especial_butaca_sesion id_especial_butaca_sesion=\"".$this->_id."\" id_agrupador=\"".$this->_id_agrupador."\">\n";
        $string_xml.="\t\t\t<flag_numerada>".$this->_flag_numerada."</flag_numerada>\n";
        $string_xml.="\t\t\t<nombre>\n";
        for($i=0;$i<$num_idiomas;$i++){              
            $idioma= $idioma_arr[$i];
            $string_xml.="\t\t\t\t<".$idioma.">";
            $string_xml.=htmlentities($this->_nombre_arr[$idioma], ENT_XML1);
            $string_xml.="</".$idioma.">\n";
        } 
        $string_xml.="\t\t\t</nombre>\n";
        
        $string_xml.="\t\t\t<descripcion>\n";
        for($i=0;$i<$num_idiomas;$i++){              
            $idioma= $idioma_arr[$i];
            $string_xml.="\t\t\t\t<".$idioma.">";
            $string_xml.=htmlentities($this->_descripcion_arr[$idioma], ENT_XML1);
            $string_xml.="</".$idioma.">\n";
        } 
        $string_xml.="\t\t\t</descripcion>\n";
        
        $string_xml.="\t\t\t<pvp>".$this->_pvp."</pvp>\n";
        
        $string_xml.="\t\t\t<descuentos>\n";
        foreach($this->_id_descuento_arr as $data_descuento ){ 
            $string_xml.="\t\t\t\t<descuento id_agrupador=\"".$data_descuento["id_agrupador"]."\">\n";
            $string_xml.="\t\t\t\t\t<id_descuento>".$data_descuento["id_descuento"]."</id_descuento>\n";
            $string_xml.="\t\t\t\t</descuento>\n";
        }
        $string_xml.="\t\t\t</descuentos>\n";  
        
        $string_xml.="\t\t\t<imagen_principal>".$this->_imagen_principal."</imagen_principal>\n";
        $string_xml.="\t\t\t<catalogo_galeria>\n";
        foreach($this->_catalogo_galeria_arr as $imagen){              
            $string_xml.="\t\t\t\t<imagen>";
            $string_xml.=$imagen;
            $string_xml.="</imagen>\n";
        }   
        $string_xml.="\t\t\t</catalogo_galeria>\n";
        $string_xml.="\t\t\t<id_butacas>\n";
        foreach($this->_id_butaca_arr as $butaca ){              
            $string_xml.="\t\t\t\t<butaca  id_zona=\"".$butaca["id_zona"]."\" id_butaca=\"".$butaca["id_butaca"]."\"  />\n";
        }
        $string_xml.="\t\t\t</id_butacas>\n";  
        
        $string_xml.="\t\t</especial_butaca_sesion>\n";
        return $string_xml;
    } 
      
}








