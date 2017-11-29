<?php

class CarritoController extends Zend_Controller_Action
{

    public function init()
    {
        $this->_helper->layout()->disableLayout();
        $this->_helper->viewRenderer->setNoRender(true);
        $this->view->vista_arr = array(); 
        $this->view->idioma =  $this->_getParam('idioma', 'es');
    }

    public function postDispatch()
    {
        $this->_helper->json($this->view->vista_arr);
    }

    public function getCarritoAction()
    {
        $this->_helper->viewRenderer->setNoRender(true);
        /*
        include APPLICATION_PATH.'/../docs/XtremePrograming/FrontTicketing/view/testFrontTicketing.php';
        $testTicketing= new testFrontTicketing();
        $this->view->vista_arr = $testTicketing->getCarrito();
        */
        try{
            $CarritoCompra = new Application_Model_CarritoCompra($this->view->SessionEticketSecure->getIdSession(),$this->view->SessionEticketSecure->getIdUsuario());
            $CarritoCompra->load();
            $this->view->vista_arr['getCarrito'] = $CarritoCompra->getLineaCarritoArrViewFormat();
            $this->view->vista_arr["status"] = "success";
        } catch (Exception $e) {
            $this->view->vista_arr["status"] = "error";
            $this->view->vista_arr["error"] = $e->getMessage();
        }
        
    }

