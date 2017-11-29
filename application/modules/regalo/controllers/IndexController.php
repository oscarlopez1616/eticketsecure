<?php

class Regalo_IndexController extends Zend_Controller_Action
{

    public function init()
    {
        $this->view->vista_arr = array(); 
        $this->view->idioma =  $this->_getParam('idioma', 'es');
        $this->view->show_header = 1;
        $this->view->show_cambio_idioma = 0;
    }
    
    
    public function indexAction()
    {
        $this->view->show_cambio_idioma = 1;
        $this->view->controlador = 'index';
        $this->view->accion = 'index';
    }

    public function datosCompraAction()
    {
        $id_producto = $this->_getParam('id_producto', -1);
        switch ($id_producto) {
            case -1:
                $this->_helper->redirector->gotoUrl("/".$this->view->idioma.'/regalo/index/index/');
                break;
            case 15:
                $this->view->vista_arr['producto']['imagen'] = 'img-producto-p1-'.$this->view->idioma.'.png';
                $this->view->vista_arr['producto']['pvp'] = '15.5';
                break;
            case 16:
                $this->view->vista_arr['producto']['imagen'] = 'img-producto-p2-'.$this->view->idioma.'.png';
                $this->view->vista_arr['producto']['pvp'] = '10';
                break;
            case 17:
                $this->view->vista_arr['producto']['imagen'] = 'img-producto-p3-'.$this->view->idioma.'.png';
                $this->view->vista_arr['producto']['pvp'] = '16.5';
                break;
            case 18:
                $this->view->vista_arr['producto']['imagen'] = 'img-producto-p3-'.$this->view->idioma.'.png';
                $this->view->vista_arr['producto']['pvp'] = '19.5';
                break;
            case 19:
                $this->view->vista_arr['producto']['imagen'] = 'img-producto-p4-'.$this->view->idioma.'.png';
                $this->view->vista_arr['producto']['pvp'] = '15.5';
                break;
            case 20:
                $this->view->vista_arr['producto']['imagen'] = 'img-producto-p5-'.$this->view->idioma.'.png';
                $this->view->vista_arr['producto']['pvp'] = '15.5';
                break;
            default:
                $this->_helper->redirector->gotoUrl("/".$this->view->idioma.'/regalo/index/index/');
        }       
        
        $form = new Regalo_Form_Regalo();
        $form ->setAction($this->_helper->url->url(array('idioma' => $this->view->idioma, 'module' => 'regalo', 'controller' => 'index', 'action' => 'datos-compra', 'id_producto' => $id_producto),null, true));
        
        $this->view->form= $form;        
        if ($this->getRequest()->isPost()){
            
            $formData = $this->getRequest()->getPost();
            if ($formData['flag_email_receptor']==1) $form->getElement('email_receptor')->setRequired(TRUE);                
            if ($form->isValid($formData)){
                $imagen=$form->getElement('imagen')->getValue();
                $nombre_comprador=$form->getElement('nombre_comprador')->getValue();
                $nombre_receptor=$form->getElement('nombre_receptor')->getValue();
                $numero_entradas=$form->getElement('numero_entradas')->getValue();
                $flag_email_receptor=$form->getElement('flag_email_receptor')->getValue();
                $email_receptor=$form->getElement('email_receptor')->getValue();                
                
                $datos_array = array (
                    'ruta_imagen'           => $imagen,
                    'nombre_comprador'      => $nombre_comprador,
                    'nombre_receptor'       => $nombre_receptor,
                    'numero_entradas'       => $numero_entradas,
                    'flag_email_receptor'   => $flag_email_receptor,
                    'email_receptor'        => $email_receptor,
                    'id_producto'           => $id_producto
                );
                $auth = Zend_Auth::getInstance();
                $auth->clearIdentity();
                $this->view->SessionEticketSecure->setUsuarioGuest();
                $this->view->SessionEticketSecure->reCreaSessionEticketSecure();
                $this->view->SessionEticketSecure->setUserDataArr($datos_array);
                $this->_helper->redirector->gotoUrl('/'.$this->view->idioma.'/regalo/index/confirmar-compra/');
                
           }else{
               $form->populate($formData);                
           }
        }
        $this->view->form = $form;    
    }

