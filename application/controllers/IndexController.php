<?php

class IndexController extends Zend_Controller_Action
{

    public function init()
    {
        $this->view->vista_arr = array(); 
        $this->view->idioma =  $this->_getParam('idioma', 'es');
    }
    
    public function soapAction(){
        
        $Utiles = new Eticketsecure_Utils_SoapRedsysConsultasRealizadasTpv();
        $order = "121726116";
        $merchant_data = "";
        $Utiles->estadoTransaccionByOrderAndMerchantData($order,$merchant_data);
    }
    
    public function indexAction()
    {      
 
        $this->_helper->redirector->gotoUrl("/".$this->view->idioma.'/regalo/index/index/');
        /*
        $RecortadorUrl = new Application_Model_RecortadorUrl();
        $RecortadorUrl->creaUrlRecortadaParaTicketsConLocalizador("http://regalo.teatreneu.com/es/regalo/index/index",1, 1);
        */
        /*
        $migrate = $this->_getParam('migrate', -1);
        if($migrate!=-1){
            $MigrationUsuerWebTneu = new Eticketsecure_Utils_MigrateClubNeuToTicketex();
            $MigrationUsuerWebTneu->MigrateBbddWebTicketex();
        }
        die();
        */
       /*
        $DefenderIpBaneada = new Application_Model_DefenderIpBaneada("192.168.1.1", date("Y-m-d H:i:m"));
        //$DefenderIpBaneada->add();
        $DefenderIpBaneada->load("192.168.1.1");
        echo $DefenderIpBaneada->getFechaBaneo();
        echo $DefenderIpBaneada->getIp();
        print_r($DefenderIpBaneada);

        die();
/*
        * 
        */
        //DEFENDER
/*
        $IpFunctions = new Eticketsecure_Utils_IpFunctions();
        $real_ip = $IpFunctions->getRealIP();    
        $Defender = new Application_Model_Defender();
        $Defender->loadByNombreProceso('tarjeta-imprimible-generate');
        $ManejadorRelacionDefenderIp = $Defender->startManejadorRelacionDefenderIp($real_ip);
 * 
 */
        /*$ManejadorRelacionDefenderIp->incrementaIntentos();// el throw lanza una Exception  y para la ejecucion. Si el add detecta que ya hemos pasado del umbral de proteccion ip 


        if (!$ManejadorRelacionDefenderIp->esPermitido()){
            $ManejadorRelacionDefenderIp->incrementaIntentos();// el throw lanza una Exception  y para la ejecucion. Si el add detecta que ya hemos pasado del umbral de proteccion ip 
            throw new Exception('Ticket Regalo Incorrecto');
        } */

        /*
        $id = NULL;
        $nombre_proceso = "defender_prueba"; 
        $umbral = 5;
        $Defender = new Application_Model_Defender($id, $nombre_proceso, $umbral);
        //$Defender->add();
        $Defender->load(3);
        $RelacionDefenderIp = $Defender->getRelacionDefenderIpByIp("0.0.0.0");
        echo $RelacionDefenderIp->getIntentos();
        print_r($Defender);
        */
        /*
        $id= NULL; 
        $id_defender= 3; 
        $ip= "0.0.0.0";
        $intentos= 0; 
        $fecha_ultimo_intento= date("Y-m-d H:i:s"); 
        $num_umbrales_excedidos= 0;
        $RelacionDefenderIp = new Application_Model_RelacionDefenderIp();
        //$id_relacion_defender_ip= $RelacionDefenderIp->add();
        $RelacionDefenderIp->load(1);
        print_r($RelacionDefenderIp);
        */


        /*$SessionEticketSecure = new Application_Model_SessionEticketSecure();
        echo $SessionEticketSecure->getIdFechaCreacion();
        die();
        */
        
        /*$arr_to_json = array (0 => 4, 1=>55 );
        echo json_encode($arr_to_json);
        die();*/
        
        
        /*$InterfaceCategoriaStereotypeSesion = new Application_Model_InterfaceCategoriaStereotypeSesion();
        $fecha = new DateTime('2013-08-12 00:00:00');

        
        $id_sesion_datetime_arr = $InterfaceCategoriaStereotypeSesion->getIdProductoArrByDateInterval($fecha,7);
                                                                        
        print_r($id_sesion_datetime_arr);
        die();
        
       $InterfaceGeo = new Application_Model_InterfaceGeoEticketSecure();
       $Coordenadas1 = new Application_Model_Coordenadas("41.406074", "2.162951");
       $Coordenadas2 = new Application_Model_Coordenadas("41.403982", "2.173637");
       $perimetro_km = 0.5;
       
       $data  = $InterfaceGeo->esProximo($Coordenadas1, $Coordenadas2, $perimetro_km);
       
       print_r($data); 
       die();*/
        
        /*
        $Carrito_Compra = new Application_Model_CarritoCompra(null, null);
        $Carrito_Compra->loadByIdCompra(2);
        print_r($Carrito_Compra->getLineaCarritoArrViewFormat());
        */

        /*
        $id_compra=7;
        $semerpa= Zend_Registry::get('semerpa'); 
        $InterfaceFormaPago = new Application_Model_InterfaceFormaPagoEticketSecure();
        $this->view->vista_arr = array();
        
        //$this->view->vista_arr["form_pago"] = $InterfaceFormaPago->formTPVSemerpa($id_compra, $this->view->idioma, $semerpa['url_comercio'], $semerpa['url_retorno'], $semerpa['url_cancelada'], $semerpa['clave'], $semerpa['code']);
        $this->view->vista_arr["form_pago"] = $InterfaceFormaPago->formTPVPayPal($id_compra, $this->view->idioma,"oscar.lopez@e-fbk.com", $semerpa['url_retorno'], $semerpa['url_cancelada']);
        */
        
        /*
        $InterfaceArtefacto = new Application_Model_InterfaceArtefactoEticketSecure();
        print_r($InterfaceArtefacto->getIdArtefactoArrByColeccionXMLAndIdCategoriaArtefactoAndIdArtefactoColeccion('//salas/id_sala', 9, 4));
        die();
        */
        
        /**
        echo date('Y-m-d H:m:i');
        die();
         * 
         */
        
        /*
        $acl = new Eticketsecure_Utils_ACL();
        $acl = $acl->initMySQLACL();
        die();
         * 
         */
        
        /*$InterfaceCategoria = new Application_Model_InterfaceCategoriaEticketSecure();
        $data = $InterfaceCategoria->getIdCategoriaArrByAltura(0);
        print_r($data);
        die();
         * 
         */
   /*     
        $InterfaceCategoriaArtefacto = new Application_Model_InterfaceCategoriaArtefactoEticketSecure();
        $data = $InterfaceCategoriaArtefacto->getIdCategoriaArrArtefactoByAltura(0);
        print_r($data);
        die();
    * 
    */
        
       /* $InterfaceArtefacto = new Application_Model_InterfaceArtefactoEticketSecure();
        $data = $InterfaceArtefacto->getIdArtefactoArrByIdCategoriaArtefacto(2);
        print_r($data);
        die();
        * 
        */
        
/*
        $Actor = new Application_Model_Actor();
        $Actor->load(20);
        echo $Actor->getAsXml();
        die();
*/
        /*
        $Canal = new Application_Model_Canal();
        $Canal->load(25);
        echo $Canal->getAsXml();
        die();
         * 
         */
/*
        $Comentario = new Application_Model_Comentario();
        $Comentario->load(22);
        echo $Comentario->getAsXml();
        die();
 * 
 */
/*
        $Descuento = new Application_Model_Descuento();
        $Descuento->load(23);
        echo $Descuento->getAsXml();
        die();
 * 
 */

/*
        $Obra = new Application_Model_Obra();
        $Obra->load(11);
        echo $Obra->getAsXml();
        die();
 * 
 */

        /*
        $Tag = new Application_Model_Tag();
        $Tag->load(11);
        echo $Tag->getAsXml();
        die();
         * 
         */
/*
        $Sesion = new Application_Model_Sesion();
        $Sesion->load(1);
        echo $Sesion->getAsXml();
        die();
 * /
 */

 
       /* 
        $Pack = new Application_Model_Pack();
        $Pack->load(4);
        echo $Pack->getAsXml();
        die();
        * 
        */
        /*
        $Restauracion = new Application_Model_Restauracion(3);
        $Restauracion->load(2);//hacemos que sera restauracion helado
        echo $Restauracion->getAsXml();
        die();
         * 
         */
       
        

        /*$Sala = new Application_Model_Sala();
        $Sala->load(1);
      
 */
        /*
        $Session = new Application_Model_Sesion();
        $Session->load(1);
        echo $Session->getAsXml();
        die();
        $Zona = new Application_Model_Zona();
        $Zona->load(2);
        $this->view->zona=$Zona->getZonaAsSvgGraphics3D($Session);
  
 */
        //$this->view->zona=$Zona->getZonaAsTableHtml();

//testear carrito y lineaCarrito
/*
        $id = NULL;
        $id_producto=1;
        $id_parent=0;
        $tipo_linea_producto ="LineaCarritoCompraSesion";
        $cantidad = 1;
        $pvp = 18;
        $id_usuario = 1;
        $nombre_sala = "prueba";
        $nombre_zona  = "prueba";
        $num_butaca  = 1;
        $flag_numerada = 0;
        $fila_butaca  = 1;
        $nombre_sesion  = "prueba";
        $date_time_sesion = "prueba";
        $LineaCarritoCompraSesion = new Application_Model_LineaCarritoCompraSesion($id, $id_producto, $id_parent, $tipo_linea_producto, $cantidad,$pvp,$id_usuario,$nombre_sala, $nombre_zona, $num_butaca,$flag_numerada,$fila_butaca, $nombre_sesion, $date_time_sesion);
        $LineaCarritoCompraSesion->add();
        $LineaCarritoCompraSesion->setPvp(20);
        $LineaCarritoCompraSesion->write();


        $CarritoCompra = new Application_Model_CarritoCompra();
        
        $CarritoCompra->load("69283000-1372241444-1",1);
        
        $LineaCarritoCompra1 = $CarritoCompra->getLineaCarritoCompraArrByIdLineaCarritoCompra(1);
        $LineaCarritoCompra2 = $CarritoCompra->getLineaCarritoCompraArrByIdLineaCarritoCompra(2);
        
        echo "<br>recuperado desde id_session: ".$LineaCarritoCompra1->getAsXml();
        echo "<br>recuperado desde id_usuario: ".$LineaCarritoCompra2->getAsXml();

        die();

   



        /*
        $MarketingInfo = new Application_Model_MarketingRefererInfo();
        $MarketingInfo->conectarGoogleAnalytics();
         * 
         */
        
        /*
        $Sesion = new Application_Model_Sesion();
        $Sesion->load(1);
        
        
        print_r($Sesion->getDescuentoArr(0));
        */
        
 

    }

}

