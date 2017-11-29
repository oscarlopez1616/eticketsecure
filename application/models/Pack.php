<?php

class Application_Model_Pack extends Application_Model_AbstractProductoObjectWebMeta
{
    private $_nombre_arr;
    private $_date_time_inicio;
    private $_date_time_fin;
    private $_descripcion_servicio_arr;
    private $_detalles_arr;
    private $_more_arr;
    private $_id_sesion_arr;
    private $_id_restauracion_arr;
    
    public function __construct($id=NULL,$nombre_arr=array(),$date_time_inicio=NULL,$date_time_fin=NULL,$codigo_referencia=NULL,$pvp=NULL,$iva=NULL,$detalles_arr=array(),$more_arr=array(),
        $id_sesion_arr=array(),$id_restauracion_arr=array(),$MetaTag=NULL,$sef_arr=array()){  

        $id_categoria = 4;// es el $id_categoria de Pack
        parent::__construct($id,$id_categoria,$pvp,$codigo_referencia,$iva,$MetaTag,$sef_arr);

        $this->_nombre_arr=$nombre_arr;
        $this->_date_time_inicio=(string)$date_time_inicio;
        $this->_date_time_fin=(string)$date_time_fin;
        $this->_detalles_arr=$detalles_arr;
        $this->_more_arr=$more_arr;
        $this->_id_sesion_arr=$id_sesion_arr;
        $this->_id_restauracion_arr=$id_restauracion_arr;
    } 

    protected function loadAtributosRepresentacionXml($producto_simple_xml){
        $idioma_arr = $reg_webservice=Zend_Registry::get('idioma');
        $num_idiomas = $idioma_arr["count"];  

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
        

        $this->_date_time_inicio = (string)$producto_simple_xml->date_time_inicio;
        $this->_date_time_fin = (string)$producto_simple_xml->_date_time_fin;
        
        $this->_id_sesion_arr= array();
        if($producto_simple_xml->sesiones->id_sesion!=NULL){
            $id_sesiones_xml = $producto_simple_xml->sesiones->id_sesion;
            foreach($id_sesiones_xml as $id_sesion){ 
                $this->_id_sesion_arr[] = $id_sesion;
            }
        }
        
        $this->_id_restauracion_arr= array();
        if($producto_simple_xml->restauraciones->id_restauracion!=NULL){
            $id_restauraciones_xml = $producto_simple_xml->restauraciones->id_restauracion;
            foreach($id_restauraciones_xml as $id_restauracion){ 
                $this->_id_restauracion_arr[] = $id_restauracion;
            }
        }
        
    }
    
    public function getNombreArr($idioma){
        return $this->_nombre_arr[$idioma];
    } 
    
    public function setNombreArr($nombre,$idioma){
        $this->_nombre_arr[$idioma]=$nombre;
    } 
    
    public function getDetallesArr($idioma){
        return $this->_detalles_arr[$idioma];
    } 
    
    public function setDetallesArr($detalles,$idioma){
        $this->_detalles_arr[$idioma]=$detalles;
    } 
    
    public function getDescripcionServicioArr($idioma){
        return $this->_descripcion_servicio_arr[$idioma];
    } 
    
    public function setDescripcionServicioArr($descripcion_servicio,$idioma){
        $this->_descripcion_servicio_arr[$idioma]=$descripcion_servicio;
    } 
    
    public function getMoreArr($idioma){
        return $this->_more_arr[$idioma];
    } 
    
    public function setMoreArr($more,$idioma){
        $this->_more_arr[$idioma]=$more;
    } 
    
    public function getIdObra(){
        return $this->_id_obra;
    } 
    
    public function setIdObra($id_obra){
        return $this->_id_obra=$id_obra;
    } 

    public function getDateTimeInicio(){
        return $this->_date_time_inicio;
    }
    
    public function setDateTimeInicio($date_time_inicio){
        $this->_date_time_inicio=$date_time_inicio;
    } 

    public function getDateTimeFin(){
        return $this->_date_time_fin;
    }
    
    public function setDateTimeFin($date_time_fin){
        $this->_date_time_fin=$date_time_fin;
    } 
    
    public function getIdSesionArr(){
        return $this->_id_sesion_arr;
    }    
        