    public function confirmarCompraAction()
    {
        $this->view->vista_arr['id_producto']           = $this->view->SessionEticketSecure->getValueUserDataArrByIndex('id_producto', -1);
        $this->view->vista_arr['ruta_imagen']                = $this->view->SessionEticketSecure->getValueUserDataArrByIndex('ruta_imagen', '');
        $this->view->vista_arr['nombre_comprador']      = $this->view->SessionEticketSecure->getValueUserDataArrByIndex('nombre_comprador', '');
        $this->view->vista_arr['nombre_receptor']       = $this->view->SessionEticketSecure->getValueUserDataArrByIndex('nombre_receptor', '');
        $this->view->vista_arr['numero_entradas']       = $this->view->SessionEticketSecure->getValueUserDataArrByIndex('numero_entradas', '');
        $this->view->vista_arr['flag_email_receptor']   = $this->view->SessionEticketSecure->getValueUserDataArrByIndex('flag_email_receptor', '');
        $this->view->vista_arr['email_receptor']        = $this->view->SessionEticketSecure->getValueUserDataArrByIndex('email_receptor', '');
        
        $id_producto = $this->view->vista_arr['id_producto'];
        
        $translate = Zend_Registry::get('Zend_Translate');
        switch ($id_producto) {
            case -1:
                $this->_helper->redirector->gotoUrl("/".$this->view->idioma.'/regalo/index/index/');
                break;
            case 15:
                $this->view->vista_arr['producto']['imagen'] = 'img-producto-p1-'.$this->view->idioma.'.png';
                $this->view->vista_arr['producto']['pvp'] = '15.5';
                $this->view->vista_arr['producto']['nombre'] = $translate->_('ticket.1.nombre-producto');
                break;
            case 16:
                $this->view->vista_arr['producto']['imagen'] = 'img-producto-p2-'.$this->view->idioma.'.png';
                $this->view->vista_arr['producto']['pvp'] = '10';
                $this->view->vista_arr['producto']['nombre'] = $translate->_('ticket.2.nombre-producto');
                break;
            case 17:
                $this->view->vista_arr['producto']['imagen'] = 'img-producto-p3-'.$this->view->idioma.'.png';
                $this->view->vista_arr['producto']['pvp'] = '16.5';
                $this->view->vista_arr['producto']['nombre'] = $translate->_('ticket.3.nombre-producto.1');
                break;
            case 18:
                $this->view->vista_arr['producto']['imagen'] = 'img-producto-p3-'.$this->view->idioma.'.png';
                $this->view->vista_arr['producto']['pvp'] = '19.5';
                $this->view->vista_arr['producto']['nombre'] = $translate->_('ticket.3.nombre-producto.2');
                break;
            case 19:
                $this->view->vista_arr['producto']['imagen'] = 'img-producto-p4-'.$this->view->idioma.'.png';
                $this->view->vista_arr['producto']['pvp'] = '15.5';
                $this->view->vista_arr['producto']['nombre'] = $translate->_('ticket.4.nombre-producto');
                break;
            case 20:
                $this->view->vista_arr['producto']['imagen'] = 'img-producto-p5-'.$this->view->idioma.'.png';
                $this->view->vista_arr['producto']['pvp'] = '15.5';
                $this->view->vista_arr['producto']['nombre'] = $translate->_('ticket.5.nombre-producto');
                break;
            default:
                $this->_helper->redirector->gotoUrl("/".$this->view->idioma.'/regalo/index/index/');
        }       
        
        
        $ruta= Zend_Registry::get('ruta');
        $url_imagen_temporal = $ruta['dominio'].$ruta['base'].$ruta['imagenes']['galeria']['regalo_temporal'].'/'.$this->view->vista_arr['ruta_imagen']; 
        
        $this->view->vista_arr['url_imagen'] = $url_imagen_temporal;
        $this->view->SessionEticketSecure->setUserDataArr($this->view->vista_arr);
    }

