<?php

class Eticketsecure_plugin_Idioma
    extends Zend_Controller_Plugin_Abstract
{
    public function routeShutdown(Zend_Controller_Request_Abstract $request)
    {
        $idioma = $request->getParam('idioma', null);
        $module = $request->getModuleName();
        $translate= new Zend_Translate('array', APPLICATION_PATH . '/configs/languages/'.$module.'/ca.php', "ca");
        $translate_comunes= new Zend_Translate('array', APPLICATION_PATH . '/configs/languages/comunes/ca.php', "ca");
        $translate->addTranslation(APPLICATION_PATH . '/configs/languages/'.$module.'/es.php', 'es');
        $translate_comunes->addTranslation(APPLICATION_PATH . '/configs/languages/comunes/es.php', 'es');
        $translate->setLocale("ca");
        
        if ($translate->isAvailable($idioma)) {
            $translate->setLocale($idioma);
        } else {//idioma por defecto
            $locale = $translate->getLocale();
            $idioma = $locale;
        }
        
        $translate->addTranslation($translate_comunes);
        Zend_Registry::set('Zend_Translate', $translate);
        Zend_Form::setDefaultTranslator($translate); 
        
        $front = Zend_Controller_Front::getInstance();
        $router = $front->getRouter();
        $router->setGlobalParam('idioma', $idioma); // Seteamos el idioma para poder cojerlo desde cualquier sitio, y lo seteamos asi para que no sea sustituido por un userParam 
    }
}