<?php

class Application_Model_CarritoCompra
{
    /**
     * LineaCarritoCompraArr[$id_agrupador][$id_linea_carrito]
     * @var type 
     */
    private $_LineaCarritoCompra_arr;
    private $_id_session;
    private $_id_usuario;
    private $_CarritoCompraDbTable;

    public function __construct($id_session,$id_usuario,$LineaCarritoCompraArr=array()){
        
        $this->_CarritoCompraDbTable = new Application_Model_DbTable_CarritoCompra();  
        $this->_LineaCarritoCompra_arr= $LineaCarritoCompraArr;
        $this->_id_session=$id_session;
        $this->_id_usuario=$id_usuario;

    }
    

    public function load(){
        $data=$this->_CarritoCompraDbTable->getCarritoByIdSession($this->_id_session);
        $this->loadInternal($data);
    }
    
    public function loadByIdCompra($id_compra){
        $data=$this->_CarritoCompraDbTable->getCarritoByIdCompra($id_compra);
        $this->loadInternal($data);
    }
    
    private function loadInternal($data){
        $this->_LineaCarritoCompra_arr= array();
        foreach($data as $linea_carrito){
            $id_linea_carrito= $linea_carrito["id"];
            $tipo_linea_producto= $linea_carrito["tipo_linea_producto"];
            $ObjectCarrito=NULL;
            switch ($tipo_linea_producto) {
                case "LineaCarritoCompraSesion":
                    $ObjectCarrito= new Application_Model_LineaCarritoCompraSesion();
                    break;
                case "LineaCarritoCompraRestauracion":
                    $ObjectCarrito= new Application_Model_LineaCarritoCompraRestauracion();  
                    break;
                case "LineaCarritoCompraPack":
                    $ObjectCarrito= new Application_Model_LineaCarritoCompraPack();                 
                    break;
                case "LineaCarritoCompraRegalo":
                    $ObjectCarrito= new Application_Model_LineaCarritoCompraRegalo();                 
                    break;
            }
            $ObjectCarrito->load($id_linea_carrito);
            $this->_LineaCarritoCompra_arr[$ObjectCarrito->getIdAgrupador()][$id_linea_carrito]=$ObjectCarrito;
        }   
    }
    
    /**
     * 
     * @return float redondeado a 1 digito
     */
    public function getPvpConIvaCarrito(){
        $LineasCarritoCompra_arr = $this->getLineaCarritoCompraArr();
        $pvp_iva = 0;
        foreach($LineasCarritoCompra_arr as $Agrupador){
            foreach($Agrupador as $LineaCarritoCompra)
            {   
                $pvp_iva_parcial = $LineaCarritoCompra->getPvp()+($LineaCarritoCompra->getPvp()*($LineaCarritoCompra->getIva()/100));
                $pvp_iva = $pvp_iva + $pvp_iva_parcial;
                $pvp_iva = $pvp_iva*$LineaCarritoCompra->getCantidad();
            }
        }
        return round($pvp_iva,1);
    }
    /**
     * 
     * @return float redondeado a 1 digito
     */
    public function getPvpCarrito(){
        $LineasCarritoCompra_arr = $this->getLineaCarritoCompraArr();
        $pvp_iva = 0;
        foreach($LineasCarritoCompra_arr as $Agrupador){
            foreach($Agrupador as $LineaCarritoCompra)
            {   
                $pvp_iva_parcial = $LineaCarritoCompra->getPvp();
                $pvp_iva = $pvp_iva + $pvp_iva_parcial;
                $pvp_iva = $pvp_iva*$LineaCarritoCompra->getCantidad();
            }
        }
        return round($pvp_iva,1);
    }

    
    /**
     * Retorna solo el tipo solicitado sigue indizado $this->_LineaCarritoCompra_arr[$id_linea_carrito_compra]
     * @param string type
     * type puede ser:
     * LineaCarritoCompraSesion
     * LineaCarritoCompraRestauracion
     * LineaCarritoCompraPack
     * @return array ($id_grupador => ($id_linea_carrito_compra => $LineaCarritoCompra)) 
     */
    public function getLineaCarritoCompraArrType($type){
        if($type == "LineaCarritoCompraSesion" | $type == "LineaCarritoCompraRestauracion"| $type == "LineaCarritoCompraPack" | $type == "LineaCarritoCompraRegalo"){
            $lineaCarritoCompraArr = array();
            foreach($this->_LineaCarritoCompra_arr as $id_agrupador =>$Agrupador){
                foreach($Agrupador as $id_linea_carrito_compra=>$LineaCarritoCompra){
                    if($LineaCarritoCompra->getTipoLineaProducto()==$type){
                        $lineaCarritoCompraArr[$id_agrupador][$id_linea_carrito_compra] = $LineaCarritoCompra;
                    }
                }
            }
            return $lineaCarritoCompraArr;
        }else{
            throw new Exception('getLineaCarritoCompraArrTypeException');
        }
    }
    