    public function tramitarCompraAction()
    {
        $this->_helper->layout->setLayout('procesando-layout');
        $id_session = $this->view->SessionEticketSecure->getIdSession(); 
        $id_usuario = $this->view->SessionEticketSecure->getIdUsuario();
        $id_compra_base_64 = $this->_getParam('p1', -1);

        if($id_compra_base_64!=-1){
            $id_compra = base64_decode($id_compra_base_64);
            $Compra = new Application_Model_Compra();
            try{
                $Compra->load($id_compra);
                $pagada = $Compra->getPagada();
                if($pagada){
                    exit("Esta compra ya ha sido pagada");
                }else{
                    $CarritoCompra = $Compra->getCarrito();
                }
            }catch(Exception $e){//si no existe el load de compra seguimos y la creamos con el tpvsemerpa del interfaz  
                exit("Esta compra es inexistente");
            }
            $LineaCarritoCompraRegalo = $CarritoCompra->getLineaCarritoCompraArrType("LineaCarritoCompraRegalo");
            $LineaCarritoCompraRegalo = current(current($LineaCarritoCompraRegalo));
            $id = NULL;
            $id_producto = $LineaCarritoCompraRegalo->getIdProducto(); 
            $id_parent = NULL; 
            $tipo_linea_producto = "LineaCarritoCompraRegalo"; 
            $cantidad = $LineaCarritoCompraRegalo->getCantidad(); 
            $ruta_imagen = $LineaCarritoCompraRegalo->getRutaImagen();
            $nombre_receptor_regalo = $LineaCarritoCompraRegalo->getNombreReceptorRegalo();
            $email_receptor_regalo = $LineaCarritoCompraRegalo->getEmailReceptorRegalo();
            $nombre_comprador_regalo = $LineaCarritoCompraRegalo->getNombreCompradorRegalo();
            if($id_producto == -1 || $cantidad==-1) throw new Exception('No ha seleccionado ningun producto');  
            $Regalo = new Application_Model_Regalo();
            $Regalo->load($id_producto);
            $pvp = $Regalo->getPvp(); 
            $iva = $Regalo->getIva(); 
            $nombre = $Regalo->getNombre($this->view->idioma );
            $date_time = date('Y-m-d H:m:i'); 
            $descripcion_servicio = $Regalo->getDescripcionServicio($this->view->idioma );
            $detalles =  $Regalo->getDetalles($this->view->idioma );
            $CarritoCompra=NULL;
            $Compra->delete(); 
            $this->view->SessionEticketSecure->reCreaSessionEticketSecure();
            $id_session = $this->view->SessionEticketSecure->getIdSession();
            $LineaCarritoCompraRegalo = new Application_Model_LineaCarritoCompraRegalo($id, $id_producto, $id_parent, $tipo_linea_producto, $cantidad, $pvp, $iva, $id_session, $id_usuario, NULL, $nombre, $date_time, $descripcion_servicio, $detalles,$ruta_imagen,$nombre_receptor_regalo,$email_receptor_regalo,$nombre_comprador_regalo);
            $id_linea_carrito_compra_regalo = $LineaCarritoCompraRegalo->add();
            $CarritoCompra = new Application_Model_CarritoCompra($id_session, $id_usuario);
        }else{    
            $CarritoCompra= new Application_Model_CarritoCompra($id_session, $id_usuario);
            $Compra = new Application_Model_Compra();
            try{
                $CarritoCompra->load();
                $id_compra = $CarritoCompra->getIdCompra();
                $Compra->load($id_compra);
                $pagada = $Compra->getPagada();
                if($pagada){
                    exit("Esta compra ya ha sido pagada");
                }else{
                    $Compra->delete($CarritoCompra); 
                }
            }catch(Exception $e){//si no existe el load de compra seguimos y la creamos con el tpvsemerpa del interfaz
            }
            $id = NULL;        
            $id_producto =  $this->view->SessionEticketSecure->getValueUserDataArrByIndex('id_producto', -1);
            $ruta_imagen =  $this->view->SessionEticketSecure->getValueUserDataArrByIndex('ruta_imagen', "");
            $cantidad =  $this->view->SessionEticketSecure->getValueUserDataArrByIndex('numero_entradas', -1); 
            if($id_producto == -1 || $cantidad==-1) throw new Exception('No ha seleccionado ningun producto');  

            $Regalo = new Application_Model_Regalo();
            $Regalo->load($id_producto);
            $id_parent = NULL; 
            $tipo_linea_producto = "LineaCarritoCompraRegalo"; 
            $pvp = $Regalo->getPvp(); 
            $iva = $Regalo->getIva(); 
            $nombre = $Regalo->getNombre($this->view->idioma );
            $date_time = date('Y-m-d H:m:i'); 
            $descripcion_servicio = $Regalo->getDescripcionServicio($this->view->idioma );
            $detalles =  $Regalo->getDetalles($this->view->idioma );
            $nombre_receptor_regalo =$this->view->SessionEticketSecure->getValueUserDataArrByIndex('nombre_receptor', "");
            $email_receptor_regalo =$this->view->SessionEticketSecure->getValueUserDataArrByIndex('email_receptor', "");
            $nombre_comprador_regalo = $this->view->SessionEticketSecure->getValueUserDataArrByIndex('nombre_comprador', "");
            $LineaCarritoCompraRegalo = new Application_Model_LineaCarritoCompraRegalo($id, $id_producto, $id_parent, $tipo_linea_producto, $cantidad, $pvp, $iva, $id_session, $id_usuario, NULL, $nombre, $date_time, $descripcion_servicio, $detalles,$ruta_imagen,$nombre_receptor_regalo,$email_receptor_regalo,$nombre_comprador_regalo);
            $id_linea_carrito_compra_regalo = $LineaCarritoCompraRegalo->add();
            $CarritoCompra->load();//por el add de lineaCarritoCompraRegalo
        }
        
        $ruta=Zend_Registry::get('ruta');
        $domain = $ruta["dominio"].$ruta["base"]; 
        $redsys = array();
        $redsys_regystry = Zend_Registry::get('redsys');
        $redsys['code']=$redsys_regystry["merchant_code"];
        $redsys['clave'] =$redsys_regystry["clave_comercio"];
        $redsys['url_comercio']=$domain;
        $redsys['url_retorno'] =$domain."/".$this->view->idioma."/regalo/index/retorno-pago/p1/260ca9dd8a4577fc00b7bd5810298076";//p1 es success en md5 para dificultar el entendimiento de la petición
        $redsys['url_cancelada'] =$domain."/".$this->view->idioma."/regalo/index/retorno-pago/p1/cb5e100e5a9a3e7f6d1fd97512215282";//p1 es error en md5 para dificultar el entendimiento de la petición
        $Utiles = new Eticketsecure_Utils_TPV();
        $this->view->vista_arr["form_pago"] = $Utiles->getFormTPVRedsys($CarritoCompra,0,$this->view->idioma, $redsys['url_comercio'], $redsys['url_retorno'], $redsys['url_cancelada'], $redsys['clave'], $redsys['code']);
        
    }