    public function addButacasSesionToCarritoAction()
    {        
        //Test
        /*include APPLICATION_PATH.'/../docs/XtremePrograming/FrontTicketing/view/testFrontTicketing.php';
        $testTicketing= new testFrontTicketing();
        $this->view->vista_arr = $testTicketing->getTestvistaArrAddButacasSesionToCarritoInput(1);
        $json = json_encode($this->view->vista_arr);
         */
        //fin Test
        
        $json = $this->_getParam('json', -1); 
        if($json==-1){//si no lo detectamos por Get(nos llega asi desde la llamada AJAX del paso2) lo intentamos con por session  $this->view->SessionEticketSecure->getJsonAddButacasSesionToCarrito();
            $json = $this->view->SessionEticketSecure->getJsonAddButacasSesionToCarrito();
        }else{
            $this->view->SessionEticketSecure->setJsonAddButacasSesionToCarrito($json); 
        }
        $id_sesion = $this->_getParam('id_sesion', -1); 
        try{
            
            if ($json==NULL || $id_sesion==-1){
                throw new Exception('No json detectado o id_sesion');
            }else{
                $this->view->vista_arr  = json_decode($json, true);
            }
            
            //se comprueba que no se ha superado el limite de butacas a comprar por 1 proceso de compra
            $reg_reserva_butacas=Zend_Registry::get('reserva_butacas');
            $maximo_butacas_por_proceso=$reg_reserva_butacas['maximo_butacas_por_proceso'];
            try{//Si no existe carrito la primera vez saltará una excepcion la calculamos
                $CarritoCompra = new Application_Model_CarritoCompra($this->view->SessionEticketSecure->getIdSession(),$this->view->SessionEticketSecure->getIdUsuario()); 
                $CarritoCompra->load();
                $total_butacas = $CarritoCompra->getNumButacasInCarrito();//añadimos las que ya pueda tener el carrito
            }  catch (Exception $e){
                $total_butacas = 0;
            }
     
            foreach($this->view->vista_arr as $vista){
                $total_butacas = $total_butacas + $vista['info_reserva']['num_butacas'];
            }
                       
            if($total_butacas>$maximo_butacas_por_proceso){
                throw new Exception('MaximoButacasPorProcesoException');
            }
            //fin se comprueba que no se ha superado el limite de butacas a comprar por 1 proceso de compra
            
            //Se Comprueba el Rol con mas permisos encontrado
            $rol_necesario = "guest";
            foreach ($this->view->vista_arr as $item){
                if ($item['descuento']['role_id']!='guest'){
                    if (!$this->view->ACL->inheritsRole($rol_necesario, $item['descuento']['role_id'])){
                        $rol_necesario = $item['descuento']['role_id'];
                    }
                }
            }
            //Fin Se Comprueba el Rol con mas permisos encontrado
  
            //si el $rol_necesario es igual que al que tenemos en session o esta heredado en el que tenemos en session true
            if($rol_necesario==$this->view->SessionEticketSecure->getRolId() || $this->view->ACL->inheritsRole($this->view->SessionEticketSecure->getRolId(), $rol_necesario)){ 
                    
                    //introducimos dentro de $this->view->vista_arr la informacion de los id_agrupador para
              
                    //fin introducimos dentro de $this->view->vista_arr la informacion de los id_agrupador para

                
                    //Reservar butacas;
                    $semaforo=Zend_Registry::get('semaforo');
                    $id_semaforo = $semaforo["butacas"]["id"];
                    //inicio exclusividad semaforo pago
                    $sem_id = sem_get($id_semaforo, 1);
                    $Sesion = new Application_Model_Sesion();
                    $Sesion->load($id_sesion);
                    foreach($this->view->vista_arr as $index=>$vista){
                        if($vista['tipo']=="zona"){
                            $id_zona = $vista['id'];
                            $num_butacas = $vista['info_reserva']['num_butacas'];
                            if(isset($vista['info_reserva']['butacas'])){
                                $index_butaca_sesion_reservadas_arr = $Sesion->BloquearTimeButacasByIdZonaAndButacasArr($id_zona,$vista['info_reserva']['butacas'],$num_butacas); 
                            }else{
                                $index_butaca_sesion_reservadas_arr = $Sesion->BloquearTimeButacasByIdZonaAndNumButacas($id_zona, $num_butacas); 
                            }
                        }else if($vista['tipo']=="especial_butaca_sesion"){
                            $id_especial_butaca_sesion = $vista['id'];
                            $num_butacas = $vista['info_reserva']['num_butacas'];
                            if(isset($vista['info_reserva']['butacas'])){
                                $index_butaca_sesion_reservadas_arr = $Sesion->BloquearTimeButacasByIdZonaAndButacasArr($id_zona,$vista['info_reserva']['butacas'],$num_butacas);             
                            }else{
                                $index_butaca_sesion_reservadas_arr = $Sesion->BloquearTimeByIdEspecialButacaSesionAndNumButacas($id_especial_butaca_sesion, $num_butacas);   
                            }

                        }
                        $this->view->vista_arr[$index]['info_reserva']=$index_butaca_sesion_reservadas_arr; 
                    }
                    //fin exclusividad semaforo pago
                    $this->view->SessionEticketSecure->setJsonAddButacasSesionToCarrito(NULL);// lo ponemos a NULL
                    sem_release($sem_id);
                    //fin Reservar Butacas

                    //Test 2
                    //include APPLICATION_PATH.'/../docs/XtremePrograming/FrontTicketing/view/testFrontTicketing.php';
                    //$testTicketing= new testFrontTicketing();
                    //$this->view->vista_arr = $testTicketing->getTestvistaArrAddButacasSesionToCarritoOutput(1);
                    //fin Test 2   


                    //Comprobar que todas las reservas han sido satisfactorias
                    $flag_to_carrito = true;
                    foreach ($this->view->vista_arr as $item){
                        if ($item['info_reserva']['status']=='error'){
                            $flag_to_carrito = false;
                            break;
                        }
                    }
                    if ($flag_to_carrito){//saltar al paso 3 
                        //write de la reserva en el carrito
                        $CarritoCompra->AddLineasCarritoSesionDesdeArrayAddLineasCarritoSesionDesdeArrayAddButacasSesionToCarrito($this->view->idioma,$Sesion,$this->view->vista_arr);
                        //// fin write de la reserva en el carrito
                        $Sesion->write();//si el carrito se ha guardado correctamente hacemos un write a sesion con los cambios de butacas reservadas
                        //Fin de Se Comprueba el Rol con mas permisos encontrado 
                        $temp_arr = $this->view->vista_arr;
                        $this->view->vista_arr= array();
                        $this->view->vista_arr["addButacaSesionToCarrito"] = $temp_arr;
                        $this->view->vista_arr["status"] = "success";
                        $this->_helper->redirector('paso3','index','frontTicketing');
                    }else {//saltar al paso 2 con un alert de que las butacas ya no estaban disponibles
                        //saltar al paso 2 con un alert de que ha habido un error $e en el proceso de reserva
                        $temp_arr = $this->view->vista_arr;
                        $this->view->vista_arr= array();
                        $this->view->vista_arr["addButacaSesionToCarrito"] = $temp_arr;
                        $this->view->vista_arr["status"] = "error";
                        $this->view->vista_arr["error"] = "NoButacasLibresException";
                        $this->view->SessionEticketSecure->setJsonaddButacasSesionToCarrito("");
                        $this->_helper->redirector('paso2','index','frontTicketing',array("id_sesion"=>$id_sesion,"json"=>json_encode($this->view->vista_arr)));
                    }
                    
            }else{
                $params_arr = array('id_sesion' =>$id_sesion,
                                    'accion' =>$this->view->accion,
                                    'controlador' =>$this->view->controlador,
                                    'modulo' =>$this->view->modulo,
                                    'flag_json' =>$this->view->flag_json);
                $this->_helper->redirector('login','usuario','',$params_arr);
            }
        }catch (Exception $e) {
            //saltar al paso 2 con un alert de que ha habido un error $e en el proceso de reserva
            $temp_arr = $this->view->vista_arr;
            $this->view->vista_arr= array();
            $this->view->vista_arr["addButacaSesionToCarrito"] = $temp_arr;
            $this->view->vista_arr["status"] = "error";
            $this->view->vista_arr["error"] = $e->getMessage();
            $this->view->vista_arr["errorTraceAsString"] = urlencode($e->getTraceAsString());
            $this->view->SessionEticketSecure->setJsonaddButacasSesionToCarrito("");
            $this->_helper->redirector('paso2','index','frontTicketing',array("id_sesion"=>$id_sesion,"json"=>json_encode($this->view->vista_arr)));
     
        }
    }

