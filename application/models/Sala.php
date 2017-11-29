<?php

class Application_Model_Sala extends Application_Model_AbstractArtefactoObjectWebMeta
{
    private $_nombre; 
    private $_nombre_abreviado; 
    private $_svg_2d;
    private $_svg_3d;
    private $_capacidad;
    private $_x_length;
    private $_y_length;
    private $_descripcion_arr;
    private $_imagen_principal;    
    private $_catalogo_galeria_arr;
    private $_video_principal; 
    private $_catalogo_video_arr;
    private $_id_teatro;
    private $_id_zona_arr;
    /**
     *
     * @var /Application_Model_Coordenadas 
     */
    private $_Coordenadas;
    
    public function __construct($id=NULL,$nombre=NULL,$nombre_abreviado=NULL,$capacidad=NULL,$x_length=NULL,
            $y_length=NULL,$descripcion_arr=array(),$imagen_principal=NULL,$catalogo_galeria_arr=array(),
            $video_principal=NULL,$catalogo_video_arr=array(),$svg_2d=NULL,$svg_3d=NULL,$id_teatro=NULL
            ,$id_zona_arr=array(), $MetaTag=NULL,$sef_arr=array(),$Coordenadas=NULL){

        $id_categoria_artefacto = 1;// es el $id_categoria_artefacto de Sala
        parent::__construct($id,$id_categoria_artefacto,$MetaTag,$sef_arr);

        $this->_nombre=$nombre;
        $this->_nombre_abreviado=$nombre_abreviado;
        $this->_svg_2d=$svg_2d;
        $this->_svg_3d=$svg_3d;
        $this->_capacidad=$capacidad;
        $this->_x_length =$x_length;
        $this->_y_length =$y_length;
        $this->_descripcion_arr=$descripcion_arr;
        $this->_imagen_principal=$imagen_principal;    
        $this->_catalogo_galeria_arr=$catalogo_galeria_arr;
        $this->_video_principal=$video_principal; 
        $this->_catalogo_video_arr=$catalogo_video_arr;
        $this->_id_teatro=$id_teatro;
        $this->_id_zona_arr=$id_zona_arr;
        if($Coordenadas==NULL){
            $this->_Coordenadas= new Application_Model_Coordenadas();
        }else{
            $this->_Coordenadas=$Coordenadas;
        }
    } 
    
    protected function loadAtributosRepresentacionXml($artefacto_simple_xml){
        $idioma_arr = $reg_webservice=Zend_Registry::get('idioma');
        $num_idiomas = $idioma_arr["count"];       

        $this->_nombre = (string)$artefacto_simple_xml->nombre;
        $this->_nombre_abreviado = (string)$artefacto_simple_xml->nombre_abreviado;
        $this->_svg_2d = (string)$artefacto_simple_xml->svg_2d;
        $this->_svg_3d = (string)$artefacto_simple_xml->svg_3d;
        $this->_capacidad=(int)$artefacto_simple_xml->capacidad;
        $this->_x_length = (int)$artefacto_simple_xml->x_length;
        $this->_y_length = (int)$artefacto_simple_xml->y_length;
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
        
        $this->_id_teatro= (int)$artefacto_simple_xml->id_teatro;
        
        $this->_id_zona_arr= array();  
        foreach($artefacto_simple_xml->zonas->id_zona as $id_zona){
            $this->_id_zona_arr[] = $id_zona;
        } 
        $latitud = (string)$artefacto_simple_xml->coordenadas->latitud;   
        $longitud = (string)$artefacto_simple_xml->coordenadas->longitud;  
        $this->_Coordenadas = new Application_Model_Coordenadas($latitud, $longitud);  
    }

    public function getNombre(){
        return $this->_nombre;
    }
    
    public function setNombre($nombre){
        $this->_nombre=$nombre;
    }
    
    public function getNombreAbreviado() {
        return $this->_nombre_abreviado;
    }

    public function setNombreAbreviado($nombre_abreviado) {
        $this->_nombre_abreviado = $nombre_abreviado;
    }

    public function getSvg2D(){
        return $this->_svg_2d;
    } 
    
    public function setSvg2D($svg_2d){
        $this->_svg_2d=$svg_2d;
    } 
     
    public function getSvg3D(){
        return $this->_svg_3d;
    } 
    
    public function setSvg3D($svg_3d){
        $this->_svg_3d=$svg_3d;
    } 

    public function getCapacidad(){
        return $this->_capacidad;
    }
    
    public function setCapacidad($capacidad){
        $this->_capacidad=$capacidad;
    } 

    public function getXLength(){
        return $this->_x_length;
    } 
    
    public function setXLength($x_length){
        return $this->_x_length = $x_length;
    } 

    
    public function getYLength(){
        return $this->_y_length;
    } 
    