    public function retornoPagoAction()
    {   
        $this->view->show_header = 0;
        $code_md5 = $this->_getParam('p1', -1);
        $id_usuario_base_64 = $this->_getParam('p2', -1);
        $ds_order = $this->_getParam('Ds_Order', -1);
        if($code_md5 == -1 || $id_usuario_base_64 == -1 || $ds_order == -1){
            throw new Exception('1. Error no RedSys Response Ticketex');
        }
        $temp = substr($ds_order,6);
        $id_compra = $temp; 
        
        $SecurizeDefender = new Eticketsecure_Utils_SecurizeDefender();
        $SecurizeDefender->SecurizeCompraByIdCompraAndIdUsuario($id_compra,base64_decode($id_usuario_base_64),"retorno-pago");   

        /*
        $Utiles = new Eticketsecure_Utils_SoapRedsysConsultasRealizadasTpv();
        $consulta_arr = $Utiles->estadoTransaccionByOrder($ds_order);//comprobamos si ha sido pagada y que ha pasado.
         */
        
        $consulta_arr["flag_pagada"]= true;
        $consulta_arr["message"]= "";
                
        $translate = Zend_Registry::get('Zend_Translate');
        if($code_md5 == "260ca9dd8a4577fc00b7bd5810298076" && $consulta_arr["flag_pagada"]){//code_md5 success y ha sido pagada
            $Compra = new Application_Model_Compra();
            try{
                $Compra->load($id_compra);
                $CarritoCompra = $Compra->getCarrito();
                $LineaCarritoCompraRegalo = $CarritoCompra->getLineaCarritoCompraArrType("LineaCarritoCompraRegalo");
                $LineaCarritoCompraRegalo = current(current($LineaCarritoCompraRegalo));//entramos en la linea saltamos id_agrupador y id_linea_carrito_compra con los dos currents ya que sabemos que siempre habra solo una linea para este proceso, es un proces de compra unica

                $user_data_arr['id_producto']           = $LineaCarritoCompraRegalo->getIdProducto();
                $user_data_arr['id_compra']             = $id_compra;
                $user_data_arr['ruta_imagen']           = $LineaCarritoCompraRegalo->getRutaImagen();
                $user_data_arr['nombre_comprador']      = $LineaCarritoCompraRegalo->getNombreCompradorRegalo();
                $Usuario = new Application_Model_Usuario();
                $Usuario->load($Compra->getIdUsuario());
                $user_data_arr['email_comprador']       = $Usuario->getEmail();
                $user_data_arr['nombre_receptor']       = $LineaCarritoCompraRegalo->getNombreReceptorRegalo();
                $user_data_arr['numero_entradas']       = $LineaCarritoCompraRegalo->getCantidad();
                if($LineaCarritoCompraRegalo->getEmailReceptorRegalo()==""){
                   $user_data_arr['flag_email_receptor']   = 0;
                }else{
                   $user_data_arr['flag_email_receptor']   = 1;
                }
                $user_data_arr['email_receptor']        = $LineaCarritoCompraRegalo->getEmailReceptorRegalo();

                $this->view->vista_arr['numero_entradas']  = $user_data_arr['numero_entradas'];
                $this->view->vista_arr['nombre_receptor']  = $user_data_arr['nombre_receptor'];
                $this->view->vista_arr['email_comprador']  = $user_data_arr['email_comprador'];
                $this->view->vista_arr['email_receptor']  = $user_data_arr['email_receptor'];
                $this->view->vista_arr['flag_email_receptor']  = $user_data_arr['flag_email_receptor'];
                $ruta= Zend_Registry::get('ruta');
                $id_compra_base_64_url_encoded  = urlencode(base64_encode($user_data_arr['id_compra']));
                $email_md5_url_encoded = urlencode(md5($user_data_arr['email_comprador']));
                $this->view->vista_arr['link_tarjeta_regalo'] = $ruta['dominio'].$ruta['base'].'/'.$this->view->idioma.'/regalo/index/tarjeta-imprimible-generate/p1/'.$id_compra_base_64_url_encoded.'/p2/'.$email_md5_url_encoded;
                $this->view->vista_arr['link_bono2x1'] = $ruta['dominio'].$ruta['base'].'/'.$this->view->idioma.'/regalo/index/bono2x1-imprimible-generate/p1/'.$id_compra_base_64_url_encoded.'/p2/'.$email_md5_url_encoded;

                $user_data_arr['link_tarjeta_regalo'] = $this->view->vista_arr['link_tarjeta_regalo'];
                $user_data_arr['link_bono2x1'] = $this->view->vista_arr['link_bono2x1'];
                $user_data_arr['link_reservar'] = $ruta['dominio'].$ruta['base'].'/'.$this->view->idioma.'/regalo/reservar-usuario/index/p1/'.$id_compra_base_64_url_encoded.'/p2/'.$email_md5_url_encoded;

                if($user_data_arr['id_producto']==15){
                        $this->view->vista_arr['producto']['imagen'] = 'img-producto-p1-clean-'.$this->view->idioma.'.png';
                        $this->view->vista_arr['producto']['nombre'] = 'Cualquier Obra';
                        $this->view->vista_arr['producto']['pvp'] = '15.5';
                }
                if($user_data_arr['id_producto']==16){
                    $this->view->vista_arr['producto']['imagen'] = 'img-producto-p2-clean-'.$this->view->idioma.'.png';
                    $this->view->vista_arr['producto']['nombre'] = 'Cualquier Obra Familiar';
                    $this->view->vista_arr['producto']['pvp'] = '10';
                }
                if($user_data_arr['id_producto']==17){
                    $this->view->vista_arr['producto']['imagen'] = 'img-producto-p3-clean-'.$this->view->idioma.'.png';
                    $this->view->vista_arr['producto']['nombre'] = 'Cualquier Obra + Complemento';
                    $this->view->vista_arr['producto']['pvp'] = '16.5';
                }
                if($user_data_arr['id_producto']==18){
                    $this->view->vista_arr['producto']['imagen'] = 'img-producto-p3-clean-'.$this->view->idioma.'.png';
                    $this->view->vista_arr['producto']['nombre'] = 'Cualquier Obra + Complemento';
                    $this->view->vista_arr['producto']['pvp'] = '19.5';
                }
                if($user_data_arr['id_producto']==19){
                        $this->view->vista_arr['producto']['imagen'] = 'img-producto-p4-clean-'.$this->view->idioma.'.png';
                        $this->view->vista_arr['producto']['nombre'] = 'Tributo Pepe Rubianes';
                        $this->view->vista_arr['producto']['pvp'] = '15.5';
                }
                if($user_data_arr['id_producto']==20){
                        $this->view->vista_arr['producto']['imagen'] = 'img-producto-p5-clean-'.$this->view->idioma.'.png';
                        $this->view->vista_arr['producto']['nombre'] = 'Orgasmos La Comedia';
                        $this->view->vista_arr['producto']['pvp'] = '15.5';
                }
                $Compra->setProcesada(1);
                $Compra->write();//para que pete si ya ha venido antes a procesarla
                $Compra->setPagada(1);
                $Compra->write();
                // MOVER FOTO //
                if ($user_data_arr['ruta_imagen'] !=''){
                    $ruta= Zend_Registry::get('ruta');
                    $ruta_imagen_temporal = $ruta['server'].$ruta['base'].$ruta['imagenes']['galeria']['regalo_temporal'].'/'.$user_data_arr['ruta_imagen']; 
                    $ruta_imagen_final = $ruta['server'].$ruta['base'].$ruta['imagenes']['galeria']['regalo'].'/uploads/'.$user_data_arr['ruta_imagen']; 
                    copy($ruta_imagen_temporal, $ruta_imagen_final);
                    unlink($ruta_imagen_temporal);
                }
                // MOVER FOTO //

                // ENVIAR EMAIL COMPRADOR // Comentar para probar el email al receptor
                $Mail = new Application_Model_Mail($user_data_arr['email_comprador'], $user_data_arr['nombre_comprador']);
                $Mail->enviaMailRegaloComprador($user_data_arr);
                // ENVIAR EMAIL COMPRADOR //

                // ENVIAR EMAIL RECEPTOR //
                if ($user_data_arr['flag_email_receptor']){
                    $Mail = new Application_Model_Mail($user_data_arr['email_receptor'], $user_data_arr['nombre_receptor']);
                    $Mail->enviaMailRegaloReceptor($user_data_arr);
                }
                // ENVIAR EMAIL RECEPTOR //
                $Utils = new Eticketsecure_Utils_EcommerceAnalytics();
                try{
                    $this->view->vista_arr["analytics_js_send_transaccion"] = $Utils->getJsToSendTransactionToAnalyticsDesdeCarritoCompra($Compra, $this->view->idioma);
                    $this->view->vista_arr["facebook_conversion_regalo"] = '<script type="text/javascript">
var fb_param = {};
fb_param.pixel_id = "6012103957981";
fb_param.value = "9";
fb_param.currency = "EUR";
(function(){
  var fpw = document.createElement("script");
  fpw.async = true;
  fpw.src = "//connect.facebook.net/en_US/fp.js";
  var ref = document.getElementsByTagName("script")[0];
  ref.parentNode.insertBefore(fpw, ref);
})();
</script>
<noscript><img height="1" width="1" alt="" style="display:none" src="https://www.facebook.com/offsite_event.php?id=6012103957981&amp;value=0&amp;currency=EUR" /></noscript>';
                    $this->view->vista_arr["adwords_conversion_regalo"] = '<!-- Google Code for Compra Regala Teatro 2013 Conversion Page -->
<img height="1" width="1" alt="" src="//www.googleadservices.com/pagead/conversion/980018757/imp.gif?value=0&amp;label=8gNqCIPanQcQxcyn0wM&amp;guid=ON&amp;script=0"/>';
                } catch (Exception $e) {// si falla el proceso de capturar transaccion en analytics por lo que fuera continuamos con la compra.
                    $this->view->vista_arr["analytics_js_send_transaccion"] = "";
                    $this->view->vista_arr["facebook_conversion_regalo"] ="";
                    $this->view->vista_arr["adwords_conversion_regalo"] ="";
                }
                $Usuario = new Application_Model_Usuario();
                $Usuario->setActivo(1);//al pagar marcamos al usuario lo marcamos como activo da igual cual era su estado anterior.
                $Usuario->write();
                $this->view->vista_arr["message"] = "Gracias por haber Comprado en GrupTeatreNeu";
                $this->view->vista_arr["status"] = "success";
            } catch (Exception $e) {
                if($e->getMessage()=="setUsuarioById" || $e->getMessage()=="writeUsuario"){//si la excepcion era por marcar el usuario activo, es compra correcta igualmente e informamos de esa forma
                    $this->view->vista_arr["message"] = "Gracias por haber Comprado en GrupTeatreNeu";
                    $this->view->vista_arr["status"] = "success";
                    //aqui no ponemos el $this->view->vista_arr["analytics_js_send_transaccion"] = ""; porque entrara por el writeUsuario tiene que aparecer el codigo de ecommerce
                }else{
                    try{
                        $flag_compra_pagada = $Compra->getPagada();
                    } catch (Exception $ex) {
                        $flag_compra_pagada=0;
                    }
                    if($flag_compra_pagada){
                        $this->view->vista_arr["message"] = $translate->_('retorno.compra.ko.desc.compra_ya_procesada');
                        $this->view->vista_arr["status"] = "success"; 
                        $this->view->vista_arr["analytics_js_send_transaccion"] = "";
                        $this->view->vista_arr["facebook_conversion_regalo"] ="";
                        $this->view->vista_arr["adwords_conversion_regalo"] ="";
                    }else{
                        throw new Exception('2. Error no RedSys Response Ticketex');
                    }
                }
            }
        }else{////code_md5 error o compra no pagada
            $Compra = new Application_Model_Compra();
            try{
                $Compra->load($id_compra);
                $Compra->setProcesada(1);
                $Compra->write();
            } catch (Exception $ex) {}
            $this->view->vista_arr["message"] = $translate->_('retorno.compra.ko.desc.compra_cancelada');
            $this->view->vista_arr["message_tpv"] = $consulta_arr["message"];
            $this->view->vista_arr["id_compra"] = base64_encode($id_compra);
            $this->view->vista_arr["status"] = "error";         
        } 
    }

