<?php

class Application_Model_Zona extends Application_Model_AbstractArtefactoObject
{
    private $_nombre;
    private $_svg_2d;
    private $_svg_3d;
    /**
     * @param string $_flag_numerada valores permitidos "1" , "0" .
     */
    private $_flag_numerada;
    private $_capacidad;
    private $_x_pos_inicial;
    private $_y_pos_inicial;
    private $_x_pos_final;
    private $_y_pos_final;
    private $_x_length;
    private $_y_length;
    private $_descripcion_arr;
    private $_imagen_principal;    
    private $_catalogo_galeria_arr;
    private $_id_butacas_arr;
    
    public function __construct($id=NULL,$nombre=NULL,$svg_2d=NULL,$svg_3d=NULL,$capacidad=NULL,
            $x_pos_inicial=NULL,$y_pos_inicial=NULL,$x_pos_final=NULL,$y_pos_final=NULL,$descripcion_arr=array(),
            $x_length=NULL,$y_length=NULL,$imagen_principal=NULL,$catalogo_galeria_arr=array(),$id_butacas_arr=array()){
       
        $id_categoria_artefacto = 2;// es el $id_categoria_artefacto de Zona
        parent::__construct($id,$id_categoria_artefacto);
        
        $this->_nombre=(string)$nombre;
        $this->_svg_2d=$svg_2d;
        $this->_svg_3d=$svg_3d;
        $this->_flag_numerada = 0;
        $this->_capacidad = $capacidad;
        $this->_x_pos_inicial = (int)$x_pos_inicial;
        $this->_y_pos_inicial = (int)$y_pos_inicial;
        $this->_x_pos_final = (int)$x_pos_final;
        $this->_y_pos_final = (int)$y_pos_final;
        $this->_x_length = (int)$x_length;
        $this->_y_length = (int)$y_length;
        $this->_descripcion_arr=$descripcion_arr;
        $this->_imagen_principal=(string)$imagen_principal;    
        $this->_catalogo_galeria_arr=$catalogo_galeria_arr;
        $this->_id_butacas_arr=$id_butacas_arr;
    } 
    
    protected function loadAtributosRepresentacionXml($artefacto_simple_xml){
        $idioma_arr = $reg_webservice=Zend_Registry::get('idioma');
        $num_idiomas = $idioma_arr["count"];   

        $this->_nombre = (string)$artefacto_simple_xml->nombre;
        $this->_svg_2d = (string)$artefacto_simple_xml->svg_2d;
        $this->_svg_3d = (string)$artefacto_simple_xml->svg_3d;
        $this->_flag_numerada = (int)$artefacto_simple_xml->flag_numerada;  
        $this->_capacidad = (int)$artefacto_simple_xml->capacidad;
        $this->_x_pos_inicial = (int)$artefacto_simple_xml->x_pos_inicial;
        $this->_y_pos_inicial = (int)$artefacto_simple_xml->y_pos_inicial;
        $this->_x_pos_final = (int)$artefacto_simple_xml->x_pos_final;
        $this->_y_pos_final = (int)$artefacto_simple_xml->y_pos_final;
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

        $this->_id_butacas_arr= array();  

        foreach($artefacto_simple_xml->butacas->butaca as $butaca){
            $this->_id_butacas_arr[] = (int)$butaca->attributes()["id_butaca"];
         }
    }
    
    public function getNombre(){
        return $this->_nombre;
    } 
    