    public function deleteSesionToCarritoAction()
    { 
        try {
            $id_sesion = $this->_getParam('id_sesion', -1);
            if($id_sesion == -1) throw new Exception('deleteSesionToCarritoActionException');
            $CarritoCompra = new Application_Model_CarritoCompra($this->view->SessionEticketSecure->getIdSession(),$this->view->SessionEticketSecure->getIdUsuario());
            $CarritoCompra->load();
            $linea_compra_arr = $CarritoCompra->getLineaCarritoCompraArrByIdProducto($id_sesion);
                foreach($linea_compra_arr as $id_agrupador =>$Agrupador){
                    foreach($Agrupador as $id_linea_carrito_compra=>$LineaCarritoCompra){           
                        $this->_setParam("id_linea_carrito_compra", $id_linea_carrito_compra);
                        $this->deleteLineaCompraSesionToCarritoAction();
                     }
                }
            $this->view->vista_arr["status"] = "success";
        } catch (Exception $e) {
            $this->view->vista_arr["status"] = "error";
            $this->view->vista_arr["error"] = $e->getMessage();
        }  
    }

    public function deleteLineaCompraSesionToCarritoAction()
    {
        try {
            $id_linea_carrito_compra = $this->_getParam('id_linea_carrito_compra', -1);
            if($id_linea_carrito_compra == -1) throw new Exception('deleteLineaCompraSesionToCarritoActionException');
            $LineaCarritoCompraSesion = new Application_Model_LineaCarritoCompraSesion();
            $LineaCarritoCompraSesion->load($id_linea_carrito_compra);
            $id_sesion = $LineaCarritoCompraSesion->getIdProducto();

            $Sesion = new Application_Model_Sesion();
            $Sesion->load($id_sesion);
            $id_zona = $LineaCarritoCompraSesion->getIdZona();
            $id_butaca = $LineaCarritoCompraSesion->getIdButaca(); 
            $Sesion->desbloquearButacaSesion($id_zona, $id_butaca);
            $Sesion->write();
            $LineaCarritoCompraSesion->delete();
            
            //borrar todos los de ese carrito para ese $id_sesion
            $CarritoCompra = new Application_Model_CarritoCompra($this->view->SessionEticketSecure->getIdSession(),$this->view->SessionEticketSecure->getIdUsuario());
            $CarritoCompra->load();
            if(!$CarritoCompra->existeIdProductoInCarritoCompra($id_sesion)){// si ya no existe ninguna linea con$id_sesion borramos los complementos también.
                $linea_compra_arr = $CarritoCompra->getLineaCarritoCompraArrByIdParent($id_sesion);
                foreach($linea_compra_arr as $id_agrupador =>$Agrupador){
                    foreach($Agrupador as $id_linea_carrito_compra=>$LineaCarritoCompra){
                        $CarritoCompra->deleteLineaCarrito($id_agrupador,$id_linea_carrito_compra);
                     }
                }
            }
            //fin  borrar todos los de ese carrito para ese $id_sesion
            $this->view->vista_arr["status"] = "success";
        } catch (Exception $e) {
            $this->view->vista_arr["status"] = "error";
            $this->view->vista_arr["error"] = $e->getMessage();
        }
    }
    
