<?php

class Bootstrap extends Zend_Application_Bootstrap_Bootstrap
{   
    
    protected  function _initFront(){
        $this->bootstrap('FrontController');
    }
    
    protected  function _initPlugins()  
    {  
        $front = $this->getResource('FrontController');
        $front->registerPlugin(new Eticketsecure_plugin_ACL());
        $front->registerPlugin(new Eticketsecure_plugin_Idioma());
    }
    
    protected function _initRoutes()
    {
       $routeRecortadorLocalizador = new Zend_Controller_Router_Route(
            'ticketex/:id_recorte',
            array(
                'module'        => 'default',
                'controller'    => 'ticketing-utils',
                'action'        => 'recortador-url',
                'redirigidor'   => 'ticketex',
                'id_recorte'    => ''
            )
        );
       
       $routeRecortadorNormal = new Zend_Controller_Router_Route(
            're/:id_recorte',
            array(
                'module'        => 'default',
                'controller'    => 'ticketing-utils',
                'action'        => 'recortador-url',
                'redirigidor'   => 're',
                'id_recorte' => ''
            )
        );

        $routeIdioma = new Zend_Controller_Router_Route(
            ':idioma',
            array(
                'idioma' => 'ca',
                'module' => 'default',
                'controller' => 'index',
                'action' => 'index'
            ),
            array('idioma' => '[a-z]{2}')
        );
        
        $front  = $this->getResource('frontcontroller');
        $router = $front->getRouter();
        
        $routeDefault = new Zend_Controller_Router_Route_Module(
            array(),
            $front->getDispatcher(),
            $front->getRequest()
        );
        
        $routeIdiomaDefault = $routeIdioma->chain(($routeDefault));
        
        $router->addRoute('default', $routeIdiomaDefault);
        $router->addRoute('idioma', $routeIdioma);
        $router->addRoute('recortador-localizador', $routeRecortadorLocalizador);
        $router->addRoute('recortador-normal', $routeRecortadorNormal);
    }
    
    public function _initSomeservice()
    {     
        $mail = $this->getOption('mail');
        Zend_Registry::set('mail', $mail);
        
        $idioma = $this->getOption('idioma');
        Zend_Registry::set('idioma', $idioma);
        
        $webservice= $this->getOption('webservice');
        Zend_Registry::set('webservice', $webservice);
        
        $ruta= $this->getOption('ruta');
        Zend_Registry::set('ruta', $ruta);
        
        $iva= $this->getOption('iva');
        Zend_Registry::set('iva', $iva);
        
        $flag_web_service= $this->getOption('flag_web_service');
        Zend_Registry::set('flag_web_service', $flag_web_service);
        
        $flag_cache= $this->getOption('flag_cache');
        Zend_Registry::set('flag_cache', $flag_cache);
        
        $butaca_tipo = $this->getOption('butaca_tipo');
        Zend_Registry::set('butaca_tipo', $butaca_tipo);
        
        $butaca_estado = $this->getOption('butaca_estado');
        Zend_Registry::set('butaca_estado', $butaca_estado);
        
        $ruta_helpers = $this->getOption('resources');
        Zend_Registry::set('ruta_helpers', $ruta_helpers);
        
        $reserva_butacas = $this->getOption('reserva_butacas');
        Zend_Registry::set('reserva_butacas', $reserva_butacas);
        
        $sermepa = $this->getOption('sermepa');
        Zend_Registry::set('sermepa', $sermepa);
 
        $pago = $this->getOption('pago');
        Zend_Registry::set('pago', $pago);
 
        $restauracion = $this->getOption('restauracion');
        Zend_Registry::set('restauracion', $restauracion);
 
        $cron = $this->getOption('cron');
        Zend_Registry::set('cron', $cron);
 
        $semaforo = $this->getOption('semaforo');
        Zend_Registry::set('semaforo', $semaforo);
 
        $dibujado_butaca_tipo= $this->getOption('dibujado_butaca_tipo');
        Zend_Registry::set('dibujado_butaca_tipo', $dibujado_butaca_tipo);
        
        $comandos_curl= $this->getOption('comandos_curl');
        Zend_Registry::set('comandos_curl', $comandos_curl);
        
        $defender= $this->getOption('defender');
        Zend_Registry::set('defender', $defender);
        
        $bbdd_teatreneu= $this->getOption('bbdd_teatreneu');
        Zend_Registry::set('bbdd_teatreneu', $bbdd_teatreneu);
        
        $recortador= $this->getOption('recortador');
        Zend_Registry::set('recortador', $recortador);
        
        $redsys= $this->getOption('redsys');
        Zend_Registry::set('redsys', $redsys);
        
        set_time_limit(0);
        date_default_timezone_set('Europe/Madrid');
    }
    
    protected function _initEmuladorSemaforoWindows(){
        if ( !function_exists('sem_get') ) {
            function sem_get($key) { return fopen(__FILE__.'.sem.'.$key, 'w+'); }
            function sem_acquire($sem_id) { return flock($sem_id, LOCK_EX); }
            function sem_release($sem_id) { return flock($sem_id, LOCK_UN); }
        }
    }
    

}

