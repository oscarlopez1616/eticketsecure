<?php

class Application_Model_Restauracion extends Application_Model_AbstractProductoObjectWebMeta
{
    private $_nombre_arr;
    private $_date_time;
    private $_descripcion_servicio_arr;
    private $_detalles_arr;
    private $_more_arr;
    private $_nombre_local;
    private $_telf_local;
    private $_web_local;
    private $_pais_local;
    private $_provincia_local;
    private $_poblacion_local;
    private $_cp_local;
    private $_direccion_local;
    private $_pvp_anterior;
    private $_imagen_principal;    
    private $_catalogo_galeria_arr;
    /**
     *
     * @var /Application_Model_Coordenadas 
     */
    private $_Coordenadas;
    
    public function __construct($id_categoria,$id=NULL,$nombre_arr=array(),$date_time=NULL,$codigo_referencia=NULL,
            $pvp=NULL,$iva=NULL,$descripcion_servicio_arr=array(),$detalles_arr=array(),$more_arr=array(),
            $nombre_local=NULL,$telf_local=NULL,$web_local=NULL,$pais_local=NULL,$provincia_local=NULL,
            $poblacion_local=NULL,$cp_local=NULL,$direccion_local=NULL,$MetaTag=NULL,$sef_arr=array(), $pvp_anterior=NULL,
            $imagen_principal=NULL,$catalogo_galeria_arr=array(),$Coordenadas=NULL){  
        
        parent::__construct($id,$id_categoria,$pvp,$codigo_referencia,$iva,$MetaTag,$sef_arr);

        $this->_nombre_arr=$nombre_arr;
        $this->_date_time=$date_time;
        $this->_descripcion_servicio_arr=$descripcion_servicio_arr;
        $this->_detalles_arr=$detalles_arr;
        $this->_more_arr=$more_arr;
        $this->_nombre_local=$nombre_local;
        $this->_telf_local=$telf_local;
        $this->_web_local=$web_local;
        $this->_pais_local=$pais_local;
        $this->_provincia_local=$provincia_local;
        $this->_poblacion_local=$poblacion_local;
        $this->_cp_local=$cp_local;
        $this->_direccion_local=$direccion_local;
        $this->_pvp_anterior=$pvp_anterior;
        $this->_imagen_principal=$imagen_principal;    
        $this->_catalogo_galeria_arr=$catalogo_galeria_arr;
        if($Coordenadas==NULL){
            $this->_Coordenadas= new Application_Model_Coordenadas();
        }else{
            $this->_Coordenadas=$Coordenadas;
        }
    } 

    protected function loadAtributosRepresentacionXml($producto_simple_xml){
        $idioma_arr = $reg_webservice=Zend_Registry::get('idioma');
        $num_idiomas = $idioma_arr["count"];
        
        $InterfaceProducto = new Application_Model_InterfaceProductoEticketSecure();
        $data = $InterfaceProducto->getProductoById($this->_id);
        $this->_id_categoria = (int)$data['id_categoria'];
                
        $this->_nombre_arr= array();
        for($i=0;$i<$num_idiomas;$i++){              
            $idioma= $idioma_arr[$i];
            $this->_nombre_arr[$idioma]= (string)$producto_simple_xml->nombre->$idioma;
        } 
        
        $this->_descripcion_servicio_arr= array();
        for($i=0;$i<$num_idiomas;$i++){              
            $idioma= $idioma_arr[$i];
            $this->_descripcion_servicio_arr[$idioma]= (string)$producto_simple_xml->descripcion_servicio->$idioma;
        } 
        
        $this->_detalles_arr= array();
        for($i=0;$i<$num_idiomas;$i++){              
            $idioma= $idioma_arr[$i];
            $this->_detalles_arr[$idioma]= (string)$producto_simple_xml->detalles->$idioma;
        } 
        
        $this->_more_arr= array();
        for($i=0;$i<$num_idiomas;$i++){              
            $idioma= $idioma_arr[$i];
            $this->_more_arr[$idioma]= (string)$producto_simple_xml->more->$idioma;
        } 
        

        $this->_date_time = (string)$producto_simple_xml->date_time;

        $this->_nombre_local = (string)$producto_simple_xml->local->nombre;
        $this->_telf_local = (string)$producto_simple_xml->local->telf;
        $this->_web_local = (string)$producto_simple_xml->local->web;
        $this->_pais_local= (string)$producto_simple_xml->local->pais;
        $this->_provincia_local= (string)$producto_simple_xml->local->provincia;
        $this->_poblacion_local= (string)$producto_simple_xml->local->poblacion;
        $this->_cp_local= (string)$producto_simple_xml->local->cp;
        $this->_direccion_local= (string)$producto_simple_xml->local->direccion;
        $this->_pvp_anterior= (double)$producto_simple_xml->pvp_anterior;
        $this->_imagen_principal = (string)$producto_simple_xml->imagen_principal;

        $this->_catalogo_galeria_arr= array();
        if($producto_simple_xml->catalogo_galeria->imagen!=NULL){
            $imagen_arr = $producto_simple_xml->catalogo_galeria->imagen;
            foreach($imagen_arr as $imagen){              
                $this->_catalogo_galeria_arr[]=(string)$imagen;
            }
        }
        $latitud = (string)$producto_simple_xml->coordenadas->latitud;   
        $longitud = (string)$producto_simple_xml->coordenadas->longitud;  
        $this->_Coordenadas = new Application_Model_Coordenadas($latitud, $longitud);
    }
    
