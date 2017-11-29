<?php

class Application_Model_Sesion extends Application_Model_AbstractProductoObjectWebMeta
{
    private $_nombre_arr;
    private $_id_obra;
    private $_date_time;
    private $_pvp_taquilla;
    
    private $_id_sala;
    private $_flag_agotada;
    /**
     * @var  ZonaSesion
     */
    private $_ZonaSesion_arr;
    /**
     *
     * @var  ButacaSesion
     */
    private $_ButacaSesion_arr;  
    /**
     *
     * @var  EspecialButacaSesion
     */
    private $_EspecialButacaSesion_arr;  
    /**
     *
     * @var  CanalesSesion
     */
    private $_CanalesSesion_arr;  
    
    public function __construct($id=NULL,$id_obra=NULL,$nombre_arr=array(),$date_time=NULL,$pvp=NULL,$pvp_taquilla=NULL,$codigo_referencia=NULL,
            $id_sala=NULL,$flag_agotada=NULL,$id_descuentos_arr=array(),$iva=NULL,$MetaTag=NULL,$sef_arr=array(),
            $ZonaSesion_arr=array(),$ButacaSesion_arr=array(),$EspecialButacaSesion_arr=array(),
            $CanalesSesion_arr=array()){  

        $id_categoria = 1;// es el $id_categoria de Sesion
        parent::__construct($id,$id_categoria,$pvp,$codigo_referencia,$iva,$MetaTag,$sef_arr);

        $this->_id_obra=$id_obra;
        $this->_nombre_arr=$nombre_arr;
        $this->_date_time=$date_time;
        $this->_pvp_taquilla=$pvp_taquilla;
        $this->_id_sala=$id_sala;
        $this->_flag_agotada=$flag_agotada;
        $this->_id_descuentos_arr=$id_descuentos_arr;
        $this->_ZonaSesion_arr=$ZonaSesion_arr;
        $this->_ButacaSesion_arr=$ButacaSesion_arr;
        $this->_EspecialButacaSesion_arr=$EspecialButacaSesion_arr;
        $this->_CanalesSesion_arr=$CanalesSesion_arr;
    } 