    public function tarjetaImprimibleGenerateAction()
    {
        $this->_helper->layout()->disableLayout();
        
        //PARAMETROS
        $id_compra_base_64  = urldecode($this->_getParam('p1', -1));
        $email_md5         = urldecode($this->_getParam('p2', -1));
        $render            = $this->_getParam('p3', 0);
 
        $SecurizeDefender = new Eticketsecure_Utils_SecurizeDefender();
        $flag_compra_correcta=$SecurizeDefender->SecurizeCompraPagadaByIdCompraBase64AndEmailMd5($id_compra_base_64, $email_md5,'tarjeta-imprimible-generate');

        if ($flag_compra_correcta){
            $ruta = Zend_Registry::get('ruta');
            if ($render==0){
                $this->_helper->viewRenderer->setNoRender(true);
                $unique_id = uniqid();
                $comandos_curl = Zend_Registry::get('comandos_curl');

                //RUTAS
                $ruta_web_to_pdf = $ruta['dominio'].$ruta['base'].'/'.$this->view->idioma.'/regalo/index/tarjeta-imprimible-generate/p1/'.urlencode($id_compra_base_64).'/p2/'.urlencode($email_md5).'/p3/1';
                $ruta_save_pdf = $ruta["server"].$ruta["base"].$ruta["temporal"];
                $ruta_save_web_pdf= $ruta["dominio"].$ruta["base"].$ruta["temporal"];
                $name_save_pdf = "tarjeta-regalo-".  uniqid().".pdf";
                //CONVERTIR PDF
                $command_wkhtmltopdf = $comandos_curl["wkhtmltopdf"]." ".$ruta_web_to_pdf." ".$ruta_save_pdf."/".$name_save_pdf;
                
				exec($command_wkhtmltopdf);
                
                //DESCARGAR ARCHIVO
                header('Content-Type: application/pdf');
                header('Content-Disposition: attachment; filename="'.$name_save_pdf.'"');
                readfile($ruta_save_web_pdf.'/'.$name_save_pdf);

                //ELIMINAR ARCHIVO
                unlink($ruta_save_pdf."/".$name_save_pdf);
            }else{
                $Compra = new Application_Model_Compra();
                $Compra->load(base64_decode($id_compra_base_64));
                $CarritoCompra= $Compra->getCarrito();
                $LineaCarritoCompraRegalo = $CarritoCompra->getLineaCarritoCompraArrType("LineaCarritoCompraRegalo");
                $LineaCarritoCompraRegalo = current(current($LineaCarritoCompraRegalo));//entramos en la linea saltamos id_agrupador y id_linea_carrito_compra con los dos currents ya que sabemos que siempre habra solo una linea para este proceso, es un proces de compra unica
                
                $this->view->vista_arr['localizador']=$Compra->getLocalizador();
                $this->view->vista_arr['numero_entradas']=$LineaCarritoCompraRegalo->getCantidad();
                $this->view->vista_arr['nombre_producto']=$LineaCarritoCompraRegalo->getNombre();
                $this->view->vista_arr['nombre_receptor']=$LineaCarritoCompraRegalo->getNombreReceptorRegalo();
                $this->view->vista_arr['imagen_personalizada']=$LineaCarritoCompraRegalo->getRutaImagen();
                $RecortadorUrl = new Application_Model_RecortadorUrl();
                $this->view->vista_arr['link_reservar'] = $ruta['dominio'].$ruta['base'].'/'.$this->view->idioma.'/regalo/reservar-usuario/index/p1/'.urlencode($id_compra_base_64).'/p2/'.urlencode($email_md5);
                $id_categoria_url_recortada = 1;//es la de regalo
                $RecortadorUrl->creaUrlRecortadaParaTicketsConLocalizador($this->view->vista_arr['link_reservar'] ,$Compra->getLocalizador(),$id_categoria_url_recortada);
                $this->view->vista_arr['link_reservar_short']=$ruta['dominio'].$ruta['base'].$RecortadorUrl->getUrlRecortada();
                
                $id_producto = $LineaCarritoCompraRegalo->getIdProducto();
                if($id_producto==15){
                    $this->view->vista_arr['imagen_producto']='img-producto-p1-clean-'.$this->view->idioma.'.png';
                }
                if($id_producto==16){
                    $this->view->vista_arr['imagen_producto']='img-producto-p2-clean-'.$this->view->idioma.'.png';
                }
                if($id_producto==17){
                    $this->view->vista_arr['imagen_producto']='img-producto-p3-clean-'.$this->view->idioma.'.png';
                }
                if($id_producto==18){
                    $this->view->vista_arr['imagen_producto']='img-producto-p3-clean-'.$this->view->idioma.'.png';
                }
                if($id_producto==19){
                    $this->view->vista_arr['imagen_producto']='img-producto-p4-clean-'.$this->view->idioma.'.png';
                }
                if($id_producto==20){
                    $this->view->vista_arr['imagen_producto']='img-producto-p5-clean-'.$this->view->idioma.'.png';
                }
            }
        }else{
            throw new Exception('Ticket Regalo Incorrecto');
        }   
        
    }

