<?php

class Application_Model_Regalo extends Application_Model_AbstractProductoObjectWebMeta
{
    private $_nombre_arr;
    private $_date_time;
    private $_descripcion_servicio_arr;
    private $_detalles_arr;
    private $_more_arr;
    private $_imagen_principal;    
    private $_catalogo_galeria_arr;
    
    public function __construct($id=NULL,$nombre_arr=array(),$date_time=NULL,$codigo_referencia=NULL,
            $pvp=NULL,$iva=NULL,$descripcion_servicio_arr=array(),$detalles_arr=array(),$more_arr=array(),
            $MetaTag=NULL,$sef_arr=array(), $pvp_anterior=NULL,$imagen_principal=NULL){  
        
        $id_categoria = 1;// es el $id_categoria de Regalo
        parent::__construct($id,$id_categoria,$pvp,$codigo_referencia,$iva,$MetaTag,$sef_arr);

        $this->_nombre_arr=$nombre_arr;
        $this->_date_time=$date_time;
        $this->_descripcion_servicio_arr=$descripcion_servicio_arr;
        $this->_detalles_arr=$detalles_arr;
        $this->_more_arr=$more_arr;
        $this->_imagen_principal=$imagen_principal;    
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
        $this->_imagen_principal = (string)$producto_simple_xml->imagen_principal;
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

    public function getDateTime(){
        return $this->_date_time;
    }
    
    public function setDateTime($date_time){
        $this->_date_time=$date_time;
    } 
    
    public function getDescripcionServicio($idioma){
        return $this->_descripcion_servicio_arr[$idioma];
    }
    
    public function setDescripcionServicio($descripcion_servicio,$idioma){
        $this->_descripcion_servicio_arr[$idioma]=$descripcion_servicio;
    } 
    
    public function getDetalles($idioma){
        return $this->_detalles_arr[$idioma];
    }
    
    public function setDetalles($detalles,$idioma){
        $this->_detalles_arr[$idioma]=$detalles;
    } 
    
    public function getMore($idioma){
        return $this->_more_arr[$idioma];
    }
    
    public function setMore($more,$idioma){
        $this->_more_arr[$idioma]=$more;
    } 

    
    public function getImagenPrincipal(){
        return $this->_imagen_principal;
    } 
    
    public function setImagenPrincipal($imagen_principal){
        $this->_imagen_principal=$imagen_principal;
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






