<?php

class Regalo_ReservasAdminController extends Zend_Controller_Action
{

    public function init()
    {
        $idioma = Zend_Registry::get('idioma'); 
        $this->_idioma = $idioma['default'];
        $this->view->idioma =  $this->_getParam('idioma', 'ca');
        $this->view->flag_json =  $this->_getParam('flag_json', 0);
    }
    
    public function postDispatch()
    {
        if ($this->view->flag_json){
            //Con el parametro de PAGE ya cargamos el array con la lista de sesiones esperada (para hacer el test creamo vista_arr_2 en testFrontTicketing.php)
            $this->_helper->json($this->view->vista_arr); 
        }
        
    }

    public function indexAction()
    {
        // action body
    }

    public function buscarLocalizadorRegaloAction()
    {
        $this->_helper->layout()->disableLayout();
        $this->view->vista_arr = array();
        $form = new Regalo_Form_BuscarLocalizadorRegalo();
        $form ->setAction($this->_helper->url->url(array('idioma' => $this->view->idioma, 'module' => 'regalo', 'controller' => 'reservas-admin', 'action' => 'buscar-localizador-regalo'),null, true)); 
        if ($this->getRequest()->isPost()){
            $formData = $this->getRequest()->getPost();
            if ($form->isValid($formData)){
                $localizador_compra=$form->getElement('localizador_compra')->getValue();
                $Compra = new Application_Model_Compra();
                try{
                    $Compra->loadByLocalizador($localizador_compra);
                    if (!$Compra->getPagada() && $Compra->getProcesada()) throw new Exception();
                    $CarritoCompra = $Compra->getCarrito();
                    $LineaCarritoCompraRegalo_arr = $CarritoCompra->getLineaCarritoCompraArrType("LineaCarritoCompraRegalo");
                    $LineaCarritoCompraRegalo = current(current($LineaCarritoCompraRegalo_arr));//entramos en la linea saltamos id_agrupador y id_linea_carrito_compra con los dos currents ya que sabemos que siempre habra solo una linea para este proceso, es un proces de compra unica
                    $this->view->vista_arr["linea_carrito_compra_regalo"]["info"]["id_linea_carrito_compra_regalo"]=$LineaCarritoCompraRegalo->getId();
                    $this->view->vista_arr["linea_carrito_compra_regalo"]["info"]["nombre"]=$LineaCarritoCompraRegalo->getNombre();
                    $this->view->vista_arr["linea_carrito_compra_regalo"]["info"]["descripcion_servicio"]=$LineaCarritoCompraRegalo->getDescripcion_servicio();
                    $this->view->vista_arr["linea_carrito_compra_regalo"]["info"]["detalles"]=$LineaCarritoCompraRegalo->getDetalles();
                    $this->view->vista_arr["linea_carrito_compra_regalo"]["info"]["cantidad"]=$LineaCarritoCompraRegalo->getCantidad();
                    $this->view->vista_arr["linea_carrito_compra_regalo"]["info"]["fecha_insercion"]=$LineaCarritoCompraRegalo->getFechaInsercion();
                    $this->view->vista_arr["linea_carrito_compra_regalo"]["info"]["nombre_receptor"]=$LineaCarritoCompraRegalo->getNombreReceptorRegalo();    
                    $Usuario = new Application_Model_Usuario();
                    $Usuario->load($LineaCarritoCompraRegalo->getIdUsuario());
                    $this->view->vista_arr["linea_carrito_compra_regalo"]["info_comprador"]["nombre"]=$Usuario->getNombre();
                    $this->view->vista_arr["linea_carrito_compra_regalo"]["info_comprador"]["apellidos"]=$Usuario->getApellidos();
                    $this->view->vista_arr["linea_carrito_compra_regalo"]["info_comprador"]["email"]=$Usuario->getEmail(); 
                    $id_compra = $LineaCarritoCompraRegalo->getIdCompra();
                    $this->view->vista_arr["compra"]["info"]['id_compra']=$id_compra;
                    
                    $ModuleRegaloReservasDb = new Regalo_Model_DbTable_ModuleRegaloReservas();
                    $this->view->vista_arr["reserva"] = $ModuleRegaloReservasDb->getByIdCompra($id_compra);
                    $status="success";
                    $message="localizador_valido_reservado";
                } catch (Exception $e) {
                    if($e->getCode()!=3){// error code $ModuleRegaloReservasDb->getByIdCompra($id_compra)
                        $status="error";
                        $message = "localizador_no_valido";
                    }else{ 
                        $status="success";
                        $message="localizador_valido_no_reservado";
                        $ruta = Zend_Registry::get('ruta');
                        $this->view->vista_arr['link_reserva'] = $ruta['dominio'].$ruta['base'].'/'.$this->view->idioma.'/regalo/reservar-usuario/index/p1/'.urlencode(base64_encode($id_compra)).'/p2/'.urlencode(md5($Usuario->getEmail()));
                    }
                }  
           }else{   
                    $status="error";
                    $message="localizador_no_valido";
           }
           $mode="busqueda";
        }else{
            $status="succes";
            $message="";
            $mode="inicio";
        }      
        $this->view->vista_arr["status"]=$status;
        $this->view->vista_arr["message"]=$message;
        $this->view->vista_arr["mode"]=$mode;
        $this->view->vista_arr["form"]= $form;
        //print_r($this->view->vista_arr);
        //die();
    }
    
    public function cambiarEstadoLocalizadorRegaloAction()
    {
        $id_compra = $this->_getParam('p1', -1); 
        $this->view->vista_arr=array();
        $ModuleRegaloReservasDb = new Regalo_Model_DbTable_ModuleRegaloReservas();
        try{
            $reserva_arr = $ModuleRegaloReservasDb->getByIdCompra($id_compra);
            $ModuleRegaloReservasDb->setRedimidoById($reserva_arr["id"], 1);
            $this->view->vista_arr["status"]="success";
        } catch (Exception $ex) {
            $this->view->vista_arr["status"]="error";
        }
    }
    
    public function buscarLocalizador2x1Action()
    {
        // action body
    }

    public function cambiarEstadoLocalizador2x1Action()
    {
        // action body
    }


}









