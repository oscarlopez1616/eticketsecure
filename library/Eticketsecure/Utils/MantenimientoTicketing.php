<?php
class Eticketsecure_Utils_MantenimientoTicketing {

    /**
     * Migra los contactos desde la fecha_alta sea hoy
     */
    public function integrationWebttneuDesdeHoy(){
        $utils = new Eticketsecure_Utils_MigrateClubNeuToTicketex();
        $utils->MigrateBbddWebTicketexDesdeHoy();
    }
    
    /**
     * Reinicia los RelacionDefenderIp que estan en lapsos de tiempo ya disponibles y con ips no baneadas
     */
    public function reiniciaDefenderLapseIntentos(){
       $InterfaceRelacionDefenderIp = new Application_Model_InterfaceRelacionDefenderIpEticketSecure();
       $relacion_defender_ip_arr = $InterfaceRelacionDefenderIp->getAll();
       $string = "";
       foreach($relacion_defender_ip_arr as $relacion_defender_ip){
           $RelacionDefenderIp = new Application_Model_RelacionDefenderIp();
           try{
                $RelacionDefenderIp->load($relacion_defender_ip["id"]);
           } catch (Exception $ex) {
               $string.= " <br>RelacionDefederIp id: ".$relacion_defender_ip["id"]." ip excedido intentos o baneada: ".$relacion_defender_ip["ip"];
           }
           $RelacionDefenderIp->desbloqueaTimeLapseIntentosRelacionDefenderIp();
       }
       return $string;
    }
    
    /**
     * haremos 2 barridos por se acaso para las 2 llamadas cada 15 minutos 
     * Siempre tiene que ser  cron.reinicia_compra_time.clean.time = 310 + 5 minutos
     * ese tiempo prudencial hara que no puedan haber errores en el proceso de compra
     * @return type
     */
    public function reiniciaCompraTime(){ 
        $string = "";
        $semaforo=Zend_Registry::get('semaforo');
        $id_semaforo = $semaforo["butacas"]["id"];
        $sem_id = sem_get($id_semaforo, 1);
        $string.=  "<br>Esperando al semaforo  ...\n";
        if (! sem_acquire($sem_id)) die ('<br>fallo al esperar al semaforo.');
        $string.= "<br>Se obtuvo el permiso para disponer de la via a las " .date ('H:i:s') . "\n";
        
        //lo que hacemos en exclusiva con el semaforo pago
        $string.=$this->cleanCarrito();
        $string.=$this->desbloquearButacas();
        //fin lo que hacemos en exclusiva con el semaforo pago
        
        if (! sem_release($sem_id)) die ('<br>fallo liberando el semaforo');
        $string.= "<br>el semaforo ha sido liberado.\n";
        return $string;
    }

    private function cleanCarrito()
    {
        $string = "";
        try{
            $CarritoDbTable = new Application_Model_DbTable_CarritoCompra();
            $translate = Zend_Registry::get('Zend_Translate');
            $cron = Zend_Registry::get('cron');
            $CarritoDbTable->cleanCarrito($cron["reinicia_compra_time"]["clean"]["time"]);
        } catch (Exception $e) {
            $string =  $e->getMessage();
        }
        return $string;
    }

    private function desbloquearButacas()
    {
        $string = "";
        $Sesion = new Application_Model_Sesion();
        $InterfaceProducto = new Application_Model_InterfaceProductoEticketSecure();
        $id_categoria = 1;//la categoria del Producto Sesion
        $id_sesion_arr = $InterfaceProducto->getIdProductoArrByIdCategoria($id_categoria);
        foreach($id_sesion_arr as $id_sesion){
            $Sesion->load($id_sesion);
            $Sesion->DesBloquearTimeButacasByDateTime();
            try{
                $Sesion->write();
            } catch (Exception $e) {
                $string.= "<br>No se ha desbloqueado ninguna Butaca en la sesion: ".$id_sesion;
            }
        }
        return $string;
  
    }

}
?>