    /**
     * Retorna el numero de lineas de ese tipo que tiene el carrito
     * @param string type
     * type puede ser:
     * LineaCarritoCompraSesion
     * LineaCarritoCompraRestauracion
     * LineaCarritoCompraPack
     * @return int 
     */
    public function getCountLineaCarritoCompraArrType($type){
        return count($this->getLineaCarritoCompraArrType($type));
    }
    
    /**
     * Setea todas las lineas de ese carrito al ultimo idCompra para esa sesion no usada.
     */
    public function setCompra($id_forma_pago){
        //encuentro el id_compra si no existe creo la compra con la primera  linea de compra.
        $id_compra = null;
        $flag_crear_compra = true;
        foreach($this->_LineaCarritoCompra_arr as $id_agrupador =>$Agrupador){
            foreach($Agrupador as $id_linea_carrito_compra=>$LineaCarritoCompra){
                $id_compra = $LineaCarritoCompra->getIdCompra();
                if($id_compra!=null && $id_compra!=""){
                    $flag_crear_compra = false;
                    break;
                }
            }
            if($id_compra!=null && $id_compra!=""){
                break;
            }
        }
        if($flag_crear_compra){// si no existe compra la creo y si no asigno esa compa a todo el carrito
            $Compra = new Application_Model_Compra(NULL, $this->_id_usuario, date("Y-m-d h:i:s"), $id_forma_pago, 0, 0, 0, NULL, 0);
            $id_compra = $Compra->add($this);
        }
        
        foreach($this->_LineaCarritoCompra_arr as $id_agrupador =>$Agrupador){
            foreach($Agrupador as $id_linea_carrito_compra=>$LineaCarritoCompra){
                $LineaCarritoCompra->setIdCompra($id_compra);
                try{//ponemos el try para las lineas que ya tienen el $id_compra setado no lanzen excepcion y paren la ejecucion
                    $LineaCarritoCompra->write();
                }catch(Exception $e){
                    
                }
 
            }
        }
    }
    
    /**
     * 
     * @return int Retorna el numero de Butacas que hay en un carrito
     */
    public function getNumButacasInCarrito(){
        $i=0;
        foreach($this->_LineaCarritoCompra_arr as $id_agrupador =>$Agrupador){
            foreach($Agrupador as $id_linea_carrito_compra=>$LineaCarritoCompra){
                if($LineaCarritoCompra->getTipoLineaProducto()=="LineaCarritoCompraSesion"){
                    $i=$i+1;
                }
            }
        }
        return $i;
    }
    
    /**
     * Retorna solo las lineas con id_producto = $id_producto
     * @param int $id_producto
     * @return array ($id_grupador => ($id_linea_carrito_compra => $LineaCarritoCompra))
     */
    public function getLineaCarritoCompraArrByIdProducto($id_producto){
        $lineaCarritoCompraArr = array();
        foreach($this->_LineaCarritoCompra_arr as $id_agrupador =>$Agrupador){
            foreach($Agrupador as $id_linea_carrito_compra=>$LineaCarritoCompra){
                if($LineaCarritoCompra->getIdProducto()==$id_producto){
                    $lineaCarritoCompraArr[$id_agrupador][$id_linea_carrito_compra] = $LineaCarritoCompra;
                }
            }
         }
        return $lineaCarritoCompraArr;
    }
    
    /**
     * Devuelve true si el $id_producto existe en alguna de las lineas del carrito
     * @param int $id_producto
     * @return boolean 
     */
    public function existeIdProductoInCarritoCompra($id_producto){
        $lineaCarritoCompraArr = $this->getLineaCarritoCompraArrByIdProducto($id_producto);
        if(count($lineaCarritoCompraArr)>0){
            return true;
        }else{
            return false;
        }
    }
    
