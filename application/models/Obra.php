<?php

class Application_Model_Obra extends Application_Model_AbstractArtefactoObjectWebMeta
{
    private $_nombre;
    private $_pvp_base;
    private $_descripcion_arr;
    private $_descripcion_larga_arr;
    private $_duracion;
    private $_idioma;
    private $_publico;
    private $_imagen_miniatura;
    private $_imagen_principal;
    private $_catalogo_galeria_arr;
    private $_video_principal; 
    private $_catalogo_video_arr;
    private $_Twitter;
    private $_id_comentarios_arr;
    private $_id_actores_arr;
    private $_id_sesiones_arr;
    private $_id_tags_arr;
    
    public function __construct($id=NULL,$nombre=NULL,$pvp_base=NULL,$descripcion_arr=array(),$descripcion_larga_arr=array(),
            $nombre=NULL,$duracion=NULL,$idioma=NULL,$publico=NULL,$imagen_miniatura=NULL,$imagen_principal=NULL,$catalogo_galeria_arr=array(),
            $video_principal=NULL,$catalogo_video_arr=array(),$Twitter=NULL,$id_comentarios_arr=array(),
            $id_actores_arr=array(),$id_sesiones_arr=array(),$id_tags_arr=array(),$MetaTag=NULL,$sef_arr=array())
    {        
        $id_categoria_artefacto = 3;// es el $id_categoria_artefacto de Obra
        parent::__construct($id,$id_categoria_artefacto,$MetaTag,$sef_arr);

        $this->_nombre=(string)$nombre;
        $this->_pvp_base = (float)$pvp_base;
        $this->_descripcion_arr=$descripcion_arr;
        $this->_descripcion_larga_arr=$descripcion_larga_arr;
        $this->_duracion=(float)$duracion;
        $this->_idioma=(string)$idioma;
        $this->_publico=(string)$publico;
        $this->_imagen_miniatura=(string)$imagen_miniatura;    
        $this->_imagen_principal=(string)$imagen_principal;    
        $this->_catalogo_galeria_arr=$catalogo_galeria_arr;
        $this->_video_principal=(string)$video_principal; 
        $this->_catalogo_video_arr=$catalogo_video_arr;
        

        $this->_Twitter=$Twitter;    

        
        $this->_id_comentarios_arr=$id_comentarios_arr;
        $this->_id_actores_arr=$id_actores_arr;
        $this->_id_sesiones_arr=$id_sesiones_arr;
        $this->_id_tags_arr=$id_tags_arr;

    } 

    protected function loadAtributosRepresentacionXml($artefacto_simple_xml){
        $idioma_arr = $reg_webservice=Zend_Registry::get('idioma');
        $num_idiomas = $idioma_arr["count"];    

        $this->_nombre = (string)$artefacto_simple_xml->nombre;
        $this->_pvp_base = (string)$artefacto_simple_xml->pvp_base;

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

        $this->_duracion = (string)$artefacto_simple_xml->duracion;
        $this->_idioma = (string)$artefacto_simple_xml->idioma;
        $this->_publico = (string)$artefacto_simple_xml->publico;

        $this->_imagen_miniatura = (string)$artefacto_simple_xml->imagen_miniatura;
        $this->_imagen_principal = (string)$artefacto_simple_xml->imagen_principal;

        $this->_catalogo_galeria_arr= array();
        if($artefacto_simple_xml->catalogo_galeria->imagen!=NULL){
            $imagen_xml = $artefacto_simple_xml->catalogo_galeria->imagen;
            foreach($imagen_xml as $imagen){              
                $this->_catalogo_galeria_arr[]=$imagen;
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
                $hashtags_arr[]=(string)$hashtag;
            }
        }
        $this->_Twitter = new Application_Model_Twitter($usario, $password, $hashtags_arr);

        $this->_id_comentarios_arr= array();
        if($artefacto_simple_xml->comentarios->id_comentario!=NULL){
            $id_comentarios_xml = $artefacto_simple_xml->comentarios->id_comentario;
            foreach($id_comentarios_xml as $id_comentario){ 
                $this->_id_comentarios_arr[] = (int)$id_comentario;
            }
        }    

        $this->_id_actores_arr= array();
        if($artefacto_simple_xml->actores->id_actor!=NULL){
            
            $id_actores_xml = $artefacto_simple_xml->actores->id_actor;
            foreach($id_actores_xml as $id_actores){ 
                $this->_id_actores_arr[] = (int)$id_actores;
            }

        }

        $this->_id_sesiones_arr= array();
        if($artefacto_simple_xml->sesiones->id_sesion!=NULL){
            $id_sesiones_xml = $artefacto_simple_xml->sesiones->id_sesion;
            foreach($id_sesiones_xml as $id_sesion){ 
                $this->_id_sesiones_arr[] = (int)$id_sesion;
            }
        } 

        $this->_id_tags_arr= array();
        if($artefacto_simple_xml->tags->id_tag!=NULL){
            $id_tags_xml = $artefacto_simple_xml->tags->id_tag;
            foreach($id_tags_xml as $id_tag){ 
                $this->_id_tags_arr[] = (int)$id_tag;
            }
        } 
    }    

