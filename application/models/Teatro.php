<?php

class Application_Model_Teatro extends Application_Model_AbstractArtefactoObjectWebMeta
{
    private $_nombre; 
    private $_descripcion_arr;
    private $_imagen_principal;    
    private $_catalogo_galeria_arr;
    private $_video_principal; 
    private $_catalogo_video_arr;
    private $_id_sala_arr;
    
    public function __construct($id=NULL,$nombre=NULL,$descripcion_arr=array(),
            $imagen_principal=NULL,$catalogo_galeria_arr=array(),
            $video_principal=NULL,$catalogo_video_arr=array(),$id_sala_arr=array(),
            $MetaTag=NULL,$sef_arr=array()){

        $id_categoria_artefacto = 9;// es el $id_categoria_artefacto de Teatro
        parent::__construct($id,$id_categoria_artefacto,$MetaTag,$sef_arr);

        $this->_nombre=(string)$nombre;
        $this->_descripcion_arr=$descripcion_arr;
        $this->_imagen_principal=(string)$imagen_principal;    
        $this->_catalogo_galeria_arr=$catalogo_galeria_arr;
        $this->_video_principal=(string)$video_principal; 
        $this->_catalogo_video_arr=$catalogo_video_arr;
        $this->_id_sala_arr=$id_sala_arr;

    } 
    
    protected function loadAtributosRepresentacionXml($artefacto_simple_xml){
        $idioma_arr = $reg_webservice=Zend_Registry::get('idioma');
        $num_idiomas = $idioma_arr["count"];       

        $this->_nombre = (string)$artefacto_simple_xml->nombre;
        $this->_descripcion_arr= array();
        for($i=0;$i<$num_idiomas;$i++){              
            $idioma= $idioma_arr[$i];
            $this->_descripcion_arr[$idioma]= (string)$artefacto_simple_xml->descripcion->$idioma;
        }  

        $this->_imagen_principal = (string)$artefacto_simple_xml->imagen_principal;

        $this->_catalogo_galeria_arr= array();
        if($artefacto_simple_xml->catalogo_galeria->imagen!=NULL){
            $imagen_xml = $artefacto_simple_xml->catalogo_galeria->imagen;
            foreach($imagen_xml as $imagen){              
                $this->_catalogo_galeria_arr[]=(string)$imagen;
            }
        }
        $this->_video_principal = (string)$artefacto_simple_xml->video_principal;

        $this->_catalogo_video_arr= array();
        if($artefacto_simple_xml->catalogo_video->video!=NULL){
            $video_xml = $artefacto_simple_xml->catalogo_video->video;
            foreach($video_xml as $video){              
                $this->_catalogo_video_arr[]=$video;
            }
        }
        
        $this->_id_sala_arr= array();  
        foreach($artefacto_simple_xml->salas->id_sala as $id_sala){
            $this->_id_sala_arr[] = $id_sala;
        } 
    }

    public function getNombre(){
        return $this->_nombre;
    }
    
    public function setNombre($nombre){
        $this->_nombre=$nombre;
    }
  
    public function getDescripcionArr($idioma){
        return $this->_descripcion_arr[$idioma];
    } 
    
    public function setDescripcionArr($descripcion,$idioma){
        $this->_descripcion_arr[$idioma]=$descripcion;
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
        $temp = array();
        foreach($this->_catalogo_galeria_arr as $imagen){
            $temp[] = $imagen;
        }
        $this->_catalogo_galeria_arr = $temp;   
    } 
    
    public function getVideoPrincipal(){
        return $this->_video_principal;
    } 
    
    public function setVideoPrincipal($video_principal){
        $this->_video_principal=$video_principal;
    } 
    
    public function getCatalogoVideoArr(){
        return $this->_catalogo_video_arr;
    } 
    
    public function addVideoCatalogoVideoArr($ruta_video){
        $this->_catalogo_video_arr[]=$ruta_video;
    }
    
    public function deleteVideoCatalogoVideoArrByIndex($index){
        unset($this->_catalogo_video_arr[$index]);
    } 
  
    public function getIdZonaArr(){
        return $this->_id_sala_arr;
    } 
    
    /**
    * Añade una referencia Teatro en $id_sala
    */ 
    public function addIdZonaArr($id_sala){
        $this->_id_sala_arr[] =  $id_sala;
        array_unique($this->_id_sala_arr);
    }     
    
    /**
    * Borra la referencia de Teatro identificada con id_sala en Teatro
    * @throws Exception deleteIdSala
    */  
    public function deleteIdSala($id_sala){
        $index = -1;
        foreach ($this->_id_sala_arr as $index_temp => $id_sala_temp){
            if($id_sala == $id_sala_temp){
                $index= $index_temp;
                break;
            }
        }
        if ($index!=-1){
            unset ($this->_id_sala_arr[$index]);
        }else{
            throw new Exception('deleteIdSala');
        }
    }        
    
    
    /**
    * Retorna la Serializacion como XML de un Objeto Teatro
    * @return XML
    */  
    protected function getAsXmlAtributosRepresentacionXml()
    {
        $idioma_arr = $reg_webservice=Zend_Registry::get('idioma');
        $num_idiomas = $idioma_arr["count"];
        $string_xml ="\t<nombre>".htmlentities($this->_nombre, ENT_XML1)."</nombre>\n";
        
        $string_xml.="\t<salas>\n";
        foreach($this->_id_sala_arr as $id_sala){              
            $string_xml.="\t<id_sala>".$id_sala."</id_sala>\n";
        }   
        $string_xml.="\t</salas>\n";
        
        $string_xml.="\t<descripcion>\n";
        for($i=0;$i<$num_idiomas;$i++){              
            $idioma= $idioma_arr[$i];
            $string_xml.="\t\t<".$idioma.">";
            $string_xml.=htmlentities($this->_descripcion_arr[$idioma], ENT_XML1);
            $string_xml.="</".$idioma.">\n";
        }          
        $string_xml.="\t</descripcion>\n";
        $string_xml.="\t<imagen_principal>".$this->_imagen_principal."</imagen_principal>\n";
        $string_xml.="\t<catalogo_galeria>\n";
        foreach($this->_catalogo_galeria_arr as $imagen){              
            $string_xml.="\t\t<imagen>";
            $string_xml.=$imagen;
            $string_xml.="</imagen>\n";
        }   
        $string_xml.="\t</catalogo_galeria>\n";
        
        $string_xml.="\t<video_principal>".$this->_video_principal."</video_principal>\n";
        $string_xml.="\t<catalogo_video>\n";
        foreach($this->_catalogo_video_arr as $video){              
            $string_xml.="\t\t<video>";
            $string_xml.=$video;
            $string_xml.="</video>\n";
        }   
        $string_xml.="\t</catalogo_video>\n";

        return $string_xml;
    }       
    
    protected function addSpecificArtefacto($params_arr,$last_insert_id){}
    
    protected function writeSpecificArtefacto($params_arr){}  
    
    protected function deleteSpecificArtefacto($params_arr){}
    
    
}