    public function getIdCategoria(){
        return $this->_id_categoria;
    } 
    
    /**
    * Setea $id_categoria
    * @throws Exception esPermitidoIdCategoria
    */ 
    public function setIdCategoria($id_categoria){    
        $this->_id_categoria = $id_categoria;
        if(!$this->esPermitidoIdCategoria($this->_id_categoria)){
            throw new Exception('esPermitidoIdCategoria');
        } 
    } 
    
    public function getNombre($idioma){
        return $this->_nombre_arr[$idioma];
    }
    
    public function setNombre($nombre,$idioma){
        $this->_nombre_arr[$idioma]=$nombre;
    } 
    
    public function getIdObra(){
        return $this->_id_obra;
    } 
    
    public function setIdObra($id_obra){
        return $this->_id_obra=$id_obra;
    } 

    public function getDateTime(){
        return $this->_date_time;
    }
    
    public function setDateTime($date_time){
        $this->_date_time=$date_time;
    } 
    
    public function getDescripcionServicioArr($idioma){
        return $this->_descripcion_servicio_arr[$idioma];
    }
    
    public function setDescripcionServicioArr($descripcion_servicio,$idioma){
        $this->_descripcion_servicio_arr[$idioma]=$descripcion_servicio;
    } 
    
    public function getDetallesArr($idioma){
        return $this->_detalles_arr[$idioma];
    }
    
    public function setDetallesArr($detalles,$idioma){
        $this->_detalles_arr[$idioma]=$detalles;
    } 
    
    public function getMoreArr($idioma){
        return $this->_more_arr[$idioma];
    }
    
    public function setMoreArr($more,$idioma){
        $this->_more_arr[$idioma]=$more;
    } 

    public function getNombreLocal(){
        return $this->_nombre_local;
    }
    
    public function setNombreLocal($nombre_local){
        $this->_nombre_local=$nombre_local;
    } 
    
    public function getTelfLocal(){
        return $this->_telf_local;
    }
    
    public function setTelfLocal($telf_local){
        $this->_telf_local=$telf_local;
    } 
    
    public function getWebLocal(){
        return $this->_web_local;
    }
    
    public function setWebLocal($web_local){
        $this->_web_local=$web_local;
    } 
    
    public function getPaisLocal(){
        return $this->_pais_local;
    }
    
    public function setPaisLocal($pais_local){
        $this->_pais_local=$pais_local;
    } 
    
    public function getProvinciaLocal(){
        return $this->_provincia_local;
    }
    
    public function setProvinciaLocal($provincia_local){
        $this->_provincia_local=$provincia_local;
    } 
    
    public function getPoblacionLocal(){
        return $this->_poblacion_local;
    }
    
    public function setPoblacionLocal($poblacion_local){
        $this->_poblacion_local=$poblacion_local;
    } 
    
    public function getCPLocal(){
        return $this->_cp_local;
    }
    
    public function setCPLocal($cp_local){
        $this->_cp_local=$cp_local;
    } 
    
    public function getDireccionLocal(){
        return $this->_direccion_local;
    }
    
    public function setDireccionLocal($direccion_local){
        $this->_direccion_local=$direccion_local;
    } 
    
    public function getPvpAnterior() {
        return $this->_pvp_anterior;
    }

    public function setPvpAnterior($pvp_anterior) {
        $this->_pvp_anterior = $pvp_anterior;
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
        $ruta = Zend_Registry::get('ruta');
        $ruta_imagen_server = $ruta['server'].$ruta['imagenes']['galeria']['restauracion']; 
        unlink($ruta_imagen_server.'/'.$this->_id.'/'.$this->_catalogo_galeria_arr[$index]);
        unset($this->_catalogo_galeria_arr[$index]);
    } 
    
    public function getCoordenadas() {
        return $this->_Coordenadas;
    }

    /**
     * 
     * @param $Coordenadas /Application_Model_Coordenadas 
     */
    public function setCoordenadas($Coordenadas) {
        $this->_Coordenadas = $Coordenadas;
    }
    