    /**
    * Añade una referencia en Pack a la Sesion identificada con $id_sesion 
    */ 
    public function addSesion($id_sesion){
        $this->_id_sesion_arr[] = $id_sesion;
        array_unique($this->_id_sesion_arr);
  
    }  
    
    /**
    * Borra todo el contenido de $_id_sesion_arr
    */ 
    public function inicializaIdSesionArr(){
        $this->_id_sesion_arr = array();
  
    }  
    
    /**
    * Borra la referencia  de id_sesion_arr indexado con $id_sesion  en Pack
    * @throws Exception buscarIdSesionInIdSesionArr
    */  
    public function deleteZonaSesion($id_sesion){
        $index = $this->buscarIdSesionInIdSesionArr($id_sesion);
        unset ($this->_id_sesion_arr[$index]);
        //reindexo para evitar que me queden posiciones vacias en el array
        $this->_id_sesion_arr = array_values($this->_id_sesion_arr);
    } 
    
    
    /**
    * Encuenta el index para la referencia  a $id_sesion  en el array id_sesion_arr
    * Si lo encuentra devuelve el index si no lanza una excepcion
    * @throws Exception buscarIdSesionInIdSesionArr
    */    
    private function buscarIdSesionInIdSesionArr($id_sesion){
        $index = -1;
        foreach ($this->_id_sesion_arr as $index_temp => $id_sesion_current){
            if($id_sesion == $id_sesion_current){
                $index= $index_temp;
                break;
            }
        }
        if ($index!=-1){
            return $index;
        }else{
            throw new Exception('buscarIdSesionInIdSesionArr');
        }
    } 
    
   
    public function getIdRestauracionArr(){
        return $this->_id_restauracion_arr;
    }    
        
    /**
    * Añade una referencia en Pack a la Restauracion identificada con $id_restauracion 
    */ 
    public function addRestauracion($id_restauracion){
        $this->_id_restauracion_arr[] = $id_restauracion;
        array_unique($this->_id_restauracion_arr);
  
    }   
    
    /**
    * Borra todo el contenido de $_id_restauracion_arr
    */ 
    public function inicializaIdRestauracionArr(){
        $this->_id_restauracion_arr = array();
  
    }   
    
    /**
    * Borra la referencia  de _id_restauracion_arr indexado con $id_restauracion  en Pack
    * @throws Exception buscarIdRestauracionInIdRestauracionArr
    */  
    public function deleteRestauracion($id_restauracion){
        $index = $this->buscarIdRestauracionInIdRestauracionArr($id_restauracion);
        unset ($this->_id_restauracion_arr[$index]);
        //reindexo para evitar que me queden posiciones vacias en el array
        $this->_id_restauracion_arr = array_values($this->_id_restauracion_arr);
    } 
    
    
    /**
    * Encuenta el index para la referencia  a $id_restauracion  en el array _id_restauracion_arr
    * Si lo encuentra devuelve el index si no lanza una excepcion
    * @throws Exception buscarIdRestauracionInIdRestauracionArr
    */    
    private function buscarIdRestauracionInIdRestauracionArr($id_restauracion){
        $index = -1;
        foreach ($this->_id_restauracion_arr as $index_temp => $id_restauracion_current){
            if($id_restauracion == $id_restauracion_current){
                $index= $index_temp;
                break;
            }
        }
        if ($index!=-1){
            return $index;
        }else{
            throw new Exception('buscarIdRestauracionInIdRestauracionArr');
        }
    } 

       
    /**
    * Retorna la Serializacion como XML de un Objeto Pack
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
        $string_xml.="\t<date_time_inicio>".htmlentities($this->_date_time_inicio, ENT_XML1)."</date_time_inicio>\n";
        $string_xml.="\t<date_time_fin>".htmlentities($this->_date_time_fin, ENT_XML1)."</date_time_fin>\n";
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
        
        $string_xml.="\t<sesiones>\n";
        foreach($this->_id_sesion_arr as $id_sesion){
            $string_xml.= "\t\t<id_sesion>".$id_sesion."</id_sesion>\n";
        }   
        $string_xml.="\t</sesiones>\n";
        
        $string_xml.="\t<restauraciones>\n";
        foreach($this->_id_restauracion_arr as $id_restauracion){
            $string_xml.= "\t\t<id_restauracion>".$id_restauracion."</id_restauracion>\n";
        }   
        $string_xml.="\t</restauraciones>\n";

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






