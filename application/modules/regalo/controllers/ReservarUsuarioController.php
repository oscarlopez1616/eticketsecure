<?php

class Regalo_ReservarUsuarioController extends Zend_Controller_Action
{

    public function init()
    {
        $this->view->vista_arr = array(); 
        $this->view->flag_json = $this->_getParam('flag_json', 0);
        $this->view->idioma =  $this->_getParam('idioma', 'es');
        $this->view->show_header = 0;
    }

    public function indexAction()
    {
        //PARAMETROS
        
        $id_compra_base_64  = urldecode($this->_getParam('p1', -1));
        $email_md5         = urldecode($this->_getParam('p2', -1));

        $SecurizeDefender = new Eticketsecure_Utils_SecurizeDefender();
        $flag_compra_correcta=$SecurizeDefender->SecurizeCompraPagadaByIdCompraBase64AndEmailMd5($id_compra_base_64, $email_md5,'escojer-reserva');
        
        try{
            $ModuleRegaloReservasDb = new Regalo_Model_DbTable_ModuleRegaloReservas();
            $reserva_arr = $ModuleRegaloReservasDb->getByIdCompra(base64_decode($id_compra_base_64));
            $id_sesion = $reserva_arr["id_sesion_webttneu08"];
            $flag_reserva_efectuada=true;
        } catch (Exception $ex) {
            $flag_reserva_efectuada=false;
        }

        if($flag_reserva_efectuada){
            $url_tramitar_reserva = "/".$this->view->idioma."/regalo/reservar-usuario/tramitar-reserva/p1/".urlencode($id_compra_base_64)."/p2/".urlencode($email_md5)."/p3/".$id_sesion."/";     
            $this->_helper->redirector->gotoUrl($url_tramitar_reserva);
        }else{
            if ($flag_compra_correcta==true){
                $this->view->vista_arr = array();
                $this->view->vista_arr['id_compra_base_64'] = urlencode($id_compra_base_64)  ;
                $this->view->vista_arr['email_md5'] = urlencode($email_md5)  ;
                $SessionsTeatreNeu = new Regalo_Model_DbTable_TtnSessions();
                $EspectaclesTeatreNeu = new Regalo_Model_DbTable_TtnEspectacles();
                
                $id_compra = base64_decode($id_compra_base_64);
                $Compra = new Application_Model_Compra();
                $Compra->load($id_compra);
                $CarritoCompra = $Compra->getCarrito();
                $LineaCarritoCompraRegalo = $CarritoCompra->getLineaCarritoCompraArrType("LineaCarritoCompraRegalo");
                $LineaCarritoCompraRegalo = current(current($LineaCarritoCompraRegalo));//entramos en la linea saltamos id_agrupador y id_linea_carrito_compra con los dos currents ya que sabemos que siempre habra solo una linea para este proceso, es un proces de compra unica
                $id_producto = $LineaCarritoCompraRegalo->getIdProducto();
                if($id_producto==12){//teatre familiar
                    try{
                        $espectacles_arr = $EspectaclesTeatreNeu->getInfantilByDataMasGrandeQueHoy();
                    } catch (Exception $ex) {
                        $espectacles_arr = array();
                    }

                }else{
                    $espectacles_arr = $EspectaclesTeatreNeu->getAllByDataMasGrandeQueHoy();
                }
                
                $this->view->vista_arr["espectales"] = array();
                $this->view->vista_arr["sessions"] = array();
                $MigraClubNeuToTicketex = new Eticketsecure_Utils_MigrateClubNeuToTicketex();
                $i=0;
                foreach($espectacles_arr as $espectacles){
                    $this->view->vista_arr["espectales"][$i]["id_espectacle"] =  $espectacles["id_espectacle"];
                    $this->view->vista_arr["espectales"][$i]["espai"] =  $espectacles["espai"];
                    $this->view->vista_arr["espectales"][$i]["titol"] =  $espectacles["titol"];
                    $this->view->vista_arr["espectales"][$i]["descripcio_curt"] =  $espectacles["descripcio_curt_".$this->view->idioma];
                    $this->view->vista_arr["espectales"][$i]["foto_gran"] =  "http://www.teatreneu.com/archivos/espectacles_foto_gran/".$espectacles["foto_gran"];
                    $this->view->vista_arr["espectales"][$i]["youtube"] =  $espectacles["youtube"];
                    $this->view->vista_arr["espectales"][$i]["link"] =  "http://www.teatreneu.com/e-".$espectacles["id_espectacle"]."/".$MigraClubNeuToTicketex->format_url_rewrite($espectacles["titol"]); 
                    try{
                        $sessions_arr = $SessionsTeatreNeu->getByIdEspectacleAndDataAndHoraMasGrandeQueNowOrderByData($espectacles["id_espectacle"]); 
                    } catch (Exception $ex) {
                        $sessions_arr = array();
                    }
                    
                    $j=0;
                    $Utils = new Eticketsecure_Utils_DateTimeUtils();
                    foreach($sessions_arr as $sessions){
                        $fecha = $Utils->dateTimetoString($this->view->idioma, $sessions["data"].' '.$sessions["hora"]);
                        $this->view->vista_arr["sessions"][$espectacles["id_espectacle"]][$j]["fecha_sesion"] = $fecha;
                        $this->view->vista_arr["sessions"][$espectacles["id_espectacle"]][$j]["sala"] =  $sessions["sala"];
                        $this->view->vista_arr["sessions"][$espectacles["id_espectacle"]][$j]["entradas_disponibles"] =  $sessions["entrades"];
                        $this->view->vista_arr["sessions"][$espectacles["id_espectacle"]][$j]["id_session"] =  $sessions["id"];
                        $j++;
                    }
                    $i++;
                }
                
                $this->view->vista_arr['nombre_receptor'] = $LineaCarritoCompraRegalo->getNombreReceptorRegalo();
                $this->view->vista_arr['numero_entradas'] = $LineaCarritoCompraRegalo->getCantidad();
                $id_producto = $LineaCarritoCompraRegalo->getIdProducto();
                if($id_producto==15){
                    $this->view->vista_arr['producto']['imagen'] = 'img-producto-p1-clean-'.$this->view->idioma.'.png';
                    $this->view->vista_arr['producto']['nombre'] = 'Cualquier Obra';
                }
                if($id_producto==16){
                    $this->view->vista_arr['producto']['imagen'] = 'img-producto-p2-clean-'.$this->view->idioma.'.png';
                    $this->view->vista_arr['producto']['nombre'] = 'Cualquier Obra Familiar';
                }
                if($id_producto==17){
                    $this->view->vista_arr['producto']['imagen'] = 'img-producto-p3-clean-'.$this->view->idioma.'.png';
                    $this->view->vista_arr['producto']['nombre'] = 'Cualquier Obra + Complemento';
                    $this->view->vista_arr["complemento"]['nombre_complemento'] = "HELADERIA DE GRACIA";
                    $this->view->vista_arr["complemento"]['tipo_complemento'] = "Helado";
                    $this->view->vista_arr["complemento"]['telf'] = "932 377 719";
                    $this->view->vista_arr["complemento"]['link'] = "http://www.crepsbarcelona.com/";
                    $this->view->vista_arr["complemento"]['imagen'] = "sample-gelats.png";
                }
                if($id_producto==18){
                    $this->view->vista_arr['producto']['imagen'] = 'img-producto-p3-clean-'.$this->view->idioma.'.png';
                    $this->view->vista_arr['producto']['nombre'] = 'Cualquier Obra + Complemento';
                    $this->view->vista_arr["complemento"]['nombre_complemento'] = "CREPS BARCELONA";
                    $this->view->vista_arr["complemento"]['tipo_complemento'] = "Deliciosa Crepe";
                    $this->view->vista_arr["complemento"]['telf'] = "932 377 719";
                    $this->view->vista_arr["complemento"]['link'] = "http://www.crepsbarcelona.com/";
                    $this->view->vista_arr["complemento"]['imagen'] = "sample-creps-bcn.png";
                }
                if($id_producto==19){
                    $this->view->vista_arr['producto']['imagen'] = 'img-producto-p4-clean-'.$this->view->idioma.'.png';
                    $this->view->vista_arr['producto']['nombre'] = 'Tributo Pepe Rubianes';
                }
                if($id_producto==19){
                    $this->view->vista_arr['producto']['imagen'] = 'img-producto-p5-clean-'.$this->view->idioma.'.png';
                    $this->view->vista_arr['producto']['nombre'] = 'Orgasmos La Comedia';
                }

            }else{
                throw new Exception('Ticket Regalo Incorrecto');
            }     
        }

    }

