<?php

class Application_Model_Actor extends Application_Model_AbstractArtefactoObjectWebMeta
{
    private $_nombre;
    private $_descripcion_arr;
    private $_descripcion_larga_arr;
    private $_imagen_principal;    
    private $_catalogo_galeria_arr;
    private $_video_principal; 
    private $_catalogo_video_arr;
    private $_Twitter;
    private $_id_tags_arr;
    
    public function __construct($id=NULL,$nombre=NULL,$descripcion_arr=array(),$descripcion_larga_arr=array(),
            $nombre=NULL,$imagen_principal=NULL,$catalogo_galeria_arr=array(),
            $video_principal=NULL,$catalogo_video_arr=array(),$Twitter=NULL,$id_tags_arr=array(),
            $MetaTag=NULL,$sef_arr=array()){
        
        $id_categoria_artefacto = 4;// es el $id_categoria_artefacto de Actor
        parent::__construct($id,$id_categoria_artefacto,$MetaTag,$sef_arr);
        
        $this->_nombre=$nombre;
        $this->_descripcion_arr=$descripcion_arr;
        $this->_descripcion_larga_arr=$descripcion_larga_arr;
        $this->_imagen_principal=$imagen_principal;    
        $this->_catalogo_galeria_arr=$catalogo_galeria_arr;
        $this->_video_principal=$video_principal; 
        $this->_catalogo_video_arr=$catalogo_video_arr;
        $this->_Twitter=$Twitter;
        $this->_id_tags_arr=$id_tags_arr;

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

        $this->_descripcion_larga_arr= array();
        for($i=0;$i<$num_idiomas;$i++){              
            $idioma= $idioma_arr[$i];
            $this->_descripcion_larga_arr[$idioma]= (string)$artefacto_simple_xml->descripcion_larga->$idioma;
        }  

        $this->_imagen_principal = (string)$artefacto_simple_xml->imagen_principal;

        $this->_catalogo_galeria_arr= array();
        if($artefacto_simple_xml->catalogo_galeria->imagen!=NULL){
            $imagen_arr = $artefacto_simple_xml->catalogo_galeria->imagen;
            foreach($imagen_arr as $imagen){              
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
        $usario = (string)$artefacto_simple_xml->twitter->usario;
        $password = (string)$artefacto_simple_xml->twitter->password;
        $hashtags_arr = array();
        if($artefacto_simple_xml->twitter->hashtags->hashtag!=NULL){
            $hashtags_artefacto_xml = $artefacto_simple_xml->twitter->hashtags->hashtag;
            foreach($hashtags_artefacto_xml as $hashtag){              
                $hashtags_arr[]=$hashtag;
            }
        }
        $this->_Twitter = new Application_Model_Twitter($usario, $password, $hashtags_arr);


        $this->_id_tags_arr= array();
        if($artefacto_simple_xml->tags->id_tag!=NULL){
            $id_tags_xml = $artefacto_simple_xml->tags->id_tag;
            foreach($id_tags_xml as $id_tag){ 
                $this->_id_tags_arr[] = $id_tag;
            }
        }  
    }

    public function getId(){
        return $this->_id;
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
    
    public function getDescripcionLargaArr($idioma){
        return $this->_descripcion_larga_arr[$idioma];
    } 
    
    public function setDescripcionLargaArr($descripcion_larga,$idioma){
        $this->_descripcion_larga_arr[$idioma]=$descripcion_larga;
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
        $ruta_imagen_server = $ruta['server'].$ruta['imagenes']['galeria']['actor']; 
        unlink($ruta_imagen_server.'/'.$this->_id.'/'.$this->_catalogo_galeria_arr[$index]);
        unset($this->_catalogo_galeria_arr[$index]);
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
    
    public function getTwitter(){
        return $this->_Twitter;
    } 
    
    public function setTwitter($Twitter){
        $this->_Twitter=$Twitter;
    }   
    
    public function getIdTagsArr(){
        return $this->_id_tags_arr;
    } 
    
    /**
    * Añade una referencia a $id_tag en Obra
    */ 
    public function addRefTag($id_tag){
        $this->_id_tags_arr[] = $id_tag;   
        array_unique($this->_id_tags_arr);   
    }     
    
    /**
    * Bora una referencia a $id_tag en Obra
    * @throws Exception deleteRefTag
    */  
    public function deleteRefTag($id_tag){
        $index = -1;
        foreach ($this->_id_tags_arr as $index_temp => $id_tag_temp){
            if($id_tag_temp == $id_tag){
                $index= $index_temp;
                break;
            }
        }
        if ($index!=-1){
            unset ($this->_id_tags_arr[$index]);
            //reindexo para evitar que me queden posiciones vacias en el array
            $this->_id_tags_arr = array_values($this->_id_tags_arr);
        }else{
            throw new Exception('deleteRefTag');
        }
    } 
    
    /**
    * Borra todo el contenido de $_id_tags_arr
    */ 
    public function inicializaIdTagsArr(){
        $this->_id_tags_arr= array();
  
    } 
    
    /**
    * Retorna la Serializacion como XML de un Objeto Obra
    * @return XML
    */  
    protected function getAsXmlAtributosRepresentacionXml()
    {
        $idioma_arr = $reg_webservice=Zend_Registry::get('idioma');
        $num_idiomas = $idioma_arr["count"];
        $string_xml ="\t<nombre>".htmlentities($this->_nombre, ENT_XML1)."</nombre>\n";
        $string_xml.="\t<descripcion>\n";
        for($i=0;$i<$num_idiomas;$i++){              
            $idioma= $idioma_arr[$i];
            $string_xml.="\t\t<".$idioma.">";
            $string_xml.=htmlentities($this->_descripcion_arr[$idioma], ENT_XML1);
            $string_xml.="</".$idioma.">\n";
        }          
        $string_xml.="\t</descripcion>\n";
        $string_xml.="\t<descripcion_larga>\n";
        for($i=0;$i<$num_idiomas;$i++){              
            $idioma= $idioma_arr[$i];
            $string_xml.="\t\t<".$idioma.">";
            $string_xml.=htmlentities($this->_descripcion_larga_arr[$idioma], ENT_XML1);
            $string_xml.="</".$idioma.">\n";
        }          
        $string_xml.="\t</descripcion_larga>\n";
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
        $string_xml.=$this->_Twitter->getAsXml();
  
        $string_xml.="\t<tags>";
        foreach($this->_id_tags_arr as $id_tag){              
            $string_xml.="\t\t<id_tag>".$id_tag."</id_tag>\n";
        }   
        $string_xml.="\t</tags>\n";
        
        return $string_xml;
    }    
    
    protected function addSpecificArtefacto($params_arr,$last_insert_id){}
    
    protected function writeSpecificArtefacto($params_arr){}  
    
    protected function deleteSpecificArtefacto($params_arr){}
    
}