    /**
     * Retorna solo las lineas con id_producto = $id_producto y id_parent = $id_parent 
     * @param int $id_producto
     * @param int $id_parent
     * @return array ($id_grupador => ($id_linea_carrito_compra => $LineaCarritoCompra))
     */
    public function getLineaCarritoCompraArrByIdProductoAndIdParent($id_producto,$id_parent){
        $lineaCarritoCompraArr = array();
        foreach($this->_LineaCarritoCompra_arr as $id_agrupador =>$Agrupador){
            foreach($Agrupador as $id_linea_carrito_compra=>$LineaCarritoCompra){
                if($LineaCarritoCompra->getIdProducto()==$id_producto && $LineaCarritoCompra->getIdParent()==$id_parent){
                    $lineaCarritoCompraArr[$id_agrupador][$id_linea_carrito_compra] = $LineaCarritoCompra;
                }
            }
         }
        return $lineaCarritoCompraArr;
    }
    
    /**
     * Devuelve true si el $id_producto y $id_parent existen en alguna de las lineas del carrito
     * @param int $id_producto
     * @return boolean 
     */
    public function existeIdProductoAndIdParentInCarritoCompra($id_producto,$id_parent){
        $lineaCarritoCompraArr = $this->getLineaCarritoCompraArrByIdProductoAndIdParent($id_producto,$id_parent);
        if(count($lineaCarritoCompraArr)>0){
            return true;
        }else{
            return false;
        }
    }
    
    /**
     * Retorna solo las lineas con id_parent = $id_parent
     * @param int $id_parent
     * @return array ($id_grupador => ($id_linea_carrito_compra => $LineaCarritoCompra))
     */
    public function getLineaCarritoCompraArrByIdParent($id_parent){
        $lineaCarritoCompraArr = array();
        foreach($this->_LineaCarritoCompra_arr as $id_agrupador =>$Agrupador){
            foreach($Agrupador as $id_linea_carrito_compra=>$LineaCarritoCompra){
                if($LineaCarritoCompra->getIdParent()==$id_parent){
                    $lineaCarritoCompraArr[$id_agrupador][$id_linea_carrito_compra] = $LineaCarritoCompra;
                }
            }
         }
        return $lineaCarritoCompraArr;
    }
    