    public function addLineaCompraRestauracionToCarritoAction()
    {        
        try {
            $id_restauracion =  $this->_getParam('id_restauracion', -1); 
            $id_categoria =  $this->_getParam('id_categoria', -1); 
            $id_sesion =  $this->_getParam('id_sesion', -1); 
            $cantidad = $this->_getParam('cantidad', 0); 
            if($id_restauracion == -1) throw new Exception('addLineaCompraRestauracionToCarritoActionException');
            if($id_categoria == -1) throw new Exception('addLineaCompraRestauracionToCarritoActionException');
            if($id_sesion == -1) throw new Exception('addLineaCompraRestauracionToCarritoActionException');
            if($cantidad < 1) throw new Exception('addLineaCompraRestauracionToCarritoActionException');
            $Restauracion = new Application_Model_Restauracion($id_categoria);
            $Restauracion->load($id_restauracion);
            $id_producto = $id_restauracion;
            $tipo_linea_producto= "LineaCarritoCompraRestauracion";
            $pvp = $Restauracion->getPvp();
            $iva = $Restauracion->getIva();
            $id_session = $this->view->SessionEticketSecure->getIdSession();
            $id_usuario = $this->view->SessionEticketSecure->getIdUsuario();
            $id_compra = NULL;

            $nombre=$Restauracion->getNombre($this->view->idioma);; 
            $date_time =$Restauracion->getDateTime(); 
            $descripcion_servicio =$Restauracion->getDescripcionServicioArr($this->view->idioma); 
            $detalles =$Restauracion->getDetallesArr($this->view->idioma); 
            $more=$Restauracion->getMoreArr($this->view->idioma); 
            $nombre_local= $Restauracion->getNombreLocal(); 
            $telf_local =$Restauracion->getTelfLocal(); 
            $web_local =$Restauracion->getWebLocal(); 
            $pais_local =$Restauracion->getPaisLocal(); 
            $provincia_local=$Restauracion->getProvinciaLocal();  
            $poblacion_local =$Restauracion->getPoblacionLocal(); 
            $cp_local =$Restauracion->getCPLocal(); 
            $direccion_local =$Restauracion->getDireccionLocal(); 
            $pvp_anterior=$Restauracion->getPvpAnterior(); 
            
            $CarritoCompra = new Application_Model_CarritoCompra($id_session, $id_usuario);
            $CarritoCompra->load();
            if($CarritoCompra->existeIdProductoAndIdParentInCarritoCompra($id_producto, $id_sesion)){            //comprobamos que esa LineaRestauracion no existe ya en CarritoCompra para ese $id_sesion
                $LineaCarritoCompraRestauracion_arr = $CarritoCompra->getLineaCarritoCompraArrByIdProductoAndIdParent($id_producto, $id_sesion);
                foreach($LineaCarritoCompraRestauracion_arr as $id_agrupador =>$Agrupador){//Devolvera solo un valor pero para entrar en la forma de formatear las LineasCarritoCompra de Carrito
                    foreach($Agrupador as $id_linea_carrito_compra=>$LineaCarritoCompraRestauracion){
                        $LineaCarritoCompraRestauracion->setCantidad($cantidad);//Damos por echo que esta Tipado a LineaCarritoCompraRestauracion porque conocemos el $id_producto que es un id_resutauracion
                        $LineaCarritoCompraRestauracion->write();
                    }
                }
            }else{
                $LineaCarritoCompraRestauracion = new Application_Model_LineaCarritoCompraRestauracion(NULL, $id_producto, $id_sesion, $tipo_linea_producto, $cantidad, $pvp, $iva, $id_session, $id_usuario, $id_compra, $nombre, $date_time, $descripcion_servicio, $detalles, $more, $nombre_local, $telf_local, $web_local, $pais_local, $provincia_local, $poblacion_local, $cp_local, $direccion_local, $pvp_anterior);
                $id_linea_carrito_compra = $LineaCarritoCompraRestauracion->add();
            }
            $this->view->vista_arr["status"] = "success";
            $this->view->vista_arr['id_linea_carrito_compra'] = $id_linea_carrito_compra;
        } catch (Exception $e) {
            $this->view->vista_arr["status"] = "error";
            $this->view->vista_arr["error"] = $e->getMessage();
        }

    }