    protected function loadAtributosRepresentacionXml($producto_simple_xml){
        $idioma_arr = $reg_webservice=Zend_Registry::get('idioma');
        $num_idiomas = $idioma_arr["count"];   
        
        $this->_nombre_arr= array();
        for($i=0;$i<$num_idiomas;$i++){              
            $idioma= $idioma_arr[$i];
            $this->_nombre_arr[$idioma]= (string)$producto_simple_xml->nombre->$idioma;
        } 
        
        $this->_id_obra=(int)$producto_simple_xml->id_obra;  
        $this->_date_time = (string)$producto_simple_xml->date_time;
        $this->_pvp_taquilla = (float)$producto_simple_xml->pvp_taquilla;
        $this->_id_sala= (int)$producto_simple_xml->id_sala;
        $this->_flag_agotada= (int)$producto_simple_xml->flag_agotada;
   
        $this->_CanalesSesion_arr= array();  
        foreach($producto_simple_xml->canales_sesion->canal_sesion as $canal_sesion){
            $id_canal = (int)$canal_sesion->attributes()["id_canal"];
            $id_agrupador = (int)$canal_sesion->attributes()["id_agrupador"];
            $flag_numerada = (float)$canal_sesion->flag_numerada;
            $pvp = (float)$canal_sesion->pvp;
            $id_descuento_arr= array();
            foreach($canal_sesion->descuentos->descuento as $descuento){
                $id_agrupador_descuento = (int)$descuento->attributes()["id_agrupador"];
                $id_descuento_arr[] = array("id_descuento"=>(int)$descuento->id_descuento,"id_agrupador"=>$id_agrupador_descuento);
            } 
            $id_butaca_arr= array();
            foreach($canal_sesion->id_butacas->butaca as $butaca){
                $id_zona = (int)$butaca->attributes()["id_zona"];
                $id_butaca = (int)$butaca->attributes()["id_butaca"];
                $id_butaca_arr[] = array("id_zona"=>$id_zona,"id_butaca"=>$id_butaca);
            } 
            $this->_CanalesSesion_arr[] = new Application_Model_CanalSesion($id_canal,$id_agrupador,$flag_numerada, $pvp, $id_descuento_arr, $id_butaca_arr);
        }  
        
        $this->_EspecialButacaSesion_arr= array();  
   
        foreach($producto_simple_xml->especiales_butaca_sesion->especial_butaca_sesion as $especial_butaca_sesion){
            $id = (int)$especial_butaca_sesion->attributes()["id_especial_butaca_sesion"];
            $id_agrupador = (int)$especial_butaca_sesion->attributes()["id_agrupador"];
            $flag_numerada = (int)$especial_butaca_sesion->flag_numerada;
            $nombre_arr= array();
            for($i=0;$i<$num_idiomas;$i++){              
                $idioma= $idioma_arr[$i];
                $nombre_arr[$idioma]= (string)$especial_butaca_sesion->nombre->$idioma;
            } 
            $descripcion_arr= array();
            for($i=0;$i<$num_idiomas;$i++){
                $idioma= $idioma_arr[$i];
                $descripcion_arr[$idioma]= (string)$especial_butaca_sesion->descripcion->$idioma;
            } 
            $pvp = (float)$especial_butaca_sesion->pvp;
            
            $id_descuento_arr= array();
            foreach($especial_butaca_sesion->descuentos->descuento as $descuento){
                $id_agrupador_descuento = (int)$descuento->attributes()["id_agrupador"];
                $id_descuento_arr[] = array("id_descuento"=>(int)$descuento->id_descuento,"id_agrupador"=>$id_agrupador_descuento);
            } 
            
            $id_butaca_arr= array();
            foreach($especial_butaca_sesion->id_butacas->butaca as $butaca){
                $id_zona = (int)$butaca->attributes()["id_zona"];
                $id_butaca = (int)$butaca->attributes()["id_butaca"];
                $id_butaca_arr[] = array("id_zona"=>$id_zona,"id_butaca"=>$id_butaca);
            } 
            
            $imagen_principal = (string)$especial_butaca_sesion->imagen_principal;

            $catalogo_galeria_arr= array();
            if($especial_butaca_sesion->catalogo_galeria->imagen!=NULL){
                $imagen_xml = $especial_butaca_sesion->catalogo_galeria->imagen;
                foreach($imagen_xml as $imagen){              
                    $catalogo_galeria_arr[]=(string)$imagen;
                }
            }            
            $this->_EspecialButacaSesion_arr[] = new Application_Model_EspecialButacaSesion($id,$id_agrupador,$nombre_arr, $descripcion_arr, $pvp, $id_descuento_arr, $id_butaca_arr, $imagen_principal, $catalogo_galeria_arr, $flag_numerada);
        }  
        
        $this->_ZonaSesion_arr= array();  
        foreach($producto_simple_xml->zonas_sesion->zona_sesion as $zona_sesion){
            $id_zona = (int)$zona_sesion->attributes()["id_zona"];
            $id_agrupador = (int)$zona_sesion->attributes()["id_agrupador"];
            $flag_numerada = (string)$zona_sesion->flag_numerada;
            $pvp = (float)$zona_sesion->pvp;
            $id_descuento_arr= array();
            foreach($zona_sesion->descuentos->descuento as $descuento){
                $id_agrupador_descuento = (int)$descuento->attributes()["id_agrupador"];
                $id_descuento_arr[] = array("id_descuento"=>(int)$descuento->id_descuento,"id_agrupador"=>$id_agrupador_descuento);
            } 
            $this->_ZonaSesion_arr[] = new Application_Model_ZonaSesion($id_zona,$id_agrupador,$flag_numerada, $pvp, $id_descuento_arr);
        }  
        
        $this->_ButacaSesion_arr= array();  
        foreach($producto_simple_xml->butacas_sesion->butaca_sesion as $butaca_sesion){
            $id_zona = (int)$butaca_sesion->attributes()["id_zona"];
            $id_butaca = (int)$butaca_sesion->attributes()["id_butaca"];
            $estado = (string)$butaca_sesion->estado;
            $this->_ButacaSesion_arr[] = new Application_Model_ButacaSesion($id_zona, $id_butaca, $estado);
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
    
    public function getPvpTaquilla() {
        return $this->_pvp_taquilla;
    }

    public function setPvpTaquilla($pvp_taquilla) {
        $this->_pvp_taquilla = $pvp_taquilla;
    }
        
    public function getIdSala(){
        return $this->_id_sala;
    } 
    
    public function setIdSala($id_sala){
        return $this->_id_sala=$id_sala;
    }
    
    public function getFlagAgotada() {
        return $this->_flag_agotada;
    }

    public function setFlagAgotadaTrue() {
        $this->_flag_agotada = 1;
    }

    public function setFlagAgotadaFalse() {
        $this->_flag_agotada = 0;
    }

    public function getCanalesSesionArr(){
        return $this->_CanalesSesion_arr;
    } 
    
    /**
     * 
     * @param Application_Model_CanalSesion $CanalSesion
     */
    public function addCanalesSesion($CanalSesion){
        $this->_CanalesSesion_arr[] = $CanalSesion;
    }   
   
    
    public function deleteCanalSesion($id_canal){
        $index = $this->buscarIdCanalInCanalSesionArr($id_canal);
        unset ($this->_CanalesSesion_arr[$index]);
        //reindexo para evitar que me queden posiciones vacias en el array
        $this->_CanalesSesion_arr = array_values($this->_CanalesSesion_arr);
    } 
    
    
    /**
    * Encuenta el index para la referencia  a $id_zona y $id_butaca en el array CanalesSesion_arr
    * Si lo encuentra devuelve el index si no lanza una excepcion
    * @throws Exception buscarIdCanalInCanalSesionArr
    */    
    private function buscarIdCanalInCanalSesionArr($id_canal){
        $index = -1;
        foreach ($this->_CanalesSesion_arr as $index_temp => $CanalSesion){
            if($id_canal == $CanalSesion->getIdCanal()){
                $index= $index_temp;
                break;
            }
        }
        if ($index!=-1){
            return $index;
        }else{
            throw new Exception('buscarIdCanalInCanalSesionArr');
        }
    } 
    
    
    public function getEspecialButacaSesionArr(){
        return $this->_EspecialButacaSesion_arr;
    } 
    
    /**
     * 
     * @param int $id_especial_butaca_sesion
     * @return Application_Model_EspecialButacaSesion
     * @throws Exception
     */
    public function getEspecialButacaSesionByIdEspecialButacaSesion($id_especial_butaca_sesion){
        $flag = -1;
        foreach ($this->_EspecialButacaSesion_arr as $EspecialButaca){
            if($EspecialButaca->getId() == $id_especial_butaca_sesion){
                return $EspecialButaca;
            }
        }
        if ($flag==-1){
            throw new Exception('getEspecialButacaSesionByIdEspecialButacaSesionException');
        }
    } 
    
    /**
     * 
     * @param Application_Model_EspecialButacaSesion $EspecialButacaSesion
     */
    public function addEspecialButacaSesion($EspecialButacaSesion){
        end($this->_EspecialButacaSesion_arr);
        $index=key($this->_EspecialButacaSesion_arr);
        $index=$index+1;
        $this->_EspecialButacaSesion_arr[] = $EspecialButacaSesion->setId($index);
    }   
   
    
    public function deleteEspecialButacaSesion($index){
        $index = $this->buscarIndexInEspecialButacaSesionArr($index);
        unset ($this->_EspecialButacaSesion_arr[$index]);
        //reindexo para evitar que me queden posiciones vacias en el array
        $this->_EspecialButacaSesion_arr = array_values($this->_EspecialButacaSesion_arr);
    } 
    
    
    /**
    * Borra ese especial_butaca_sesion por orden de aparacion
    * Si lo encuentra devuelve el index si no lanza una excepcion
    * @throws Exception buscarIndexInEspecialButacaSesionArr
    */    
    private function buscarIndexInEspecialButacaSesionArr($index){
        foreach ($this->_EspecialButacaSesion_arr as $index_temp => $EspecialButaca){
            if($index == $EspecialButaca->getId()){
                $index= $index_temp;
                break;
            }else{
                $index=-1;
            }
        }
        if ($index!=-1){
            return $index;
        }else{
            throw new Exception('buscarIndexInEspecialButacaSesionArr');
        }
    } 
    
    public function getZonaSesionArr(){
        return $this->_ZonaSesion_arr;
    } 
    
    public function getZonaSesionByIdZona($id_zona){
        $index = $this->buscarIdZonaInZonaSesionArr($id_zona);
        return $this->_ZonaSesion_arr[$index];
    } 
    
    /**
     * 
     * @param Application_Model_ZonaSesion $ZonaSesion
     */
    public function addZonaSesion($ZonaSesion){
        $this->_ZonaSesion_arr[] = $ZonaSesion;
    }
    
    /**
    * Borra lareferencia  en _ZonaSesion_arr indizada con  $id_zona en Sesion
    * @throws Exception buscarIdZonaInZonaSesionArr
    */  
    public function deleteZonaSesion($id_zona){
        $index = $this->buscarIdZonaInZonaSesionArr($id_zona);
        unset ($this->_ZonaSesion_arr[$index]);
        //reindexo para evitar que me queden posiciones vacias en el array
        $this->_ZonaSesion_arr = array_values($this->_ZonaSesion_arr);
    } 
    
    
    /**
    * Encuenta el index para la referencia  a $id_zona  en el array ZonaSesionArr
    * Si lo encuentra devuelve el index si no lanza una excepcion
    * @throws Exception buscarIdZonaInZonaSesionArr
    */    
    private function buscarIdZonaInZonaSesionArr($id_zona){
        $index = -1;
        foreach ($this->_ZonaSesion_arr as $index_temp => $ZonaSesion){
            if($id_zona == $ZonaSesion->getIdZona()){
                $index= $index_temp;
                break;
            }
        }
        if ($index!=-1){
            return $index;
        }else{
            throw new Exception('buscarIdZonaInZonaSesionArr');
        }
    } 


    
    public function getButacaSesionArr(){
        return $this->_ButacaSesion_arr;
    } 
    
    /**
     * 
     * @param Application_Model_ButacaSesion $ButacaSesion
     */
    public function addButacaSesion($ButacaSesion){
        $this->_ButacaSesion_arr[] = $ButacaSesion;
    }   
  
    /**
     * 
     * @param int $id_zona
     * @param int $id_butaca
     * @return Application_Model_ButacaSesion
     */
    public function getButacaSesion($id_zona,$id_butaca){
        $index = $this->buscarIdZonaIdButacaInButacaSesionArr($id_zona,$id_butaca);
        return$this->_ButacaSesion_arr[$index];
    }
    
    /**
    * Borra lareferencia  en _ButacaSesion_arr indizada con  $id_zona y $id_butaca en Sesion y su "estado" para esta sesion
    * @throws Exception buscarIdZonaIdButacaInButacaSesionArr
    */  
    public function deleteButacaSesion($id_zona,$id_butaca){
        $index = $this->buscarIdZonaIdButacaInButacaSesionArr($id_zona, $id_butaca);
        unset ($this->_ButacaSesion_arr[$index]);
        //reindexo para evitar que me queden posiciones vacias en el array
        $this->_ButacaSesion_arr = array_values($this->_ButacaSesion_arr);
    } 
    
    
    /**
    * Encuenta el index para la referencia  a $id_zona y $id_butaca en el array ButacaSesionArr
    * Si lo encuentra devuelve el index si no lanza una excepcion
    * @throws Exception buscarIdZonaIdButacaInButacaSesionArr
    */    
    private function buscarIdZonaIdButacaInButacaSesionArr($id_zona,$id_butaca){
        $index = -1;
        foreach ($this->_ButacaSesion_arr as $index_temp => $ButacaSesion){
            if($id_zona == $ButacaSesion->getIdZona() && $id_butaca == $ButacaSesion->getIdButaca()){
                $index= $index_temp;
                break;
            }
        }
        if ($index!=-1){
            return $index;
        }else{
            throw new Exception('buscarIdZonaIdButacaInButacaSesionArr');
        }
    }
    
    
    /**
     * @param array[Descuentos] $id_descuentos_arr
     * @param integer $con_flag_publico por defecto todos los descuentos , "0" solo los bajo registro , "1" solos los publicos
     * @param string $orden ASC o DESC por defecto ASC
     * @return array[Descuento]
     */
    private function getDescuentoArr($id_descuentos_arr,$con_flag_publico=NULL,$orden="ASC"){
        if($con_flag_publico==NULL) $con_flag_publico="todos";
        
        if ($orden!=="ASC" && $orden!=="DESC") throw new Exception('getDescuentoArr');
        if ($con_flag_publico!=="todos" && $con_flag_publico!==0 && $con_flag_publico!==1)throw new Exception('getDescuentoArr');
        
        $Descuento_arr = array();
        $i=0;
        foreach($id_descuentos_arr as $id_descuento){
            $Descuento = new Application_Model_Descuento();
            $Descuento->load($id_descuento); 
            
            if($Descuento->getFlagPublico()== $con_flag_publico){
                $Descuento_arr[$i] = $Descuento;  
                $j=$i;
                $i++;
                $continua = TRUE;
                while ($j>0 && $continua) {
                    $orden_nueva =  $Descuento_arr[$j]->getOrden();
                    $orden_anterior =  $Descuento_arr[($j-1)]->getOrden();

                    if($orden=="ASC"){
                        if ($orden_nueva<$orden_anterior){
                            $temp_arr = $Descuento_arr[($j-1)];
                            $Descuento_arr[($j-1)] = $Descuento_arr[$j];
                            $Descuento_arr[$j] = $temp_arr;
                        }else{
                            $continua = TRUE;
                        }
                    }else if($orden=="DESC"){
                        if ($orden_nueva>$orden_anterior){
                            $temp_arr = $Descuento_arr[($j-1)];
                            $Descuento_arr[($j-1)] = $Descuento_arr[$j];
                            $Descuento_arr[$j] = $temp_arr;
                        }else{
                            $continua = TRUE;
                        }
                    }

                    $j--;
                }
            }
        }
        return $Descuento_arr;
    }
    
    /**
     * 
     * @param integer $id_zona
     * @param integer $con_flag_publico por defecto todos los descuentos , "0" solo los bajo registro , "1" solos los publicos
     * @param string $orden ASC o DESC por defecto ASC
     * @return array[Descuento]
     */
    public function getDescuentoZonaSesionArrByIdZona($id_zona,$con_flag_publico=NULL,$orden="ASC"){
        $id_sesion_arr = array();
        foreach ($this->_ZonaSesion_arr as $ZonaSesion){
            if($ZonaSesion->getIdZona() == $id_zona) $id_sesion_arr[] = $ZonaSesion->getIdDescuentoArr();
        }
        return $this->getDescuentoArr($id_sesion_arr,$con_flag_publico, $con_flag_publico, $orden);
    }
    
    /**
     * 
     * @param integer $id_zona
     * @param integer $id_butaca
     * @param integer $con_flag_publico por defecto todos los descuentos , "0" solo los bajo registro , "1" solos los publicos
     * @param string $orden ASC o DESC por defecto ASC
     * @return array[Descuento]
     */
    public function getDescuentoButacaSesionArrByIdZona($id_zona,$id_butaca,$con_flag_publico=NULL,$orden="ASC"){
     
        $id_sesion_arr = array();
        foreach ($this->_ButacaSesion_arr as $ButacaSesion){
            if($ButacaSesion->getIdZona() == $id_zona && $ButacaSesion->getIdButaca() == $id_butaca) $id_sesion_arr[] = $ButacaSesion->getIdDescuentoArr();
        }
        return $this->getDescuentoArr($id_sesion_arr,$con_flag_publico, $con_flag_publico, $orden);
    }
    
    /**
     * Retornta todos las butacas que no pertenecen a ninguna zona de EspecialesSesion
     * @return array /ApplicationModelButacaSesion
     */
    public function getButacasSesionQueNoPertenecenAEspecialesSesion(){
        $ButacaSesion_arr = $this->_ButacaSesion_arr;
        $this->_EspecialButacaSesion_arr;
        foreach($this->_EspecialButacaSesion_arr as $EspecialButacaSesion){
            $id_butaca_arr_arr = $EspecialButacaSesion->getIdButacaArr();
            foreach($id_butaca_arr_arr as $id_butaca_arr){
                foreach($ButacaSesion_arr as $key=>$ButacaSesion){
                    if($ButacaSesion->getIdButaca()==$id_butaca_arr["id_butaca"] && $ButacaSesion->getIdZona()==$id_butaca_arr["id_zona"]){
                        unset($ButacaSesion_arr[$key]);
                    }
                }
            }
        }
        return $ButacaSesion_arr;
    }
    
    /**
     * devuelve  true si ha podido Bloquear $num_butacas para esa EspecialButacaSesion 
     * identificado por $id_especial_butaca_sesion
     * devuelve  false si no ha podido Bloquear $num_butacas para esa EspecialButacaSesion y no marcará ninguna como reservada
     * @param int $id_zona
     * @param int $num_butacas
     * Ejemplo de array de retorno:
     * 
     * $index_butaca_sesion_reservadas_arr['status']= 'success'; 
     * $index_butaca_sesion_reservadas_arr['butacas'][0]['id_zona']= 2;   
     * $index_butaca_sesion_reservadas_arr['butacas'][0]['id_butaca']= 1;   
     * $index_butaca_sesion_reservadas_arr['butacas'][1]['id_zona']= 2;   
     * $index_butaca_sesion_reservadas_arr['butacas'][1]['id_butaca']= 2; 
     * $index_butaca_sesion_reservadas_arr['butacas'][2]['id_zona']= 2;   
     * $index_butaca_sesion_reservadas_arr['butacas'][2]['id_butaca']= 3; 
     * $index_butaca_sesion_reservadas_arr['butacas'][3]['id_zona']= 2;   
     * $index_butaca_sesion_reservadas_arr['butacas'][3]['id_butaca']= 4;   
     */
    public function BloquearTimeByIdEspecialButacaSesionAndNumButacas($id_especial_butaca_sesion, $num_butacas){
        $EspecialButacaSesion = $this->getEspecialButacaSesionByIdEspecialButacaSesion($id_especial_butaca_sesion);
        $id_butaca_arr = $EspecialButacaSesion->getIdButacaArr(); 
        $count_butacas_reservadas = 0;
        $index_butaca_sesion_reservadas_arr = array();
        $index_butaca_sesion_reservadas_arr['butacas'] =array();
        foreach($id_butaca_arr as $id_butaca){
            if($count_butacas_reservadas>=$num_butacas){
                $index_butaca_sesion_reservadas_arr['status'] ='success';
                return $index_butaca_sesion_reservadas_arr; //ha podido bloqueadas $num_butacas
            }
            $id_zona = $id_butaca["id_zona"];
            $id_butaca = $id_butaca["id_butaca"];
            $ButacaSesion = $this->getButacaSesion($id_zona, $id_butaca);
            
            if($ButacaSesion->getEstado()=="libre"){
                $ButacaSesion->setEstado(5);
                $index_butaca_sesion_reservadas_arr['butacas'][$count_butacas_reservadas]['id_zona'] = $id_zona;
                $index_butaca_sesion_reservadas_arr['butacas'][$count_butacas_reservadas]['id_butaca'] = $id_butaca;
                $Butaca = new Application_Model_Butaca();
                $Butaca->load($id_zona, $ButacaSesion->getIdButaca());
                $Zona = new Application_Model_Zona();
                $Zona->load($id_zona);
                $index_butaca_sesion_reservadas_arr['butacas'][$count_butacas_reservadas]['nombre_zona'] = $Zona->getNombre();
                $index_butaca_sesion_reservadas_arr['butacas'][$count_butacas_reservadas]['num_butaca'] = $Butaca->getNum();
                $index_butaca_sesion_reservadas_arr['butacas'][$count_butacas_reservadas]['fila_butaca'] = $Butaca->getFila();
                $count_butacas_reservadas = $count_butacas_reservadas+1;
            }
        }
        if($count_butacas_reservadas<$num_butacas){// no ha podido reservar $num_butacas libero las bloqueadas
            $index_butaca_sesion_reservadas_arr['status'] ='error';
            foreach($index_butaca_sesion_reservadas_arr["butacas"] as $index){
                $this->_ButacaSesion_arr[$index]->setEstado(3);//se liberan las bloqueadas
            }
        }else{
            $index_butaca_sesion_reservadas_arr['status'] ='success';
        }
        return $index_butaca_sesion_reservadas_arr; 
    }
    
    /**
     * devuelve  true si ha podido Bloquear $num_butacas para esa zona, no bloqueará butacas en la zona de Especiales
     * devuelve  false si no ha podido Bloquear $num_butacas para esa zona , no marcará ninguna como reservada
     * @param int $id_zona
     * @param int $num_butacas
     * @return array 
     * Ejemplo de array de retorno:
     * 
     * $index_butaca_sesion_reservadas_arr['status']= 'success'; 
     * $index_butaca_sesion_reservadas_arr['butacas'][0]['id_zona']= 2;   
     * $index_butaca_sesion_reservadas_arr['butacas'][0]['id_butaca']= 1;   
     * $index_butaca_sesion_reservadas_arr['butacas'][1]['id_zona']= 2;   
     * $index_butaca_sesion_reservadas_arr['butacas'][1]['id_butaca']= 2; 
     * $index_butaca_sesion_reservadas_arr['butacas'][2]['id_zona']= 2;   
     * $index_butaca_sesion_reservadas_arr['butacas'][2]['id_butaca']= 3; 
     * $index_butaca_sesion_reservadas_arr['butacas'][3]['id_zona']= 2;   
     * $index_butaca_sesion_reservadas_arr['butacas'][3]['id_butaca']= 4;  
     */
    public function BloquearTimeButacasByIdZonaAndNumButacas($id_zona,$num_butacas){
        
        $ButacaSesion_arr = $this->getButacasSesionQueNoPertenecenAEspecialesSesion();//Quitamos las Bustacas que pertenecen a Especial
        
        $count_butacas_reservadas = 0;
        $index_butaca_sesion_reservadas_arr = array();
        $index_butaca_sesion_reservadas_arr['butacas'] =array();
        foreach($ButacaSesion_arr as $index=>$ButacaSesion){
            if($count_butacas_reservadas>=$num_butacas){
                $index_butaca_sesion_reservadas_arr['status'] ='success';
                return $index_butaca_sesion_reservadas_arr; //ha podido bloqueadas $num_butacas
            }
            if($ButacaSesion->getIdZona()==$id_zona && $ButacaSesion->getEstado()=="libre"){
                $ButacaSesion->setEstado(5);
                $index_butaca_sesion_reservadas_arr['butacas'][$count_butacas_reservadas]['id_zona'] = $id_zona;
                $index_butaca_sesion_reservadas_arr['butacas'][$count_butacas_reservadas]['id_butaca'] = $ButacaSesion->getIdButaca();
                $Butaca = new Application_Model_Butaca();
                $Butaca->load($id_zona, $ButacaSesion->getIdButaca());
                $Zona = new Application_Model_Zona();
                $Zona->load($id_zona);
                $index_butaca_sesion_reservadas_arr['butacas'][$count_butacas_reservadas]['nombre_zona'] = $Zona->getNombre();
                $index_butaca_sesion_reservadas_arr['butacas'][$count_butacas_reservadas]['num_butaca'] = $Butaca->getNum();
                $index_butaca_sesion_reservadas_arr['butacas'][$count_butacas_reservadas]['fila_butaca'] = $Butaca->getFila();
                $count_butacas_reservadas = $count_butacas_reservadas+1;
            }
        }
        if($count_butacas_reservadas<$num_butacas){// no ha podido reservar $num_butacas libero las bloqueadas
            $index_butaca_sesion_reservadas_arr['status'] ='error';
            foreach($index_butaca_sesion_reservadas_arr["butacas"] as $index){
                $this->_ButacaSesion_arr[$index]->setEstado(3);//se liberan las bloqueadas
            }
        }else{
            $index_butaca_sesion_reservadas_arr['status'] ='success';
        }
        return $index_butaca_sesion_reservadas_arr;
    }
    
    /**
     * Importante Este metodo Tiene que tener en cuanta para diversas utilizaciones, siempre si se usa para
     * el proceso de reserva de butacas una restriccion en la vista y es que no pueden llegar 
     * butacas a reservar que pertenezcan a un tipo zona y que tambien existan como parte del modelado de alguna Especial Sesion
     * si es tipo  Zona
     * 
     * devuelve  true si ha podido Bloquear $num_butacas para esa zona
     * devuelve  false si no ha podido Bloquear $num_butacas para esa zona , no marcará ninguna como reservada
     * @param int $id_zona
     * @param array $butacas_arr
     * $butacas_arr['butacas'][0]['id_zona']= 2;   
     * $butacas_arr['butacas'][0]['id_butaca']= 1;   
     * $butacas_arr['butacas'][1]['id_zona']= 2;   
     * $butacas_arr['butacas'][1]['id_butaca']= 2; 
     * $butacas_arr['butacas'][2]['id_zona']= 2;   
     * $butacas_arr['butacas'][2]['id_butaca']= 3; 
     * $butacas_arr['butacas'][3]['id_zona']= 2;   
     * $butacas_arr['butacas'][3]['id_butaca']= 4;  
     * 
     * @param int $num_butacas
     * @return array 
     * 
     * Ejemplo de array de retorno:
     * 
     * $index_butaca_sesion_reservadas_arr['status']= 'success'; 
     * $index_butaca_sesion_reservadas_arr['butacas'][0]['id_zona']= 2;   
     * $index_butaca_sesion_reservadas_arr['butacas'][0]['id_butaca']= 1;   
     * $index_butaca_sesion_reservadas_arr['butacas'][0]['nombre_zona']= 1;   
     * $index_butaca_sesion_reservadas_arr['butacas'][0]['num_butaca']= 1;   
     * $index_butaca_sesion_reservadas_arr['butacas'][0]['fila_butaca']= 1;   
     * $index_butaca_sesion_reservadas_arr['butacas'][1]['id_zona']= 2;   
     * $index_butaca_sesion_reservadas_arr['butacas'][1]['id_butaca']= 2;
     * $index_butaca_sesion_reservadas_arr['butacas'][1]['nombre_zona']= 1;   
     * $index_butaca_sesion_reservadas_arr['butacas'][1]['num_butaca']= 1;   
     * $index_butaca_sesion_reservadas_arr['butacas'][1]['fila_butaca']= 1;  
     * $index_butaca_sesion_reservadas_arr['butacas'][2]['id_zona']= 2;   
     * $index_butaca_sesion_reservadas_arr['butacas'][2]['id_butaca']= 3;
     * $index_butaca_sesion_reservadas_arr['butacas'][2]['nombre_zona']= 1;   
     * $index_butaca_sesion_reservadas_arr['butacas'][2]['num_butaca']= 1;   
     * $index_butaca_sesion_reservadas_arr['butacas'][2]['fila_butaca']= 1;
     * $index_butaca_sesion_reservadas_arr['status']= 'success';
     */
    public function BloquearTimeButacasByIdZonaAndButacasArr($id_zona,$butacas_arr,$num_butacas){
        $count_butacas_reservadas = 0;
        $index_butaca_sesion_reservadas_arr = array();
        $index_butaca_sesion_reservadas_arr['butacas'] =array();
        foreach($butacas_arr as $id_butaca){
            if($count_butacas_reservadas>=$num_butacas){
                $index_butaca_sesion_reservadas_arr['status'] ='success';
                return $index_butaca_sesion_reservadas_arr; //ha podido bloqueadas $num_butacas
            }
            $id_zona = $id_butaca["id_zona"];
            $id_butaca = $id_butaca["id_butaca"];
            $ButacaSesion = $this->getButacaSesion($id_zona, $id_butaca);
            
            if($ButacaSesion->getEstado()=="libre"){
                $ButacaSesion->setEstado(5);
                $index_butaca_sesion_reservadas_arr['butacas'][$count_butacas_reservadas]['id_zona'] = $id_zona;
                $index_butaca_sesion_reservadas_arr['butacas'][$count_butacas_reservadas]['id_butaca'] = $id_butaca;
                $Butaca = new Application_Model_Butaca();
                $Butaca->load($id_zona, $ButacaSesion->getIdButaca());
                $Zona = new Application_Model_Zona();
                $Zona->load($id_zona);
                $index_butaca_sesion_reservadas_arr['butacas'][$count_butacas_reservadas]['nombre_zona'] = $Zona->getNombre();
                $index_butaca_sesion_reservadas_arr['butacas'][$count_butacas_reservadas]['num_butaca'] = $Butaca->getNum();
                $index_butaca_sesion_reservadas_arr['butacas'][$count_butacas_reservadas]['fila_butaca'] = $Butaca->getFila();
                $count_butacas_reservadas = $count_butacas_reservadas+1;
            }
        }
        if($count_butacas_reservadas<$num_butacas){// no ha podido reservar $num_butacas libero las bloqueadas
            $index_butaca_sesion_reservadas_arr['status'] ='error';
            foreach($index_butaca_sesion_reservadas_arr["butacas"] as $index){
                $this->_ButacaSesion_arr[$index]->setEstado(3);//se liberan las bloqueadas
            }
        }else{
            $index_butaca_sesion_reservadas_arr['status'] ='success';
        }
        return $index_butaca_sesion_reservadas_arr; 
    }
    
    /**
    * Borra lareferencia  en _ButacaSesion_arr indizada con  $id_zona y $id_butaca en Sesion y su "estado" para esta sesion
    * @throws Exception buscarIdZonaIdButacaInButacaSesionArr
    */  
    public function desbloquearButacaSesion($id_zona,$id_butaca){
        $index = $this->buscarIdZonaIdButacaInButacaSesionArr($id_zona, $id_butaca);
        $ButacaSesion = $this->_ButacaSesion_arr[$index];
        $ButacaSesion->setEstado(3);//3 es el estado libre
    } 
    
    /**
     * 
     * @return int numero de butacas desbloqueadas
     */
    public function DesBloquearTimeButacasByDateTime(){
        $this->_ButacaSesion_arr;
        $count_butacas_desbloqueadas = 0;
        foreach($this->_ButacaSesion_arr as $ButacaSesion){
            $estado_butaca = $ButacaSesion->getEstado();
            $temp=explode ('****', $estado_butaca);
            $estado_butaca = $temp[0];
            if($estado_butaca=="bloqueada_time"){//si es bloqueada_time
                $DateTime_butaca = new DateTime($temp[1]);
                $DateTime_now = new DateTime(date("Y-m-d H:i:s"));//fecha actual
                $reg_reserva_butacas=Zend_Registry::get('reserva_butacas');
                $tiempo_espera_compra=$reg_reserva_butacas['minutos_espera'];    
                $interval_desde_reserva = $DateTime_now->diff($DateTime_butaca);
                $flag_libera_butaca = false;
                if($interval_desde_reserva->format('%Y')>= 1) $flag_libera_butaca = true;
                if($interval_desde_reserva->format('%m')>= 1) $flag_libera_butaca = true;
                if($interval_desde_reserva->format('%d')>= 1) $flag_libera_butaca = true;
                if($interval_desde_reserva->format('%h')>= 1) $flag_libera_butaca = true;
                if($interval_desde_reserva->format('%i')>= $tiempo_espera_compra) $flag_libera_butaca = true;
                if($flag_libera_butaca){
                    $ButacaSesion->setEstado(3);//las libera 
                    $count_butacas_desbloqueadas = $count_butacas_desbloqueadas+1;
                }
            }
        }
        return $count_butacas_desbloqueadas;
    }
    
    /**
     * @param int $estado
     * @param int $id_zona
     * @param int $id_butaca
     * @return boolean true si ha sido capazar de cambiar el estado false si no
     */
    public function setEstadoButacaByIdZonaAndIdButaca($estado,$id_zona,$id_butaca){
        try{
            $ButacaSesion = $this->getButacaSesion($id_zona, $id_butaca);
            $ButacaSesion->setEstado($estado);
            return false;
        }  catch (Exception $e){
            return false;
        }
    }
    
    /**
     * devuelve si true si quedan butacas libres para sesion false si no queda ninguna libre
     * @return boolean
     */
    public function esSesionAgotada(){
        $this->_ButacaSesion_arr;
        foreach($this->_ButacaSesion_arr as $ButacaSesion){
            if($ButacaSesion->getEstado()=="libre"){
                return 0;
            }
        }
        return 1;
    }
    
    
    /**
     * devuelve el numero de butacas libres de la Zona identificada con $id_zona
     * @return integer
     */
    public function getButacasLibresByIdZona($id_zona){
        $contador = 0;
        foreach($this->_ButacaSesion_arr as $ButacaSesion){
            if($ButacaSesion->getEstado()=="libre" && $ButacaSesion->getIdZona()==$id_zona) $contador = $contador+1;
        }
        return $contador;
    }
    
    /**
     * devuelve el numero de butacas libres dado un array con informacion de butacas
     * @return integer
     */
    public function getButacasLibresByArrayIdZonaIdButaca($butaca_info_arr){
        $contador = 0;
        foreach ($butaca_info_arr as $butaca_info){
            foreach($this->_ButacaSesion_arr as $ButacaSesion)
                if($ButacaSesion->getEstado()=="libre" && $ButacaSesion->getIdZona()==$butaca_info['id_zona'] && $ButacaSesion->getIdButaca()==$butaca_info['id_butaca']) 
                    $contador++;
        }
        return $contador;
    }
    
    /**
     * Devuelve max(id_agrupador)+1 encotrado, sirve para cojer el siguiente libre
     * @return integer
     */
    public function getIdAgrupadorLibre(){
        $id_agrupador_max = 0;
        $flag_existe_agrupador = false;
        //para ZonaSesion
        foreach($this->_ZonaSesion_arr as $ZonaSesion){
               if($ZonaSesion->getIdAgrupador()>$id_agrupador_max){
                   $id_agrupador_max=$ZonaSesion->getIdAgrupador();
                   $flag_existe_agrupador=true;            
               }
               //los descuentos
               $descuento_arr = $ZonaSesion->getIdDescuentoArr();
               foreach($descuento_arr  as $descuento){
                       if($descuento["id_agrupador"]>$id_agrupador_max){
                           $descuento["id_agrupador"];
                            $flag_existe_agrupador=true;   
                       }
               }
        }
        //para EspecialButacaSesion
        foreach($this->_EspecialButacaSesion_arr as $EspecialButacaSesion){
               if($EspecialButacaSesion->getIdAgrupador()>$id_agrupador_max){
                   $id_agrupador_max=$EspecialButacaSesion->getIdAgrupador();
                   $flag_existe_agrupador=true;   
               }
               //los descuentos
               $descuento_arr = $EspecialButacaSesion->getIdDescuentoArr();
               foreach($descuento_arr  as $descuento){
                       if($descuento["id_agrupador"]>$id_agrupador_max){
                           $descuento["id_agrupador"];
                           $flag_existe_agrupador=true;   
                       }
               }
        }
        //para CanalesSesion
        foreach($this->_CanalesSesion_arr as $CanalesSesion){
               if($CanalesSesion->getIdAgrupador()>$id_agrupador_max){
                   $id_agrupador_max=$CanalesSesion->getIdAgrupador();
                   $flag_existe_agrupador=true;   
               }
               //los descuentos
               $descuento_arr = $CanalesSesion->getIdDescuentoArr();
               foreach($descuento_arr  as $descuento){
                       if($descuento["id_agrupador"]>$id_agrupador_max){
                           $descuento["id_agrupador"];
                           $flag_existe_agrupador=true;   
                       }
               }
        }
        if($flag_existe_agrupador=true)$id_agrupador_max = $id_agrupador_max+1;//si no se ha cambiado tiene qeu ser cero para que id_agrupador vaya de 0 a n
        return $id_agrupador_max;
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
        $string_xml.="\t<id_obra>".$this->_id_obra."</id_obra>\n";
        $string_xml.="\t<flag_agotada>".$this->_flag_agotada."</flag_agotada>\n";
        $string_xml.="\t<date_time>".htmlentities($this->_date_time, ENT_XML1)."</date_time>\n";
        $string_xml.="\t<pvp_taquilla>".$this->_pvp_taquilla."</pvp_taquilla>\n";
        $string_xml.="\t<id_sala>".$this->_id_sala."</id_sala>\n";
        
        $string_xml.="\t<canales_sesion>\n";
        foreach($this->_CanalesSesion_arr as $CanalSesion){
            $string_xml.= $CanalSesion->getAsXml();
        }   
        $string_xml.="\t</canales_sesion>\n";
        
        $string_xml.="\t<especiales_butaca_sesion>\n";
        foreach($this->_EspecialButacaSesion_arr as $EspecialButacaSesion){
            $string_xml.= $EspecialButacaSesion->getAsXml();
        }   
        $string_xml.="\t</especiales_butaca_sesion>\n";
        
        $string_xml.="\t<zonas_sesion>\n";
        foreach($this->_ZonaSesion_arr as $ZonaSesion){
            $string_xml.= $ZonaSesion->getAsXml();
        }   
        $string_xml.="\t</zonas_sesion>\n";
        
        $string_xml.="\t<butacas_sesion>\n";
        foreach($this->_ButacaSesion_arr as $ButacaSesion){
            $string_xml.= $ButacaSesion->getAsXml();
        }   
        $string_xml.="\t</butacas_sesion>\n";
   
        return $string_xml;
    }    
    
    /**
    * @param integer $last_insert_id el id donde se inserto el artefacto
    * @param array[] $params_arr tiene que contener, $params_arr["id_obra"]
    * Este metodo abastracto
    * Permite implementar logicas adicionales de write()
    */  
    protected function addSpecificProducto($params_arr,$last_insert_id){
        $Obra = new Application_Model_Obra();
        $Obra->load($params_arr["id_obra"]);
        $Obra->addRefSesion($last_insert_id);
        $Obra->write(); 
    }

    /**
    * @param array[] $params_arr  parametros que puede necesitar el metodo protected writeSpecificArtefacto($params_arr)
    * Este metodo abastracto
    * Permite implementar logicas adicionales de write()
    */  
    protected function writeSpecificProducto($params_arr){}   
    
    /**
    * @param array[] $params_arr tiene que contener, $params_arr["id_obra"]
    * Este metodo abastracto
    * Permite implementar logicas adicionales de delete()
    */  
    protected function deleteSpecificProducto($params_arr){
        $Obra = new Application_Model_Obra();
        $Obra->load($params_arr["id_obra"]);
        $Obra->deleteRefSesion($this->_id);
        $Obra->write();  
    }
    
}