    /**
     * Retorna lienas carrito preparado para mostrar el carrito Graficamente
     * @return array
     */
    public function getLineaCarritoArrViewFormat(){
        $translate = Zend_Registry::get('Zend_Translate');
        $carrito_arr = array();
        $carrito_arr["sesiones"] = array();
        foreach($this->_LineaCarritoCompra_arr as $id_agrupador=>$Agrupador){            
            $id_sesion=-1; //la primera vez siempre entrara a añadir el bloque
            foreach($Agrupador as $id_linea_carrito_compra=>$LineaCarritoCompra){

                if($LineaCarritoCompra->getTipoLineaProducto()=="LineaCarritoCompraSesion"){
                    if($id_sesion != $LineaCarritoCompra->getIdProducto()){//si son sesiones diferetnes lo volvemos a poner este bloque de info si no no 
                        $id_sesion = $LineaCarritoCompra->getIdProducto();
                        $Sesion = new Application_Model_Sesion();
                        $Sesion->load($id_sesion);

                        $Datetime_sesion = new DateTime($Sesion->getDateTime());
                        $fecha = $Datetime_sesion->format('d').' de '.$translate->_('calendar.mesesanyo.'.(int)$Datetime_sesion->format('m')).' '.$Datetime_sesion->format('Y');  

                        $carrito_arr["sesiones"][$LineaCarritoCompra->getIdProducto()]['info_sesion']["fecha"]=$fecha;
                        $carrito_arr["sesiones"][$LineaCarritoCompra->getIdProducto()]['info_sesion']["hora"]=$Datetime_sesion->format('H:i');            

                        $id_obra = $Sesion->getIdObra();
                        $Obra = new Application_Model_Obra();
                        $Obra->load($id_obra);
                        $carrito_arr["sesiones"][$LineaCarritoCompra->getIdProducto()]["obra"]['id_obra']=$id_obra;
                        $carrito_arr["sesiones"][$LineaCarritoCompra->getIdProducto()]["obra"]['nombre']=$Obra->getNombre();
                        $carrito_arr["sesiones"][$LineaCarritoCompra->getIdProducto()]["obra"]['imagen_miniatura']=$Obra->getImagenMiniatura();

                        $id_sala = $Sesion->getIdSala();
                        $Sala = new Application_Model_Sala();
                        $Sala->load($id_sala);
                        $carrito_arr["sesiones"][$LineaCarritoCompra->getIdProducto()]["sala"]['nombre_abreviado']=$Sala->getNombreAbreviado();
                        $carrito_arr["sesiones"][$LineaCarritoCompra->getIdProducto()]["sala"]['nombre']=$Sala->getNombre();
                        $carrito_arr["sesiones"][$LineaCarritoCompra->getIdProducto()]["sala"]['svg_3d']=$Sala->getSvg3D();
                        

                    }//fin bloque para sesiones diferentes
                        
                    //para el $count
                    if(isset($carrito_arr["sesiones"][$LineaCarritoCompra->getIdProducto()]["items"][$id_agrupador])){
                        $count = count($carrito_arr["sesiones"][$LineaCarritoCompra->getIdProducto()]["items"][$id_agrupador]);//count no es n-1 por eso no +1
                    }else{ // si no es es que es el primero
                        $count = 0;
                    }
                    //fin para el $count
                    
                    //info sesion
                    $carrito_arr["sesiones"][$LineaCarritoCompra->getIdProducto()]["items"][$id_agrupador][$count]["tipo"]=$LineaCarritoCompra->getTipo();
                    
                    //Para especial_butaca_sesion
                    if($LineaCarritoCompra->getTipo()=="especial_butaca_sesion"){
                        $carrito_arr["sesiones"][$LineaCarritoCompra->getIdProducto()]["items"][$id_agrupador][$count]["nombre_especial_butaca_sesion"]=$LineaCarritoCompra->getNombreEspecialButacaSesion();
                        $carrito_arr["sesiones"][$LineaCarritoCompra->getIdProducto()]["items"][$id_agrupador][$count]["descripcion_especial_butaca_sesion"]=$LineaCarritoCompra->getDescripcionEspecialButacaSesion();
                        $carrito_arr["sesiones"][$LineaCarritoCompra->getIdProducto()]["items"][$id_agrupador][$count]["id_especial_butaca_sesion"]=$LineaCarritoCompra->getIdEspecialButacaSesion();
                    }
                    //fin Para especial_butaca_sesion
                    
                    $carrito_arr["sesiones"][$LineaCarritoCompra->getIdProducto()]["items"][$id_agrupador][$count]["flag_numerada"]=$LineaCarritoCompra->getFlagNumerada();
                    $carrito_arr["sesiones"][$LineaCarritoCompra->getIdProducto()]["items"][$id_agrupador][$count]["descuento"]['nombre_descuento']=$LineaCarritoCompra->getNombreDescuento();
                    $carrito_arr["sesiones"][$LineaCarritoCompra->getIdProducto()]["items"][$id_agrupador][$count]["descuento"]['pvp']=$LineaCarritoCompra->getPvp();
                    //bucle de butacas
                    if(isset($carrito_arr["sesiones"][$LineaCarritoCompra->getIdProducto()]["items"][$id_agrupador][$count]['info_reserva']['num_butacas'])){
                        $carrito_arr["sesiones"][$LineaCarritoCompra->getIdProducto()]["items"][$id_agrupador][$count]['info_reserva']['num_butacas']= 1+$carrito_arr["sesiones"][$LineaCarritoCompra->getIdProducto()]["items"][$id_agrupador]['info_reserva']['num_butacas']; 
                    }else{
                        $carrito_arr["sesiones"][$LineaCarritoCompra->getIdProducto()]["items"][$id_agrupador][$count]['info_reserva']['num_butacas']=0;   
                    }
                    if(isset($carrito_arr['sesiones'][$LineaCarritoCompra->getIdProducto()]["items"][$id_agrupador][$count]['info_reserva']['butacas'])){
                        $index_butaca_agrupador = 1+max(array_keys($carrito_arr['sesiones'][$LineaCarritoCompra->getIdProducto()]["items"][$id_agrupador][$count]['info_reserva']['butacas']));
                    }else{
                        $index_butaca_agrupador =0;
                    }
                    $carrito_arr["sesiones"][$LineaCarritoCompra->getIdProducto()]["items"][$id_agrupador][$count]['info_reserva']['butacas'][$index_butaca_agrupador]["id_linea_carrito_compra"]=$id_linea_carrito_compra;
                    $carrito_arr['sesiones'][$LineaCarritoCompra->getIdProducto()]["items"][$id_agrupador][$count]['info_reserva']['butacas'][$index_butaca_agrupador]['nombre_zona']=$LineaCarritoCompra->getNombreZona();   
                    $carrito_arr['sesiones'][$LineaCarritoCompra->getIdProducto()]["items"][$id_agrupador][$count]['info_reserva']['butacas'][$index_butaca_agrupador]['num_butaca']=$LineaCarritoCompra->getNumButaca();     
                    $carrito_arr['sesiones'][$LineaCarritoCompra->getIdProducto()]["items"][$id_agrupador][$count]['info_reserva']['butacas'][$index_butaca_agrupador]['fila_butaca']=$LineaCarritoCompra->getFilaButaca();
                    //fin blucle de butacas
                    //fin info sesion
                }
                if($LineaCarritoCompra->getTipoLineaProducto()=="LineaCarritoCompraRestauracion"){//solo contenidas en sesion
                    $carrito_arr['sesiones'][$LineaCarritoCompra->getIdSesion()]['restauraciones'][$LineaCarritoCompra->getIdProducto()]['id_linea_carrito_compra']= $id_linea_carrito_compra;
                    $carrito_arr['sesiones'][$LineaCarritoCompra->getIdSesion()]['restauraciones'][$LineaCarritoCompra->getIdProducto()]['nombre']=$LineaCarritoCompra->getNombre();
                    $carrito_arr['sesiones'][$LineaCarritoCompra->getIdSesion()]['restauraciones'][$LineaCarritoCompra->getIdProducto()]['nombre_local']=$LineaCarritoCompra->getNombreLocal();
                    $carrito_arr['sesiones'][$LineaCarritoCompra->getIdSesion()]['restauraciones'][$LineaCarritoCompra->getIdProducto()]['cantidad']=$LineaCarritoCompra->getCantidad();
                    $carrito_arr['sesiones'][$LineaCarritoCompra->getIdSesion()]['restauraciones'][$LineaCarritoCompra->getIdProducto()]['pvp']=$LineaCarritoCompra->getPvp();
                }
                if($LineaCarritoCompra->getTipoLineaProducto()=="LineaCarritoCompraPack"){

                }
                if($LineaCarritoCompra->getTipoLineaProducto()=="LineaCarritoCompraRegalo"){
                    $carrito_arr['regalos'][$LineaCarritoCompra->getIdProducto()]["items"]['id_linea_carrito_compra']= $id_linea_carrito_compra;
                    $carrito_arr['regalos'][$LineaCarritoCompra->getIdProducto()]["items"]['nombre']=$LineaCarritoCompra->getNombre();
                    $carrito_arr['regalos'][$LineaCarritoCompra->getIdProducto()]["items"]['descripcion_servicio']=$LineaCarritoCompra->getDescripcionServicio();
                    $carrito_arr['regalos'][$LineaCarritoCompra->getIdProducto()]["items"]['cantidad']=$LineaCarritoCompra->getCantidad();
                    $carrito_arr['regalos'][$LineaCarritoCompra->getIdProducto()]["items"]['date_time']=$LineaCarritoCompra->getDateTime();
                    $carrito_arr['regalos'][$LineaCarritoCompra->getIdProducto()]["items"]['descripcion_servicio']=$LineaCarritoCompra->getDescripcionServicio();
                    $carrito_arr['regalos'][$LineaCarritoCompra->getIdProducto()]["items"]['detalles']=$LineaCarritoCompra->getDetalles();
                    $carrito_arr['regalos'][$LineaCarritoCompra->getIdProducto()]["items"]['ruta_imagen']=$LineaCarritoCompra->getRutaImagen();
                    $carrito_arr['regalos'][$LineaCarritoCompra->getIdProducto()]["items"]['nombre_receptor_regalo']=$LineaCarritoCompra->getNombreReceptorRegalo();
                    $carrito_arr['regalos'][$LineaCarritoCompra->getIdProducto()]["items"]['email_receptor_regalo']=$LineaCarritoCompra->getEmailReceptorRegalo();
                }
            }
        }
        return $carrito_arr;
    } 
 