    public function setYLength($y_length){
        return $this->_y_length = $y_length;
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
    
    public function getIdTeatro() {
        return $this->_id_teatro;
    }

    public function setIdTeatro($id_teatro) {
        $this->_id_teatro = $id_teatro;
    }

        
    public function getIdZonaArr(){
        return $this->_id_zona_arr;
    } 
    
    /**
    * Añade una referencia Zona en Sala
    */ 
    public function addIdZonaArr($id_zona){
        $this->_id_zona_arr[] =  $id_zona;
        array_unique($this->_id_zona_arr);
    }     
    
    /**
    * Borra la referencia de Zona identificada con id_zona en Sala
    * @throws Exception deleteIdZona
    */  
    public function deleteIdZona($id_zona){
        $index = -1;
        foreach ($this->_id_zona_arr as $index_temp => $id_zona_temp){
            if($id_zona == $id_zona_temp){
                $index= $index_temp;
                break;
            }
        }
        if ($index!=-1){
            unset ($this->_id_zona_arr[$index]);
        }else{
            throw new Exception('deleteIdZona');
        }
    }    
    
    /**
    * Retorna Todas los objetos Zona de esa Sala
    * @return Application_Model_Zona[]
    */  
    public function getZonasArr(){
        $Zonas_arr = array();
        foreach($this->_id_zona_arr as $id_zona_arr){
            $Zona = new Application_Model_Zona();
            $Zona->load($id_zona_arr);
            $Zonas_arr[] = $Zona;
        }
        return $Zonas_arr;
    }
    
    public function getSalaAsTableHtml(){
        $input_arr = $this->getSalaAsArray();
        $table = '<table>';
        $table.= '<tbody>';
        for ($x=0; $x < count($input_arr); $x++)
        {
            $table.= "\n\t<tr>";
            for ($y=0; $y<count($input_arr[$x]); $y++)
            {
                if($input_arr[$x][$y]["flag"] == "Butaca"){
                    $Zona = $input_arr[$x][$y]["Zona"];
                    $Butaca = $input_arr[$x][$y]["Butaca"];
                    $table.= "\n\t\t".'<td class="zona-'.$Zona->getId().' tipo-'.$Butaca->getTipo().' altura-'.$Butaca->getAltura().'">fila:'.$Butaca->getFila().'<br/>'.$Butaca->getNum().'</td>';
                }else if($input_arr[$x][$y]["flag"] == "no_disponible"){
                    $table.= "\n\t\t".'<td class="tipo-no_disponible"></td>';
                }
            }
            $table.= "\n\t</tr>";
       }
        
        $table.= "</tbody>";    
        $table.= "</table>";    
        return $table;
    }
    
    /**
    * Retorna la Serializacion Como un Array para pintar un Objeto Sala con sus zonas y butacas
    * @return array
    */ 
    public function getSalaAsArray(){
        $Zonas_arr = $this->getZonasArr();
        $display_butacas_sala_arr = array();
        $Butacas_arr = array();
        $i=0;
        foreach($Zonas_arr as $Zona){
            $id_butacas_arr = $Zona->getIdButacasArr();
            foreach($id_butacas_arr as $id_butaca){
                $Butaca = new Application_Model_Butaca();
                $Butaca->load($Zona->getId(), $id_butaca);
                $Butacas_arr[$i]["Butaca"] = $Butaca;
                $Butacas_arr[$i]["Zona"] = $Zona;
                $i++;
            }
        } 
        for($x=0;$x<$this->_x_length;$x++){
            for($y=0;$y<$this->_y_length;$y++){
                foreach($Butacas_arr as $Butaca){
                    if($Butaca["Butaca"]->getXpos()==$x && $Butaca["Butaca"]->getYpos()==$y ){
                        $display_butacas_sala_arr[$x][$y]["flag"] = "Butaca";
                        $display_butacas_sala_arr[$x][$y]["Butaca"] = $Butaca["Butaca"];
                        $display_butacas_sala_arr[$x][$y]["Zona"] = $Butaca["Zona"];
                        break;
                    }else{
                        if(!isset($display_butacas_sala_arr[$x][$y])){
                            $display_butacas_sala_arr[$x][$y]["flag"] = "no_disponible";
                        }
                    }
                 }
            }
        }  
        return $display_butacas_sala_arr;
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
    * Retorna la Serializacion como XML de un Objeto Sala
    * @return XML
    */  
    protected function getAsXmlAtributosRepresentacionXml()
    {
        $idioma_arr = $reg_webservice=Zend_Registry::get('idioma');
        $num_idiomas = $idioma_arr["count"];
        $string_xml ="\t<nombre>".htmlentities($this->_nombre, ENT_XML1)."</nombre>\n";
        $string_xml ="\t<nombre_abreviado>".htmlentities($this->_nombre_abreviado, ENT_XML1)."</nombre_abreviado>\n";
        $string_xml.="\t<svg_2d>".htmlentities($this->_svg_2d, ENT_XML1)."</svg_2d>\n";
        $string_xml.="\t<svg_3d>".htmlentities($this->_svg_3d, ENT_XML1)."</svg_3d>\n";
        $string_xml.="\t<capacidad>".$this->_capacidad."</capacidad>\n";
        $string_xml.="\t<x_length>".$this->_x_length."</x_length>\n";
        $string_xml.="\t<y_length>".$this->_y_length."</y_length>\n";
        $string_xml.="\t<id_teatro>".$this->_id_teatro."</id_teatro>\n";
        
        $string_xml.="\t<zonas>\n";
        foreach($this->_id_zona_arr as $id_zona){              
            $string_xml.="\t<id_zona>".$id_zona."</id_zona>\n";
        }   
        $string_xml.="\t</zonas>\n";
        
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
        $string_xml.=$this->_Coordenadas->getAsXml();

        return $string_xml;
    }       
    
    protected function addSpecificArtefacto($params_arr,$last_insert_id){}
    
    protected function writeSpecificArtefacto($params_arr){}  
    
    protected function deleteSpecificArtefacto($params_arr){}
    
    
}