    public function tramitarReservaAction()
    {
        //PARAMETROS
        $id_compra_base_64  = urldecode($this->_getParam('p1', -1));
        $email_md5          = urldecode($this->_getParam('p2', -1));
        $id_sesion          = urldecode($this->_getParam('p3', -1));
        if($id_sesion==-1) throw new Exception('Ticketex tramite de sesion mal realizado');

        $SecurizeDefender = new Eticketsecure_Utils_SecurizeDefender();
        $flag_compra_correcta=$SecurizeDefender->SecurizeCompraPagadaByIdCompraBase64AndEmailMd5($id_compra_base_64, $email_md5,'tramitar-reserva');
        
        $this->view->vista_arr = array();
        
        if ($flag_compra_correcta==true){
            $id_compra = base64_decode($id_compra_base_64);
            $ModuleRegaloReservas_db = new Regalo_Model_DbTable_ModuleRegaloReservas();
            $flag_compra_ya_reservada=false;
            try{
                $ModuleRegaloReservas_db->getByIdCompra($id_compra);// si no salta la excepcion es que existe y ya ha sido reservada
                $flag_compra_ya_reservada=true;
            } catch (Exception $ex) {
                $flag_compra_ya_reservada=false;
            }

            $Compra = new Application_Model_Compra();
            $Compra->load($id_compra);
            $CarritoCompra = $Compra->getCarrito();
            $LineaCarritoCompraRegalo = $CarritoCompra->getLineaCarritoCompraArrType("LineaCarritoCompraRegalo");
            $LineaCarritoCompraRegalo = current(current($LineaCarritoCompraRegalo));//entramos en la linea saltamos id_agrupador y id_linea_carrito_compra con los dos currents ya que sabemos que siempre habra solo una linea para este proceso, es un proces de compra unica
            $entradas = $LineaCarritoCompraRegalo->getCantidad();
            $SessionsTeatreNeu = new Regalo_Model_DbTable_TtnSessions();
            $session_arr = $SessionsTeatreNeu->getById($id_sesion);
            $id_sesion_webttneu08=$id_sesion; 
            $id_compra=$id_compra; 
            $EspectaclesTeatreNeu_db = new Regalo_Model_DbTable_TtnEspectacles();
            $espectaculos_arr = $EspectaclesTeatreNeu_db->getById($session_arr["idespectacle"]);
            $titulo_espectaculo=$espectaculos_arr["titol"]; 
            $fecha_reserva=$session_arr["data"]." ".$session_arr["hora"]; 
            $nombre_teatro=$espectaculos_arr["espai"]; 
            $nombre_sala=$session_arr["sala"]; 
            $numero_entradas=$entradas; 
            $pvp_iva=0; 
            $redimido=0; 
            $fecha_realizacion_reserva=date("Y-m-d H:i:s");
            $id_producto = $LineaCarritoCompraRegalo->getIdProducto();
            if($id_producto==15){
                $this->view->vista_arr["producto"]['imagen']['es'] = 'img-producto-p1-clean-es.png';
                $this->view->vista_arr["producto"]['imagen']['ca'] = 'img-producto-p1-clean-ca.png';
            }
            if($id_producto==16){
                $this->view->vista_arr["producto"]['imagen']['es'] = 'img-producto-p2-clean-es.png';
                $this->view->vista_arr["producto"]['imagen']['ca'] = 'img-producto-p2-clean-ca.png';
            }
            if($id_producto==17){
                $this->view->vista_arr["complemento"]['nombre_complemento'] = "HELADERIA DE GRACIA";
                $this->view->vista_arr["complemento"]['tipo_complemento'] = "Helado";
                $this->view->vista_arr["complemento"]['telf'] = "933 451 782 ";
                $this->view->vista_arr["complemento"]['link'] = "www.google.es";
                $this->view->vista_arr["producto"]['imagen']['es'] = 'img-producto-p3-clean-es.png';
                $this->view->vista_arr["producto"]['imagen']['ca'] = 'img-producto-p3-clean-ca.png';
            }
            if($id_producto==18){
                $this->view->vista_arr["complemento"]['nombre_complemento'] = "CREPS BARCELONA";
                $this->view->vista_arr["complemento"]['tipo_complemento'] = "Deliciosa Crepe";
                $this->view->vista_arr["complemento"]['telf'] = "932 377 719";
                $this->view->vista_arr["complemento"]['link'] = "http://www.crepsbarcelona.com/";
                $this->view->vista_arr["producto"]['imagen']['es'] = 'img-producto-p3-clean-es.png';
                $this->view->vista_arr["producto"]['imagen']['ca'] = 'img-producto-p3-clean-ca.png';
            }
            if($id_producto==19){
                $this->view->vista_arr["producto"]['imagen']['es'] = 'img-producto-p4-clean-es.png';
                $this->view->vista_arr["producto"]['imagen']['ca'] = 'img-producto-p4-clean-ca.png';
            }
            if($id_producto==20){
                $this->view->vista_arr["producto"]['imagen']['es'] = 'img-producto-p5-clean-es.png';
                $this->view->vista_arr["producto"]['imagen']['ca'] = 'img-producto-p5-clean-ca.png';
            }
            $this->view->vista_arr["datos_obra"]['titulo_espectaculo'] = $titulo_espectaculo;
            $this->view->vista_arr["datos_obra"]['fecha_reserva'] = $fecha_reserva;
            $this->view->vista_arr["datos_obra"]['nombre_teatro'] = $espectaculos_arr["espai"];
            $this->view->vista_arr["datos_obra"]['nombre_sala'] = $session_arr["sala"];
            $this->view->vista_arr["datos_obra"]['localizador'] = $Compra->getLocalizador();
            $ruta = Zend_Registry::get('ruta');
            $this->view->vista_arr["link_entrada"] = $ruta['dominio'].$ruta['base'].'/'.$this->view->idioma.'/regalo/reservar-usuario/entrada-generate/p1/'.urlencode($id_compra_base_64).'/p2/'.urlencode($email_md5);
           
            if($flag_compra_ya_reservada){
                //lo necesario para pintar la reserva
                $this->view->vista_arr["mode"] = "si_reserva_realizada";
                $this->view->vista_arr["status"] = "success";
            }else{
                $this->view->vista_arr["message"] = "Reserva Realizada Correctamente";
                $this->view->vista_arr["mode"] = "no_reserva_realizada";
                $this->view->vista_arr["status"] = "success";
            }
           
            if($entradas<=$session_arr["entrades"]){//las entradas demandadas tienen que ser mas pequeñas o igual que las entradas libres que quedan para esa sesion
                if(!$flag_compra_ya_reservada) $SessionsTeatreNeu->setEntradas($id_sesion, $entradas);

                if(!$flag_compra_ya_reservada) $ModuleRegaloReservas_db->add($id_sesion_webttneu08, $id_compra, $titulo_espectaculo, $fecha_reserva, $numero_entradas, $pvp_iva, $redimido, $fecha_realizacion_reserva,$nombre_teatro,$nombre_sala);
            }else{
                $this->view->vista_arr["message"] = "No quedan ".$entradas." libres para esta sesión";
                $this->view->vista_arr["mode"] = "no_reserva_realizada";
                $this->view->vista_arr["status"] = "error";
            }
        }else{
            throw new Exception('Ticket Regalo Incorrecto');
        }
    }