    public function getLineaCarritoCompraArr(){
        return $this->_LineaCarritoCompra_arr;
    } 
    
    /**
     * Cambia el estado de todas las butacas de La Sesion y hace un write contenidas en $this->_LineaCarritoCompra_arr que sean del tipo
     * LineaCarritoCompraSesion al estado $index_estado
     * @param int $index_estado
     */
    public function setEstadoButacasOfSesionAllLineasCarritoCompraSesionInCarrito($index_estado){
        $lineaCarritoCompraArr = $CarritoCompra->getLineaCarritoCompraArrType("LineaCarritoCompraSesion");
        foreach($lineaCarritoCompraArr as $id_agrupador =>$Agrupador){
            foreach($Agrupador as $id_linea_carrito_compra=>$LineaCarritoCompra){
                $id_sesion = $LineaCarritoCompra->getIdProducto();
                $id_zona = $LineaCarritoCompra->getIdZona();
                $id_butaca = $LineaCarritoCompra->getIdButaca();
                $Sesion = new Application_Model_Sesion();
                $Sesion->load($id_sesion);
                $Sesion->setEstadoButacaByIdZonaAndIdButaca($index_estado, $id_zona, $id_butaca);
                $Sesion->write();
            }
        }
    }
    
    public function setLineaCarritoCompraArr($LineaCarritoCompraArr){
        $this->_LineaCarritoCompra_arr=$LineaCarritoCompraArr;
        
    } 
        