    public function setCantidadLineaCompraRestauracionToCarritoAction()
    {
        try {
            $id_linea_carrito_compra =  $this->_getParam('id_linea_carrito_compra', -1); 
            $cantidad = $this->_getParam('cantidad', 0); 
            if($id_linea_carrito_compra == -1) throw new Exception('addLineaCompraRestauracionToCarritoActionException');
            if($cantidad == -1) throw new Exception('addLineaCompraRestauracionToCarritoActionException');

            $LineaCarritoCompraRestauracion = new Application_Model_LineaCarritoCompraRestauracion();
            $LineaCarritoCompraRestauracion->load($id_linea_carrito_compra);


            $LineaCarritoCompraRestauracion->setCantidad($cantidad);
            $LineaCarritoCompraRestauracion->write();
            $this->view->vista_arr["status"] = "success";
        } catch (Exception $e) {
            $this->view->vista_arr["status"] = "error";
            $this->view->vista_arr["error"] = $e->getMessage();
        }

    }

    public function deleteLineaCompraRestauracionToCarritoAction()
    {
        try {
            $id_linea_carrito_compra =  $this->_getParam('id_linea_carrito_compra', -1); 
            if($id_linea_carrito_compra == -1) throw new Exception('deleteLineaCompraRestauracionToCarritoActionException');

            $LineaCarritoCompraRestauracion = new Application_Model_LineaCarritoCompraRestauracion();
            $LineaCarritoCompraRestauracion->load($id_linea_carrito_compra);
            $LineaCarritoCompraRestauracion->delete();
            $this->view->vista_arr["status"] = "success";
        } catch (Exception $e) {
            $this->view->vista_arr["status"] = "error";
            $this->view->vista_arr["error"] = $e->getMessage();
        }

    }
    