    public function bono2x1ImprimibleGenerateAction()
    {
        $this->_helper->layout()->disableLayout();
        
        //PARAMETROS
        $id_compra_base_64 = urldecode($this->_getParam('p1', -1));
        $email_md5 = urldecode($this->_getParam('p2', -1));
        $render = $this->_getParam('p3', 0);
 
        $SecurizeDefender = new Eticketsecure_Utils_SecurizeDefender();
        $flag_compra_correcta=$SecurizeDefender->SecurizeCompraPagadaByIdCompraBase64AndEmailMd5($id_compra_base_64, $email_md5,'bono2x1-imprimible-generate');
        
        if ($flag_compra_correcta==true){
            if ($render==0){
                $this->_helper->viewRenderer->setNoRender(true);
                $ruta = Zend_Registry::get('ruta');
                $comandos_curl = Zend_Registry::get('comandos_curl');

                //RUTAS
                $ruta_web_to_pdf = $ruta['dominio'].$ruta['base'].'/'.$this->view->idioma.'/regalo/index/bono2x1-imprimible-generate/p1/'.urlencode($id_compra_base_64).'/p2/'.urlencode($email_md5).'/p3/1';
                $ruta_save_pdf = $ruta["server"].$ruta["base"].$ruta["temporal"];
                $ruta_save_web_pdf= $ruta["dominio"].$ruta["base"].$ruta["temporal"];
                $name_save_pdf = "bono-2x1-".  uniqid().".pdf";
                //CONVERTIR PDF
                $command_wkhtmltopdf = $comandos_curl["wkhtmltopdf"]." ".$ruta_web_to_pdf." ".$ruta_save_pdf."/".$name_save_pdf;
                exec($command_wkhtmltopdf);

                //DESCARGAR ARCHIVO
                header('Content-Type: application/pdf');
                header('Content-Disposition: attachment; filename="'.$name_save_pdf.'"');
                readfile($ruta_save_web_pdf.'/'.$name_save_pdf);

                //ELIMINAR ARCHIVO
                unlink($ruta_save_pdf."/".$name_save_pdf);
            }else{
                $Compra = new Application_Model_Compra();
                $Compra->load(base64_decode($id_compra_base_64));
                $this->view->vista_arr['localizador']=$Compra->getLocalizador();
            }
        }else{
            throw new Exception('Bono 2x1 Incorrecto');
        } 
    }
}