    public function getLineaCarritoCompraArrByIdAGrupadorAndIdLineaCarritoCompra($id_agrupador,$id_linea_carrito_compra){
        return $this->_LineaCarritoCompra_arr[$id_agrupador][$id_linea_carrito_compra];
    } 
    
    public function setLineaCarritoCompraArrByIdAGrupadorIdLineaCarritoCompra($id_agrupador,$id_linea_carrito_compra,$LineaCarritoCompra){
        $this->_LineaCarritoCompra_arr[$id_agrupador][$id_linea_carrito_compra]=$LineaCarritoCompra;
        
    } 
    
    public function getIdSession(){
        return $this->_id_session;
    } 
    
    public function getIdUsuario(){
        return $this->_id_usuario;
    } 
    
    public function setIdUsuario($id_usuario){
        $this->_id_usuario=$id_usuario;
    }
    
    /**
     * Retorna los id_sesion de productos sesion que encuentre 
     * en las lineas del carrito de tipo "LineaCarritoCompraSesion"
     * @return array
     */
    public function getIdSesionArrOfLineaCarritoCompraSesion(){
        $id_sesion_arr = array();
        foreach($this->_LineaCarritoCompra_arr as $id_agrupador=>$Agrupador){            
            foreach($Agrupador as $id_linea_carrito_compra=>$LineaCarritoCompra){
                if($LineaCarritoCompra->getTipoLineaProducto() == "LineaCarritoCompraSesion"){
                    $id_sesion_arr[]=$LineaCarritoCompra->getIdProducto();
                }
            }
        }
        $id_sesion_arr=array_unique($id_sesion_arr);
        return $id_sesion_arr;
    }
    
    /**
     * Retorn la linea carrito que tiene ese localizador
     * @param string $localizador
     * @return Object puede retornar cualquier objeto que implemente AbstractLineaCarritoCompra
     * @throws Exception getLineaCarritoCompraByLocalizador si no lo encuentra
     */ 
    public function getLineaCarritoCompraByLocalizador($localizador){
        foreach($this->_LineaCarritoCompra_arr as $id_agrupador=>$Agrupador){            
            foreach($Agrupador as $id_linea_carrito_compra=>$LineaCarritoCompra){
                if($LineaCarritoCompra->getLocalizador() == $localizador){
                    return $LineaCarritoCompra;
                }
            }
        }
        throw new Exception('getLineaCarritoCompraByLocalizador');
    }
    