    /**
    * Retorna la Serializacion como XML de un Objeto Sesion
    * @return XML
    */  
    protected function getAsXmlAtributosRepresentacionXml()
    {
        $idioma_arr = $reg_webservice=Zend_Registry::get('idioma');
        $num_idiomas = $idioma_arr["count"];
        
        $string_xml ="\t<nombre>\n";
        for($i=0;$i<$num_idiomas;$i++){              
            $idioma= $idioma_arr[$i];
            $string_xml.="\t\t<".$idioma.">";
            $string_xml.=htmlentities($this->_nombre_arr[$idioma], ENT_XML1);
            $string_xml.="</".$idioma.">\n";
        }          
        $string_xml.="\t</nombre>\n";
        $string_xml.="\t<date_time>".htmlentities($this->_date_time, ENT_XML1)."</date_time>\n";
        $string_xml.="\t<pvp>".$this->_pvp."</pvp>\n";
        $string_xml.="\t<pvp_anterior>".$this->_pvp_anterior."</pvp_anterior>\n";
        $string_xml.="\t<descripcion_servicio>\n";
        for($i=0;$i<$num_idiomas;$i++){              
            $idioma= $idioma_arr[$i];
            $string_xml.="\t\t<".$idioma.">";
            $string_xml.=htmlentities($this->_descripcion_servicio_arr[$idioma], ENT_XML1);
            $string_xml.="</".$idioma.">\n";
        }          
        $string_xml.="\t</descripcion_servicio>\n";
        $string_xml.="\t<detalles>\n";
        for($i=0;$i<$num_idiomas;$i++){              
            $idioma= $idioma_arr[$i];
            $string_xml.="\t\t<".$idioma.">";
            $string_xml.=htmlentities($this->_detalles_arr[$idioma], ENT_XML1);
            $string_xml.="</".$idioma.">\n";
        }          
        $string_xml.="\t</detalles>\n";
        
        $string_xml.="\t<more>\n";
        for($i=0;$i<$num_idiomas;$i++){              
            $idioma= $idioma_arr[$i];
            $string_xml.="\t\t<".$idioma.">";
            $string_xml.=htmlentities($this->_more_arr[$idioma], ENT_XML1);
            $string_xml.="</".$idioma.">\n";
        }          
        $string_xml.="\t</more>\n";
        $string_xml.="\t<imagen_principal>".$this->_imagen_principal."</imagen_principal>\n";
        $string_xml.="\t<catalogo_galeria>\n";
        foreach($this->_catalogo_galeria_arr as $imagen){              
            $string_xml.="\t\t<imagen>";
            $string_xml.=$imagen;
            $string_xml.="</imagen>\n";
        }   
        $string_xml.="\t</catalogo_galeria>\n";
        
        $string_xml.="\t<local>\n";
        $string_xml.="\t\t<nombre_local>".htmlentities($this->_nombre_local, ENT_XML1)."</nombre_local>\n";
        $string_xml.="\t\t<telf_local>".htmlentities($this->_telf_local, ENT_XML1)."</telf_local>\n";
        $string_xml.="\t\t<web_local>".htmlentities($this->_web_local, ENT_XML1)."</web_local>\n";
        $string_xml.="\t\t<pais_local>".htmlentities($this->_pais_local, ENT_XML1)."</pais_local>\n";
        $string_xml.="\t\t<provincia_local>".htmlentities($this->_provincia_local, ENT_XML1)."</provincia_local>\n";
        $string_xml.="\t\t<poblacion_local>".htmlentities($this->_poblacion_local, ENT_XML1)."</poblacion_local>\n";
        $string_xml.="\t\t<cp_local>".htmlentities($this->_cp_local, ENT_XML1)."</cp_local>\n";
        $string_xml.="\t\t<direccion_local>".htmlentities($this->_direccion_local, ENT_XML1)."</direccion_local>\n";
        $string_xml.="\t</local>\n";
        $string_xml.=$this->_Coordenadas->getAsXml();
        return $string_xml;
    }

     /**
    * @param integer $last_insert_id el id donde se inserto el artefacto
    * @param array[] $params_arr parametros que puede necesitar el metodo protected addSpecificArtefacto($params_arr)
    * Este metodo abastracto
    * Permite implementar logicas adicionales de write()
    */  
    protected function addSpecificProducto($params_arr,$last_insert_id){}

    /**
    * @param array[] $params_arr  parametros que puede necesitar el metodo protected writeSpecificArtefacto($params_arr)
    * Este metodo abastracto
    * Permite implementar logicas adicionales de write()
    */  
    protected function writeSpecificProducto($params_arr){}   
    
    /**
    * @param array[] $params_arr parametros que puede necesitar el metodo protected deleteSpecificArtefacto($params_arr)
    * Este metodo abastracto
    * Permite implementar logicas adicionales de delete()
    */  
    protected function deleteSpecificProducto($params_arr){}
    
}