    public function setNombre($nombre){
        $this->_nombre=$nombre;
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
    
    public function getFlagNumerada(){
    return $this->_flag_numerada;
    }
    
    public function setFlagNumerada($flag_numerada){
        $this->_flag_numerada = $flag_numerada;
    }
    
    public function getCapacidad(){
        return $this->_capacidad;
    } 
    
    public function setCapacidad($capacidad){
        $this->_capacidad=$capacidad;
    } 

    public function getXPosInicial(){
        return $this->_x_pos_inicial;
    } 
    
    public function setXPosInicial($x_pos_inicial){
        return $this->_x_pos_inicial = $x_pos_inicial;
    } 
    
    public function getYPosInicial(){
        return $this->_y_pos_inicial;
    } 
    
    public function setYPosInicial($y_pos_inicial){
        return $this->_y_pos_inicial = $y_pos_inicial;
    } 


    public function getXPosFinal(){
        return $this->_x_pos_final;
    } 
    
    public function setXPosFinal($x_pos_final){
        return $this->_x_pos_final = $x_pos_final;
    } 
    
    public function getYPosFinal(){
        return $this->_y_pos_final;
    } 
    
    public function setYPosFinal($y_pos_final){
        return $this->_y_pos_final = $y_pos_final;
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
        $this->_catalogo_galeria_arr= array_values($this->_catalogo_galeria_arr);
    } 
    
    
    public function getIdButacasArr(){
        return $this->_id_butacas_arr;
    } 
    
    /**
    * Retorna Todas los objetos Butaca de esa Zona
    * @return Application_Model_Butaca
    */  
    public function getButacassArr(){
        $Butacas_arr = array();
        foreach ($this->_id_butacas_arr as $id_butaca){
            $Butaca = new Application_Model_Butaca($id_butaca);
            $Butaca->load($this->_id, $id_butaca);
            $Butacas_arr[] = $Butaca;
        }
        return $Butacas_arr;
    }
    
    /**
    * Añade una referencia a $id_butaca en ZonaNumerada
    */ 
    public function addButaca($id_butaca){
        $this->_id_butacas_arr[] = $id_butaca;
        array_unique($this->_id_butacas_arr);
    } 
    
    
    /**
    * Borra la referencia $id_butaca en Zona y Borra el Objeto Butaca identicado con $id_butaca atraves del interfaz
    * @throws Exception deleteRefButaca
    * @throws Exception InterfaceArtefactoException
    */ 
    public function deleteRefButaca($id_butaca){
        $index = -1;
        foreach ($this->id_butacas_arr as $index_temp => $id_butaca_temp){
            if($id_butaca_temp == $id_butaca){
                $index= $index_temp;
                break;
            }
        }
        if ($index!=-1){
            unset ($this->id_butacas_arr[$index]);
            //reindexo para evitar que me queden posiciones vacias en el array
            $this->id_butacas_arr = array_values($this->id_butacas_arr);
        }else{
            throw new Exception('deleteRefButaca');
        }
    }   
    
    
    
    /**
    * Retorna la Serializacion Como un Array para pintar un Objeto Zona con sus zonas y butacas
    * @return array
    */ 
    public function getZonaAsArray(){

        $Butacas_arr = array();
        $i=0;
        $id_butacas_arr = $this->getIdButacasArr();
        foreach($id_butacas_arr as $id_butaca){
            $Butaca = new Application_Model_Butaca();
            $Butaca->load($this->_id, $id_butaca);
            $Butacas_arr[$i]["Butaca"] = $Butaca;
            $Zona = new Application_Model_Zona();
            $Zona->load($this->_id);
            $Butacas_arr[$i]["Zona"] = $Zona;            
            $i++;
        }     
        
        $display_butacas_sala_arr = array();
        for($x=$this->_x_pos_inicial;$x<$this->_x_length;$x++){
            for($y=$this->_y_pos_inicial;$y<$this->_y_length;$y++){
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
    
    
    public function getZonaAsTableHtml(){
        $input_arr = $this->getZonaAsArray();
        
        $table = "<table>\n";
        $table.= "<tbody>\n";
        
        for ($x=$this->_x_pos_inicial; $x < $this->_x_length; $x++)
        {
            $table.= "\t<tr>";
            for ($y=$this->_y_pos_inicial; $y<$this->_y_length; $y++)
            {
                if($input_arr[$x][$y]["flag"] == "Butaca"){
                    $Zona = $input_arr[$x][$y]["Zona"];
                    $Butaca = $input_arr[$x][$y]["Butaca"];
                    $table.= "\n\t\t".'<td class="zona-'.$Zona->getId().' tipo-'.$Butaca->getTipo().' altura-'.$Butaca->getAltura().'">fila:'.$Butaca->getFila().'<br/>'.$Butaca->getNum().'</td>';
                }else if($input_arr[$x][$y]["flag"] == "no_disponible"){
                    $table.= "\n\t\t".'<td class="tipo-no_disponible"></td>';
                }
            }
            $table.= "\n\t</tr>\n";
       }
        
        $table.= "</tbody>\n";    
        $table.= "</table>\n";    
        return $table;
    }    
    
    

    /**
    * Retorna la Zona como un SvgGraphics 3d con
    * @param $Session /Application_Model_Sesion es el objeto Sesion, si se lo pasamos nos pondra el estado en esa sesion para cada butaca si no actuara simplmenete dibujando la zona
    * @param $butacas_in_carrito_arr array("id_zona"=>int,"id_butaca"=>int)
    * @param $id_session string es el identificador de session para ese proceso de compra
    * @param $on_click_butaca string la funcion javascript a la que vamos a llamar al evento onclick() si se le pasara como parametro a esta funcion el id_butaca
    * @param $svg_width integer es la anchura del contenedor SVGraphics
    * @param $svg_height integer es la altura del contenedor SVGraphics
    * @param $tamano_x integer es el tamaño x de los rectangulos de las butacas
    * @param $tamano_y integer es el tamaño y de los rectangulos de las butacas
    * @param $nivel_sala float es la pendiente de la sala
    * @return XML
    */  
    public function getZonaAsSvgGraphics3D($Session=NULL,$butacas_in_carrito_arr=NULL,$id_session=NULL,$onclick_butaca="clickButaca",$svg_width=580,$svg_height=500,$tamano_x=28,$tamano_y=20,$nivel_sala=1){
        
        $InterfaceArtefacto = new Application_Model_InterfaceArtefactoEticketSecure();
        
        if(!$result = $InterfaceArtefacto->loadCacheOperacionArtefacto($this->_id,"getZonaAsSvgGraphics3D")){

                $svg = 'var svgContainer = d3.select("#svg_container").append("svg")
                                   .attr("viewBox" , "0 0 '.$svg_width.' '.$svg_height.'")
                                   .attr("id", "svg_sala");';

                $svg.= $this->_svg_3d;

                $input_arr = $this->getZonaAsArray();         
                $w=$tamano_x-2;
                $h=$tamano_y-4;

                $draw_offset_y=50;

                $draw_offset_x=65;

                $perspectiva_y=($tamano_y*2);

                $perspectiva_x=$tamano_x*0.2714285;

                $perspectiva_y_adder = 0;
                $perspectiva_x_adder = 0;              

                $y_3d = $draw_offset_y; 

                $x_3d =$draw_offset_x;

                $fuente_butaca = 10;
                $fuente_fila = 13;

                
                for ($x=$this->_x_pos_inicial; $x < $this->_x_length; $x++)
                {
                    $flag_fila = 1;
                    $contador_butacas_fila= 0; 

                    for ($y=$this->_y_pos_inicial; $y<$this->_y_length; $y++)
                    {

                        if($input_arr[$x][$y]["flag"] == "Butaca"){ 

                            $contador_butacas_fila++;
                            $Butaca = $input_arr[$x][$y]["Butaca"];
                           
                           
                           if($flag_fila){//primera butaca de fila
                                $svg.= 'var fila_rectangulo = svgContainer.append("rect")
                                                                .attr("class", "fila")
                                                                .attr("transform", "matrix(1 -0.19 0.6339 0.7734  '.($y_3d-20).' '.($x_3d+15).')")
                                                                .attr("height", '.$tamano_y.')
                                                                .style("fill", "rgba(255,255,255,0.4)");'."\n";

                                $svg.= 'svgContainer.append("text")
                                                        .attr("transform", "matrix(0.6352 -0.1176 0.6339 0.7734 '.($y_3d-10).' '.($x_3d+25).')")
                                                            .text("F'.$Butaca->getFila().'")                                                 
                                                            .style("font-size" , '.$fuente_fila.')
                                                            .style("font-family" , "Verdana")
                                                            .style("font-weight" , "bold")
                                                            .style("fill", "#FFFFFF");'."\n";
                                $flag_fila = 0;

                           }


                           $altura_butaca=$Butaca->getAltura();// lo vamos a poner solo en el info por ahora

                            ///butacas rectangulares 3D:
                            
                           
                            $svg.= "\n".'var link = svgContainer.append("a")
                                                    .attr("class", "tipo-'.$Butaca->getTipo().'")  
                                                    .attr("tipo", "'.$Butaca->getTipo().'")';  
                            if($Session!=NULL){
                                $ButacaSesion = $Session->getButacaSesion($Butaca->getIdZona(), $Butaca->getId());
                                             $svg.='.attr("estado", "'.$ButacaSesion->getEstado().'")';
                            }
                            if($butacas_in_carrito_arr!=NULL){
                                //busco si el $id_zona y $id_butaca esta en $butacas_in_carrito_arr
                                foreach($butacas_in_carrito_arr as $butaca_in_carrito){
                                    if($butaca_in_carrito["id_zona"]==$Butaca->getIdZona() && $butaca_in_carrito["id_butaca"]==$Butaca->getId() ){
                                            $svg.='.attr("propietario", "'.$id_session.'")';
                                            $svg.='.attr("id_linea_carrito_compra", "'.$butaca_in_carrito["id_linea_carrito_compra"].'")';
                                    }
                                }
                                //fin busco si el $id_zona y $id_butaca esta en $butacas_in_carrito_arr

                            }
                                             $svg.='.attr("id", "butaca_'.$Butaca->getId().'")
                                                    .attr("onclick","'.$onclick_butaca.'('.$Butaca->getId().');");'."\n";

                            $svg.= 'link.append("rect")
                                                        .attr("class", "butaca_shadow")
                                                        .attr("transform", "matrix(1 -0.19 0.6339 0.7734  '.$y_3d.' '.($x_3d+15).')")
                                                        .attr("width",'.($w*0.9).')
                                                        .attr("height", '.($h*0.8).')
                                                        .style("fill", "rgba(0,0,0,0.80)");'."\n";

                            $svg.= 'link.append("rect")
                                                        .attr("class", "butaca_back")
                                                        .attr("transform", "matrix(1 -0.1763 0 1  '.$y_3d.' '.$x_3d.')")
                                                        .attr("width",'.($w*0.9).')
                                                        .attr("height", '.$fuente_butaca.');'."\n";

                             $svg.= 'link.append("rect")
                                                        .attr("class", "butaca_seat")
                                                        .attr("transform", "matrix(1 -0.19 0.6339 0.7734  '.$y_3d.' '.($x_3d+$fuente_butaca).')")
                                                        .attr("width",'.($w*0.9).')
                                                        .attr("height", '.($h*0.8).');'."\n";

                             $svg.= 'link.append("text")
                                                        .attr("class", "butaca_numero")
                                                        .attr("transform", "matrix(1 -0.19 0.6339 0.7734 '.($y_3d+15).' '.($x_3d+15).')")
                                                        .text("'.$Butaca->getNum().'")
                                                        .attr("width",'.($w*0.9).')
                                                        .attr("height", '.($h*0.8).')
                                                        .style("font-size" , '.$fuente_butaca.')
                                                        .style("font-family" , "Arial");'."\n";

                                
                            //añade el ancho al rectangulo delimitador de cada fila  / solo lo pinta si hay butaca en la fila
                            if( ($this->_y_length-$y)==1 || ($input_arr[$x][$y+1]["flag"] != "Butaca")){
                                    $svg.= 'fila_rectangulo.attr("width",'.(($perspectiva_y)*$contador_butacas_fila+24).');'."\n";

                                    $svg.= 'svgContainer.append("text")
                                                                        .attr("transform", "matrix(0.6352 -0.1176 0.6339 0.7734 '.($y_3d+37).' '.($x_3d+15).')")
                                                                            .text("F'.$Butaca->getFila().'")                                                 
                                                                            .style("font-size" , '.$fuente_fila.')
                                                                            .style("font-family" , "Verdana")
                                                                            .style("font-weight" , "bold")
                                                                            .style("fill", "#FFFFFF");'."\n";

                            }
                        }else if($input_arr[$x][$y]["flag"] == "no_disponible"){
                            $flag_fila = 1;  
                            $contador_butacas_fila= 0; 
                        }

                        $y_3d = $y_3d+$perspectiva_y; 
                        $x_3d = $x_3d-$perspectiva_x;
                }
                
                
                
                
                
                
                
                $perspectiva_y_adder = ($perspectiva_y_adder+($perspectiva_y/2))/$nivel_sala;
                $perspectiva_x_adder = $perspectiva_x_adder+($perspectiva_x*4);            

                $y_3d = $draw_offset_y + ($perspectiva_y_adder);     
                $x_3d = $draw_offset_x + ($perspectiva_x_adder);
              
            }  
            $InterfaceArtefacto->salvarCacheOperacionesModelo($this->_id, $svg,"getZonaAsSvgGraphics3D");
            return $svg;
        }else{
            return $result;
        }
    }
    
    /**
    * Retorna la Zona como un SvgGraphics 2d con
    * @param $svg_width integer es la anchura del contenedor SVGraphics
    * @param $svg_height integer es la altura del contenedor SVGraphics
    * @param $tamano_x integer es el tamaño x de las elipses de las butacas
    * @param $tamano_y integer es el tamaño y de las elipses de las butacas
    * @return XML
    */  
    public function getZonaAsSvgGraphics2D($svg_width=500,$svg_height=380,$tamano_x=28,$tamano_y=20){
        
        $InterfaceArtefacto = new Application_Model_InterfaceArtefactoEticketSecure();
        
        if(!$result = $InterfaceArtefacto->loadCacheOperacionArtefacto($this->_id,"getZonaAsSvgGraphics2D")){

                $svg = 'var svgContainer = d3.select("#svg_container").append("svg")
                                   .attr("viewBox" , "0 0 '.$svg_width.' '.$svg_height.'")
                                   .attr("id", "svg_sala");';

                $svg.= $this->_svg_2d;

                $input_arr = $this->getZonaAsArray();         
                $w=$tamano_x-2;
                $h=$tamano_y-4;

                $draw_offset_y=48;

                $draw_offset_x=30;

                $perspectiva_y=40;

                $perspectiva_x=30;            

                $y_2d = $draw_offset_y; 

                $x_2d =$draw_offset_x;

                $fuente_butaca = 9;
                $fuente_fila = 13;

                for ($x=$this->_x_pos_inicial; $x < $this->_x_length; $x++)
                {
                    $flag_fila = 1;
                    $contador_butacas_fila= 0; 

                    for ($y=$this->_y_pos_inicial; $y<$this->_y_length; $y++)
                    {

                        if($input_arr[$x][$y]["flag"] == "Butaca"){ 

                           $Butaca = $input_arr[$x][$y]["Butaca"];
                           
                           /*if($flag_fila){//primera butaca de fila
                                $svg.= 'var fila_rectangulo = svgContainer.append("rect")
                                                                .attr("class", "fila")
                                                                .attr("x", '.($y_2d-20).')
                                                                .attr("y", '.($x_2d+15).')
                                                                .attr("height", '.$tamano_y.')
                                                                .style("fill", "rgba(255,255,255,0.4)");'."\n";

                                $svg.= 'svgContainer.append("text")
                                                            .attr("x", '.($y_2d-10).')
                                                            .attr("y", '.($x_2d+25).')
                                                            .text("F'.$Butaca->getFila().'")                                                 
                                                            .style("font-size" , '.$fuente_fila.')
                                                            .style("font-family" , "Verdana")
                                                            .style("font-weight" , "bold")
                                                            .style("fill", "#FFFFFF");'."\n";
                                $flag_fila = 0;

                           }*/


                           $altura_butaca=$Butaca->getAltura();// lo vamos a poner solo en el info por ahora
                            
                           
                            //Numero fila                                           
                            
                            if($this->_x_pos_inicial==$y || ($input_arr[$x][$y-1]["flag"] != "Butaca") ){// para la primera butaca de fila o si no existe anterior
                                 $svg.= 'var fila_rectangulo = svgContainer.append("rect")
                                                                .attr("class", "fila")
                                                                .attr("x", '.($y_2d-20).')
                                                                .attr("y", '.($x_2d+2).')
                                                                .attr("height", '.$tamano_y.')
                                                                .style("fill", "rgba(255,255,255,0.4)");'."\n";
                                 
                                $svg.= 'svgContainer.append("text")
                                                                        .attr("x", '.($y_2d-18).')
                                                                        .attr("y", '.($x_2d+15).')
                                                                        .text("F'.$Butaca->getFila().'")
                                                                        .style("font-size" , '.$fuente_fila.')
                                                                        .style("font-family" , "Arial")
                                                                        .style("font-weight" , "bold")
                                                                        .style("fill", "rgba(255,255,255,0.8)");'."\n"; 
                                $width_fila=0;
                            }
                            
                            $width_fila++;
                            
                            //Numero fila
                            if(($this->_y_length-$y)==1 || ($input_arr[$x][$y+1]["flag"] != "Butaca") ){// para la ultima butaca de fila o si no existe siguiente
                                $svg.= 'svgContainer.append("text")
                                                                        .attr("x", '.($y_2d+30).')
                                                                        .attr("y", '.($x_2d+15).')
                                                                        .text("F'.$Butaca->getFila().'")
                                                                        .style("font-size" , '.$fuente_fila.')
                                                                        .style("font-family" , "Arial")
                                                                        .style("font-weight" , "bold")
                                                                        .style("fill", "rgba(255,255,255,0.8)");'."\n"; 
                                
                                $svg.= 'fila_rectangulo.attr("width",'.($perspectiva_y*$width_fila+28).');'."\n";
                            }

                            ///butacas cilindros:
                           
                           $svg.= 'var link = svgContainer.append("a")
                                                            .attr("class", "tipo-'.$Butaca->getTipo().'")
                                                            .attr("id", "butaca_'.$Butaca->getId().'")
                                                            .attr("href","javascript:selButaca()");'."\n";   
                                              

                            $svg.= 'link.append("ellipse")
                                                                    .attr("class", "butaca_back")
                                                                    .attr("cx", '.($y_2d+($w*0.5)).')
                                                                    .attr("cy", '.($x_2d+($h*0.4)+4).')
                                                                    .attr("rx",'.($w*0.5).')
                                                                    .attr("ry", '.($h*0.6).');'."\n";  

                            $svg.= 'link.append("ellipse")
                                                                    .attr("class", "butaca_seat")
                                                                    .attr("cx", '.($y_2d+($w*0.5)).')
                                                                    .attr("cy", '.($x_2d+($h*0.4)).')
                                                                    .attr("rx",'.($w*0.5).')
                                                                    .attr("ry", '.($h*0.6).');'."\n"; 
                            //Numero butaca
                            $svg.= 'link.append("text")
                                                                    .attr("x", '.($y_2d+8).')
                                                                    .attr("y", '.($x_2d+8).')
                                                                    .text("'.$Butaca->getNum().'")
                                                                    .style("font-size" , '.$fuente_butaca.')
                                                                    .style("font-family" , "Arial")
                                                                    .style("fill", "rgba(255,255,255,0.8)");'."\n"; 
                            

                        }else if($input_arr[$x][$y]["flag"] == "no_disponible"){
                        }

                        $y_2d = $y_2d+$perspectiva_y;
                }
                //añade el ancho al rectangulo delimitador de cada fila  / solo lo pinta si hay butaca en la fila
                /*if(!$flag_fila){
                    $svg.= 'fila_rectangulo.attr("width",'.(($perspectiva_y)*$contador_butacas_fila+20).');'."\n";
                    
                    $svg.= 'svgContainer.append("text")
                                                            .attr("x", '.($y_2d-4).')
                                                            .attr("y", '.($y_2d-4).')
                                                            .text("F'.$Butaca->getFila().'")                                                 
                                                            .style("font-size" , '.$fuente_fila.')
                                                            .style("font-family" , "Verdana")
                                                            .style("font-weight" , "bold")
                                                            .style("fill", "#FFFFFF");'."\n";
                
                }*/

                $y_2d = $draw_offset_y;     
                $x_2d = $x_2d+$perspectiva_x;
            }  
            $InterfaceArtefacto->salvarCacheOperacionesModelo($this->_id, $svg,"getZonaAsSvgGraphics2D");
            return $svg;
        }else{
            return $result;
        }
    }
    
    /**
    * Retorna la Serializacion como XML de un Objeto Zona
    * @return XML
    */  
    protected function getAsXmlAtributosRepresentacionXml()
    {
        $idioma_arr = $reg_webservice=Zend_Registry::get('idioma');
        $num_idiomas = $idioma_arr["count"];
        $string_xml ="\t<nombre>".htmlentities($this->_nombre,ENT_XML1)."</nombre>\n";
        $string_xml.="\t<svg_2d>".htmlentities($this->_svg_2d, ENT_XML1)."</svg_2d>\n";
        $string_xml.="\t<svg_3d>".htmlentities($this->_svg_3d, ENT_XML1)."</svg_3d>\n";
        $string_xml.="\t<flag_numerada>".$this->_flag_numerada."</flag_numerada>\n";
        $string_xml.="\t<capacidad>".$this->_capacidad."</capacidad>\n";
        $string_xml.="\t<x_pos_inicial>".$this->_x_pos_inicial."</x_pos_inicial>\n";
        $string_xml.="\t<y_pos_inicial>".$this->_y_pos_inicial."</y_pos_inicial>\n";
        $string_xml.="\t<x_pos_final>".$this->_x_pos_final."</x_pos_final>\n";
        $string_xml.="\t<y_pos_final>".$this->_y_pos_final."</y_pos_final>\n";
        $string_xml.="\t<x_length>".$this->_x_length."</x_length>\n";
        $string_xml.="\t<y_length>".$this->_y_length."</y_length>\n";
        $string_xml.="\t<descripcion>\n";
        for($i=0;$i<$num_idiomas;$i++){              
            $idioma= $idioma_arr[$i];
            $string_xml.="\t\t<".$idioma.">";
            $string_xml.=htmlentities($this->_descripcion_arr[$idioma],ENT_XML1);
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
        $string_xml.="\t<butacas>\n";
        foreach($this->_id_butacas_arr as $id_butaca){              
            $string_xml.="\t\t<id_butaca>".$id_butaca."<id_butaca>\n";
        }   
        $string_xml.="\t</butacas>\n";
        return $string_xml;
    }        

  
    /**
    * @param integer $last_insert_id el id donde se inserto el artefacto
    * @param array[] $params_arr tiene que contener, $params_arr["id_sala"]
    * Este metodo abastracto
    * Permite implementar logicas adicionales de write()
    */   
    protected function addSpecificArtefacto($params_arr,$last_insert_id){
        $Sala = new Application_Model_Sala();
        $Sala->load($params_arr["id_sala"]);
        $Sala->addIdZonaArr($last_insert_id);
        $Sala->write();
    }
    
    protected function writeSpecificArtefacto($params_arr){}  
    
    /**
    * @param array[] $params_arr tiene que contener, $params_arr["id_sala"]
    * Este metodo abastracto
    * Permite implementar logicas adicionales de delete()
    */     
    protected function deleteSpecificArtefacto($params_arr){
        $Sala = new Application_Model_Sala();
        $Sala->load($params_arr["id_sala"]);
        $Sala->deleteRefZonaInfo($this->_id);
        $Sala->write();    
    }
    
}