    /**
     * Retorna los id_compra del carrito Devolvera una excepcion si existen id_compra diferentes para un carrito con la misma sesion
     * @return array
     */
    public function getIdCompra(){
        $id_compra_arr = array();
        foreach($this->_LineaCarritoCompra_arr as $id_agrupador=>$Agrupador){            
            foreach($Agrupador as $id_linea_carrito_compra=>$LineaCarritoCompra){
                $id_compra_arr[]=$LineaCarritoCompra->getIdCompra();          
            }
        }
        $id_compra_arr=array_unique($id_compra_arr);
        if(count($id_compra_arr)>1) throw new Exception('getIdCompra Existe un Error en al configuracion de La compra y el Carrito');
        return $id_compra_arr[0];
    }
    
    private function addLineaCarrito($id_agrupador,$LineaCarritoCompra,$params_add_arr=array()){
        $LineaCarritoCompra->add($params_add_arr);
        if($LineaCarritoCompra->getId()) throw new Exception('addLineaCarrito');
        $this->_LineaCarritoCompra_arr[$id_agrupador][$LineaCarritoCompra->getId()]=$LineaCarritoCompra;
    } 
    
    public function setIdCompraAllCarrito($id_compra){
        foreach($this->_LineaCarritoCompra_arr as $Agrupador){
           foreach($Agrupador as $LineaCarritoCompra){
                $LineaCarritoCompra->setIdCompra($id_compra);
           }
        }
    }
    
    public function writeLineaCarrito($id_agrupador,$id_linea_carrito_compra,$params_write_arr=array()){
        $this->_LineaCarritoCompra_arr[$id_agrupador][$id_linea_carrito_compra]->write($params_write_arr);    
     }  
    
    public function deleteLineaCarrito($id_agrupador,$id_linea_carrito_compra,$params_add_arr=array()){
        $this->_LineaCarritoCompra_arr[$id_agrupador][$id_linea_carrito_compra]->delete($params_add_arr);
        unset ($this->_LineaCarritoCompra_arr[$id_agrupador][$id_linea_carrito_compra]);      
     }
    