    public function getNombre(){
        return $this->_nombre;
    }
    
    public function setNombre($nombre){
        $this->_nombre=$nombre;
    } 

    public function getPvpBase(){
        return $this->_pvp_base;
    }
    
    public function setPvpBase($pvp_base){
        $this->_pvp_base=$pvp_base;
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

    public function getDuracion(){
        return $this->_duracion;
    }
    
    public function setDuracion($duracion){
        $this->_duracion=$duracion;
    } 
    
    /**
    * Devuelve el parametro idioma de la obra. Nota:Esto es el idioma en la cual se hace la obra no el idioma de la web
    */  
    public function getIdioma(){
        return $this->_idioma;
    }
    
    /**
    * setea el parametro idioma de la obra. Nota:Esto es el idioma en la cual se hace la obra no el idioma de la web
    */  
    public function setIdioma($idioma){
        $this->_idioma=$idioma;
    } 
    
    public function getPublico(){
        return $this->_publico;
    }
    
    public function setPublico($publico){
        $this->_publico=$publico;
    } 
    
    public function getImagenMiniatura(){
        return $this->_imagen_miniatura;
    } 
    
    public function setImagenMiniatura($imagen_miniatura){
        $this->_imagen_miniatura=$imagen_miniatura;
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
        $ruta_imagen_server = $ruta['server'].$ruta['imagenes']['galeria']['obra']; 
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

    
    public function getIdComentariosArr(){
        return $this->_id_comentarios_arr;
    } 
    
    /**
    * Añade una referencia a $id_comentario en Obra
    */ 
    public function addRefComentario($id_comentario){
        $this->_id_comentarios_arr[] = $id_comentario; 
        array_unique($this->_id_comentarios_arr);
    }     
    
    /**
    * Bora una referencia a $id_comentario en Obra 
    * Artefacto
    * @throws Exception deleteRefComentario
    */  
    public function deleteRefComentario($id_comentario){
        $index = -1;
        foreach ($this->_id_comentarios_arr as $index_temp => $id_comentario_temp){
            if($id_comentario_temp == $id_comentario){
                $index= $index_temp;
                break;
            }
        }
        if ($index!=-1){
            unset ($this->_id_comentarios_arr[$index]);
            //reindexo para evitar que me queden posiciones vacias en el array
            $this->_id_comentarios_arr = array_values($this->_id_comentarios_arr);
        }else{
            throw new Exception('deleteRefComentario');
        }
    } 
    
    
    public function getIdActoresArr(){
        return $this->_id_actores_arr;
    } 
    
    /**
    * Añade una referencia a $id_actor en Obra
    */ 
    public function addRefActor($id_actor){
        $this->_id_actores_arr[] = $id_actor;
        array_unique($this->_id_actores_arr);
    }     
    
    /**
    * Bora una referencia a $id_actor en Obra
    * @throws Exception deleteRefActor
    */  
    public function deleteRefActor($id_actor){
        $index = -1;
        foreach ($this->_id_actores_arr as $index_temp => $id_actor_temp){
            if($id_actor_temp == $id_actor){
                $index= $index_temp;
                break;
            }
        }
        if ($index!=-1){
            unset ($this->_id_actores_arr[$index]);
            //reindexo para evitar que me queden posiciones vacias en el array
            $this->_id_actores_arr = array_values($this->_id_actores_arr);
        }else{
            throw new Exception('deleteRefActor');
        }
    } 
    
    /**
    * Borra todo el contenido de $_id_actores_arr
    */ 
    public function inicializaIdActoresArr(){
        $this->_id_actores_arr = array();
  
    }  
    
    public function getIdSesionesArr(){
        return $this->_id_sesiones_arr;
    } 
    
    /**
    * Añade una referencia a $id_sesion en Obra
    */ 
    public function addRefSesion($id_sesion){
        $this->_id_sesiones_arr[] = $id_sesion;
        array_unique($this->_id_sesiones_arr);
    }     
    
    /**
    * Bora una referencia a $id_sesion en Obra y Borra el Objeto Sesion identicado con $id_sesion atraves del interfaz
    * Artefacto
    * @throws Exception deleteRefSesion
    * @throws Exception InterfaceArtefactoException
    */  
    public function deleteRefSesion($id_sesion){
        $index = -1;
        foreach ($this->_id_sesiones_arr as $index_temp => $id_sesion_temp){
            if($id_sesion_temp == $id_sesion){
                $index= $index_temp;
                break;
            }
        }
        if ($index!=-1){
            unset ($this->_id_sesiones_arr[$index]);
            //reindexo para evitar que me queden posiciones vacias en el array
            
            $this->_id_sesiones_arr = array_values($this->_id_sesiones_arr);
        }else{
            throw new Exception('deleteRefSesion');
        }
    } 
    
    /**
    * Borra todo el contenido de $_id_sesiones_arr
    */ 
    public function inicializaIdSesionesArr(){
        $this->_id_sesiones_arr= array();
  
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
     * Retorna todas los Objetos de Sesion para esta obra desde $fechaInicial ordenados por fecha ASC o DESC 
     * segun parametro $orden
     * @param string $paginador_elementos cuantos elementos devuelve
     * @param string $paginador_inicio apartir de que elemento devuelve
     * @param string $orden ASC o DESC por defecto ASC
     * @param string $fechaInicial si le pasamos NULL por defecto este parametro es la fecha actual del servidor
     * @return array[Sesion]
     */
    public function getSesionArrOrderFecha($paginador_elementos=NULL,$paginador_inicio=NULL,$orden="ASC", $fechaInicial=NULL){
        if (is_null($fechaInicial)) $fechaInicial = date("Y-m-d H:i:s");
        if ($orden!=="ASC" && $orden!=="DESC") throw new Exception('getSesionArrOrderFecha');
        $Sesion_arr = array();
        $i=0;
        foreach($this->_id_sesiones_arr as $id_sesion){
            $Sesion = new Application_Model_Sesion();
            $Sesion->load($id_sesion); 
            $datetime_sesion = new DateTime($Sesion->getDateTime());
            $datetime_inicial = new DateTime($fechaInicial);
 
            if($datetime_sesion > $datetime_inicial){
                $Sesion_arr[$i] = $Sesion;  
                $j=$i;
                $i++;
                $continua = TRUE;
                while ($j>0 && $continua) {
                    $date_time_nueva    =  new DateTime($Sesion_arr[$j]->getDateTime());
                    $date_time_anterior =  new DateTime($Sesion_arr[($j-1)]->getDateTime());
                    if($orden=="ASC"){
                        if ($date_time_nueva<$date_time_anterior){
                            $temp_arr = $Sesion_arr[($j-1)];
                            $Sesion_arr[($j-1)] = $Sesion_arr[$j];
                            $Sesion_arr[$j] = $temp_arr;
                        }else{
                            $continua = TRUE;
                        }
                    }else if($orden=="DESC"){
                        if ($date_time_nueva>$date_time_anterior){
                            $temp_arr = $Sesion_arr[($j-1)];
                            $Sesion_arr[($j-1)] = $Sesion_arr[$j];
                            $Sesion_arr[$j] = $temp_arr;
                        }else{
                            $continua = TRUE;
                        }
                    }
                    $j--;
                }
            }
        }
        //EL ARRAY ORDENADO
        if($paginador_elementos!= NULL && $paginador_inicio!= NULL){
            $Sesion_arr = array_slice($Sesion_arr, $paginador_inicio, $paginador_elementos);
        }
        return $Sesion_arr;
    }
    
    /**
    * Retorna la Serializacion como XML de un Objeto Obra
    * @return XML
    */  
    protected function getAsXmlAtributosRepresentacionXml()
    {
        $idioma_arr = $reg_webservice=Zend_Registry::get('idioma');
        $num_idiomas = $idioma_arr["count"];
        $string_xml="\t<nombre>".htmlentities($this->_nombre, ENT_XML1)."</nombre>\n";
        $string_xml.="\t<pvp_base>".$this->_pvp_base."</pvp_base>\n";
        
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
        $string_xml.="\t<duracion>".htmlentities($this->_duracion, ENT_XML1)."</duracion>\n";
        $string_xml.="\t<idioma>".htmlentities($this->_idioma, ENT_XML1)."</idioma>\n";
        $string_xml.="\t<publico>".htmlentities($this->_publico, ENT_XML1)."</publico>\n";
        $string_xml.="\t<imagen_miniatura>".$this->_imagen_miniatura."</imagen_miniatura>\n";
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
        $string_xml.="\t<comentarios>\n";
        foreach($this->_id_comentarios_arr as $id_comentario){              
            $string_xml.="\t\t<id_comentario>".$id_comentario."</id_comentario>\n";
        }   
        $string_xml.="\t</comentarios>\n";
        
        $string_xml.="\t<actores>\n";
        foreach($this->_id_actores_arr as $id_actor){              
            $string_xml.="\t\t<id_actor>".$id_actor."</id_actor>\n";
        }   
        $string_xml.="\t</actores>\n";
        
        $string_xml.="\t<sesiones>\n";
        foreach($this->_id_sesiones_arr as $id_sesion){              
            $string_xml.="\t\t<id_sesion>".$id_sesion."</id_sesion>\n";
        }   
        $string_xml.="\t</sesiones>\n";
        
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





