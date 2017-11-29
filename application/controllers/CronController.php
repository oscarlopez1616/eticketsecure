<?php

class CronController extends Zend_Controller_Action
{

    public function init()
    {//como seguridad solo puede ejecutarse desde localhost y conociendo la clave
        $this->_helper->layout()->disableLayout();
        $this->getHelper("viewRenderer")->setNoRender();
        $secure_get = ($this->_getParam('secure', -1));
        $reg_secure=Zend_Registry::get('webservice');
        $secure=$reg_secure['secure'];
        $reg_cron=Zend_Registry::get('cron');
        $ip_server=$reg_cron['ip_server'];
        $IpFunctions = new Eticketsecure_Utils_IpFunctions();
        $ip_usuario_peticion = $IpFunctions->getRealIP();
        if($secure_get!=$secure) throw new Exception('Este Cron solo Puede Ser ejecutado por el Servidor 1');
        if($ip_usuario_peticion==$ip_server || ($ip_usuario_peticion=="::1" && $ip_server="127.0.0.1 ")){  
        }else{
            throw new Exception('Este Cron solo Puede Ser ejecutado por el Servidor 2');
        }
        $this->view->MantenimientoTicketing = new Eticketsecure_Utils_MantenimientoTicketing();
    }

    public function reiniciaCompraTimeAction(){//hacemos 2 barridos por se acaso para las 2 llamadas cada 5 minutos
        echo $this->view->MantenimientoTicketing->reiniciaCompraTime();
    }
    
    public function reiniciaDefenderLapseIntentosAction(){
        echo $this->view->MantenimientoTicketing->reiniciaDefenderLapseIntentos();
    }
    
    public function integrationWebttneuDesdeHoyAction(){
        $this->view->MantenimientoTicketing->integrationWebttneuDesdeHoy();
    }

}