    /**
     * Carga el carrito desde Action paso2ComprobarDisponibilidadButacas 
     * del controlador IndexController Modulo FrontTicketing Mirar Ejemplo en TestFrontTicketing.php getTestvistaArrPaso2ContinuarOutputReservadoOk()
     * @param string idioma
     * @param Application_Model_Sesion $Sesion es el Objecti Sesion que viene del Action paso2ComprobarDisponibilidadButacas 
     * @param array $reserva_butacas_arr contiene los datos de reserva del Action paso2ComprobarDisponibilidadButacas 
     * del controlador IndexController Modulo FrontTicketing
     */
    public function AddLineasCarritoSesionDesdeArrayAddLineasCarritoSesionDesdeArrayAddButacasSesionToCarrito($idioma,$Sesion,$reserva_butacas_arr){        
        //variables fijas
        $id = NULL;
        $id_producto = $Sesion->getId();
        $id_parent=0;
        $tipo_linea_producto ="LineaCarritoCompraSesion";
        $cantidad = 1;
        //variables fin fijas
        $id_session = $this->_id_session;
        $id_usuario = $this->_id_usuario; 
        $nombre_sala = new Application_Model_Sala();
        $nombre_sala->load($Sesion->getIdSala());
        $nombre_sala = $nombre_sala->getNombre();
        $nombre_sesion  = $Sesion->getNombre($idioma);
        $date_time_sesion = $Sesion->getDateTime();
        $iva = $Sesion->getIva();
        $id_compra = NULL;
        foreach($reserva_butacas_arr as $reserva_butacas){//una linea por cada zona y especial de $reserva_butacas_arr
            $tipo_arr = array();
            $tipo_arr["tipo"] = $reserva_butacas["tipo"];
            //para especial_butaca_sesion
            if($tipo_arr["tipo"]=="especial_butaca_sesion"){
                $tipo_arr["nombre_especial_butaca_sesion"] = $reserva_butacas["nombre_especial_butaca_sesion"]; 
                $tipo_arr["descripcion_especial_butaca_sesion"] = $reserva_butacas["descripcion_especial_butaca_sesion"]; 
                $tipo_arr["id_especial_butaca_sesion"] = $reserva_butacas["id"]; 
                $EspecialButacaSesion = $Sesion->getEspecialButacaSesionByIdEspecialButacaSesion($reserva_butacas["id"]);
                if(isset($reserva_butacas["descuento"]["id_descuento"])){
                    $id_descuento = $reserva_butacas["descuento"]["id_descuento"];
                    $id_agrupador = $EspecialButacaSesion->getIdAgrupadorByIdDescuento($id_descuento);
                }else{
                    $id_agrupador = $EspecialButacaSesion->getIdAgrupador();
                    $id_descuento = NULL;
                }
            }else{
                $tipo_arr["nombre_especial_butaca_sesion"] = NULL; 
                $tipo_arr["descripcion_especial_butaca_sesion"] = NULL; 
                $tipo_arr["id_especial_butaca_sesion"] = NULL;
                $ZonaSesion = $Sesion->getZonaSesionByIdZona($reserva_butacas["id"]);

                if(isset($reserva_butacas["descuento"]["id_descuento"])){
                    $id_descuento = $reserva_butacas["descuento"]["id_descuento"];
                    $id_agrupador = $ZonaSesion->getIdAgrupadorByIdDescuento($id_descuento);
                }else{
                    $id_agrupador = $ZonaSesion->getIdAgrupador();
                    $id_descuento = NULL;
                }
            }
            //para especial_butaca_sesion             

            $pvp = $reserva_butacas["descuento"]["pvp"];
            $nombre_descuento = $reserva_butacas["descuento"]["nombre_descuento"];
            $flag_numerada = $reserva_butacas["flag_numerada"];

            foreach($reserva_butacas['info_reserva']['butacas'] as $info_reserva_butaca){//creando lineas para cada butaca
                $nombre_zona  = $info_reserva_butaca["nombre_zona"];
                $num_butaca  = $info_reserva_butaca["num_butaca"];
                $fila_butaca  = $info_reserva_butaca["fila_butaca"];
                $id_zona  = $info_reserva_butaca["id_zona"];
                $id_butaca  = $info_reserva_butaca["id_butaca"];
                $LineaCarritoCompraSesion = new Application_Model_LineaCarritoCompraSesion($id, $id_producto, $id_parent, $tipo_linea_producto, $cantidad, $pvp, $iva, $id_session, $id_agrupador, $id_usuario, $id_compra, $tipo_arr, $nombre_sala, $nombre_zona, $num_butaca, $flag_numerada, $fila_butaca, $nombre_sesion, $date_time_sesion, $nombre_descuento, $id_zona, $id_butaca,$id_descuento);
                $LineaCarritoCompraSesion->add(); 
            }
        }  
    }
    
    /**
     * Hace Un Write Sobre todo el Carrito
     * @param array $params_write_arr
     * 
     * all_params_write_arr[0] //id_line_carrito_compra es 0
     * all_params_write_arr[0]["params_write_arr"] = $params_write_arr
     * all_params_write_arr[1]//id_line_carrito_compra es 1
     * all_params_write_arr[1]["params_write_arr"] = $params_write_arr
     */
    public function writeCarrito($all_params_write_arr=NULL){
        foreach($this->_LineaCarritoCompra_arr as $id_linea_carrito_compra=>$Agrupador_arr){
            foreach($Agrupador_arr as $LineaCarritoCompra){
                if($all_params_write_arr==NULL){
                    $params_write_arr = NULL;
                }else{
                    $params_write_arr = $all_params_write_arr[$id_linea_carrito_compra];
                }
                $LineaCarritoCompra->write($params_write_arr); 
            }
        }
     }  
     
    public function cleanCarritoBySession(){
         $data =$this->_CarritoCompraDbTable->cleanCarrito();// las borramos de la BBDD
         $this->_LineaCarritoCompra_arr= array(); //reiniciamos las lineas    
         return $data;
    }
    
    public function deleteCarritoByIdSession(){
         $data =$this->_CarritoCompraDbTable->deleteCarritoByIdSession($this->_id_session);// las borramos de la BBDD
         $this->load();//podriamos buscar la linea pero es mas complejo asi que recargamos todo el carrito para evitar errores.
        return $data;
    }
    
    public function deleteCarritoByIdCompra(){
         $data =$this->_CarritoCompraDbTable->deleteCarritoByIdCompra($this->getIdCompra());// las borramos de la BBDD
         $this->load();//podriamos buscar la linea pero es mas complejo asi que recargamos todo el carrito para evitar errores.
        return $data;
    }
     

}