    public function addLineaCompraRegaloToCarritoAction()
    {
        try {
            $id_regalo =  $this->_getParam('id_regalo', -1); 
            $id_categoria =  $this->_getParam('id_categoria', -1); 
            $cantidad = $this->_getParam('cantidad', 0); 
            $ruta_imagen = $this->_getParam('cantidad', ""); 
            $nombre_receptor_regalo = $this->_getParam('cantidad', ""); 
            $email_receptor_regalo = $this->_getParam('cantidad', ""); 
            if($id_regalo == -1) throw new Exception('addLineaCompraRegaloToCarritoActionException');
            if($cantidad < 1) throw new Exception('addLineaCompraRegaloToCarritoActionException');
            $Regalo = new Application_Model_Regalo();
            $Regalo->load($id_regalo);
            $id_producto = $id_regalo;
            $tipo_linea_producto= "LineaCarritoCompraRegalo";
            $pvp = $Regalo->getPvp();
            $iva = $Regalo->getIva();
            $id_session = $this->view->SessionEticketSecure->getIdSession();
            $id_usuario = $this->view->SessionEticketSecure->getIdUsuario();
            $id_compra = NULL;

            $nombre=$Regalo->getNombre($this->view->idioma);; 
            $date_time =$Regalo->getDateTime(); 
            $descripcion_servicio =$Regalo->getDescripcionServicioArr($this->view->idioma); 
            $detalles =$Regalo->getDetallesArr($this->view->idioma); 
            
            $CarritoCompra = new Application_Model_CarritoCompra($id_session, $id_usuario);
            $CarritoCompra->load();
            if($CarritoCompra->existeIdProductoAndIdParentInCarritoCompra($id_producto, NULL)){            //comprobamos que esa LineaRestauracion no existe ya en CarritoCompra para ese $id_sesion
                $LineaCarritoCompraRegalo_arr = $CarritoCompra->getLineaCarritoCompraArrByIdProductoAndIdParent($id_producto, NULL);
                foreach($LineaCarritoCompraRegalo_arr as $id_agrupador =>$Agrupador){//Devolvera solo un valor pero para entrar en la forma de formatear las LineasCarritoCompra de Carrito
                    foreach($Agrupador as $id_linea_carrito_compra=>$LineaCarritoCompraRegalo){
                        $LineaCarritoCompraRegalo->setCantidad($cantidad);//Damos por echo que esta Tipado a LineaCarritoCompraRestauracion porque conocemos el $id_producto que es un id_resutauracion
                        $LineaCarritoCompraRegalo->write();
                    }
                }
            }else{
                $LineaCarritoCompraRegalo = new Application_Model_LineaCarritoCompraRegalo(NULL, $id_producto, NULL, $tipo_linea_producto, $cantidad, $pvp, $iva, $id_session, $id_usuario, $id_compra, $nombre, $date_time, $descripcion_servicio, $detalles,$ruta_imagen,$nombre_receptor_regalo,$email_receptor_regalo);
                $id_linea_carrito_compra = $LineaCarritoCompraRegalo->add();
            }
            $this->view->vista_arr["status"] = "success";
            $this->view->vista_arr['id_linea_carrito_compra'] = $id_linea_carrito_compra;
        } catch (Exception $e) {
            $this->view->vista_arr["status"] = "error";
            $this->view->vista_arr["error"] = $e->getMessage();
        }

    }

    public function setCantidadLineaCompraRegaloToCarritoAction()
    {
        try {
            $id_linea_carrito_compra =  $this->_getParam('id_linea_carrito_compra', -1); 
            $cantidad = $this->_getParam('cantidad', 0); 
            if($id_linea_carrito_compra == -1) throw new Exception('addLineaCompraRegaloToCarritoActionException');
            if($cantidad == -1) throw new Exception('addLineaCompraRegaloToCarritoActionException');

            $LineaCarritoCompraRegalo = new Application_Model_LineaCarritoCompraRegalo();
            $LineaCarritoCompraRegalo->load($id_linea_carrito_compra);


            $LineaCarritoCompraRegalo->setCantidad($cantidad);
            $LineaCarritoCompraRegalo->write();
            $this->view->vista_arr["status"] = "success";
        } catch (Exception $e) {
            $this->view->vista_arr["status"] = "error";
            $this->view->vista_arr["error"] = $e->getMessage();
        }

    }

    public function deleteLineaCompraRegaloToCarritoAction()
    {
        try {
            $id_linea_carrito_compra =  $this->_getParam('id_linea_carrito_compra', -1); 
            if($id_linea_carrito_compra == -1) throw new Exception('deleteLineaCompraRegaloToCarritoActionException');

            $LineaCarritoCompraRegalo = new Application_Model_LineaCarritoCompraRegalo();
            $LineaCarritoCompraRegalo->load($id_linea_carrito_compra);
            $LineaCarritoCompraRegalo->delete();
            $this->view->vista_arr["status"] = "success";
        } catch (Exception $e) {
            $this->view->vista_arr["status"] = "error";
            $this->view->vista_arr["error"] = $e->getMessage();
        }

    }
    


}