    public function entradaGenerateAction()
    {
        $this->_helper->layout()->disableLayout();
        
        //PARAMETROS
        $id_compra_base_64 = urldecode($this->_getParam('p1', -1));
        $email_md5 = urldecode($this->_getParam('p2', -1));
        $render = $this->_getParam('p3', 0);
 
        $SecurizeDefender = new Eticketsecure_Utils_SecurizeDefender();
        $flag_compra_correcta=$SecurizeDefender->SecurizeCompraPagadaByIdCompraBase64AndEmailMd5($id_compra_base_64, $email_md5,'entrada-generate');
        
        if ($flag_compra_correcta==true){
            if ($render==0){
                $this->_helper->viewRenderer->setNoRender(true);
                $unique_id = uniqid();
                $ruta = Zend_Registry::get('ruta');
                $comandos_curl = Zend_Registry::get('comandos_curl');

                //RUTAS
                $ruta_web_to_pdf = $ruta['dominio'].$ruta['base'].'/'.$this->view->idioma.'/regalo/reservar-usuario/entrada-generate/p1/'.urlencode($id_compra_base_64).'/p2/'.urlencode($email_md5).'/p3/1';
                $ruta_save_pdf = $ruta["server"].$ruta["base"].$ruta["temporal"];
                $ruta_save_web_pdf= $ruta["dominio"].$ruta["base"].$ruta["temporal"];
                $name_save_pdf = "entrada-".  uniqid().".pdf";
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
                $CarritoCompra = $Compra->getCarrito();
                $LineaCarritoCompraRegalo = $CarritoCompra->getLineaCarritoCompraArrType("LineaCarritoCompraRegalo");
                $LineaCarritoCompraRegalo = current(current($LineaCarritoCompraRegalo));//entramos en la linea saltamos id_agrupador y id_linea_carrito_compra con los dos currents ya que sabemos que siempre habra solo una linea para este proceso, es un proces de compra unica
                $ModuleRegaloReservas_db = new Regalo_Model_DbTable_ModuleRegaloReservas();
                $reserva_arr = $ModuleRegaloReservas_db->getByIdCompra(base64_decode($id_compra_base_64));
                $this->view->vista_arr = array();
                $this->view->vista_arr['localizador']=$Compra->getLocalizador();
                $this->view->vista_arr['numero_entradas']=$LineaCarritoCompraRegalo->getCantidad();
                $this->view->vista_arr['nombre_producto']=$reserva_arr["titulo_espectaculo"];
                $this->view->vista_arr['lugar']=$reserva_arr["nombre_teatro"]." Sala: ".$reserva_arr["nombre_sala"];
                $this->view->vista_arr['nombre_receptor']=$LineaCarritoCompraRegalo->getNombreReceptorRegalo();
                $Utils = new Eticketsecure_Utils_DateTimeUtils();
                $this->view->vista_arr['sesion'] = $Utils->dateTimetoString($this->view->idioma, $reserva_arr["fecha_reserva"]);
                $id_producto = $LineaCarritoCompraRegalo->getIdProducto();
                if($id_producto==15){
                    $this->view->vista_arr['imagen_producto']= 'img-producto-p1-clean-'.$this->view->idioma.'.png';
                }
                if($id_producto==16){
                    $this->view->vista_arr['imagen_producto']= 'img-producto-p2-clean-'.$this->view->idioma.'.png';
                }
                if($id_producto==17){
                    $this->view->vista_arr['imagen_producto']= 'img-producto-p3-clean-'.$this->view->idioma.'.png';
                }
                if($id_producto==18){
                    $this->view->vista_arr['imagen_producto']= 'img-producto-p3-clean-'.$this->view->idioma.'.png';
                }
                if($id_producto==19){
                    $this->view->vista_arr['imagen_producto']= 'img-producto-p4-clean-'.$this->view->idioma.'.png';
                }
                if($id_producto==20){
                    $this->view->vista_arr['imagen_producto']= 'img-producto-p5-clean-'.$this->view->idioma.'.png';
                }
            }
        }else{
            throw new Exception('Entrada Incorrecta');
        }
    }


}
