<?php
 
class Eticketsecure_plugin_ACL extends Zend_Controller_Plugin_Abstract {
 
    
    public function dispatchLoopStartup(Zend_Controller_Request_Abstract $request)
    {
        if ('admin' == $request->getModuleName()) {
            Zend_Layout::getMvcInstance()->setLayout('admin-layout');
        }else if ('frontTicketing' == $request->getModuleName()){
            Zend_Layout::getMvcInstance()->setLayout('front-ticketing-layout');
        }else if ('frontTeatreneu' == $request->getModuleName()){
            Zend_Layout::getMvcInstance()->setLayout('front-teatreneu-home-layout');
        }else if ('adminUsuario' == $request->getModuleName()){
            Zend_Layout::getMvcInstance()->setLayout('admin-usuario');
        }else if ('regalo' == $request->getModuleName()){
            Zend_Layout::getMvcInstance()->setLayout('regalo-layout');
        }else if ('default' == $request->getModuleName()){
            Zend_Layout::getMvcInstance()->disableLayout();
        }
    }
    
    public function preDispatch(Zend_Controller_Request_Abstract $request){
        if($request->getControllerName() == 'error'){// si es module:default y controller:error no realizamos el pluging
            return ;
        }   
        //marcamos flag para los procesos que no necesitan proceso de compra
        if($request->getModuleName() == 'regalo' && $request->getModuleName() == 'default'){
            $flag_reinicia_compra_time = false;
        }else{
            $flag_reinicia_compra_time = true;
        }
        
        //Comprobación ACL
        $viewRenderer = Zend_Controller_Action_HelperBroker::getStaticHelper('viewRenderer');
        if (null == $viewRenderer->view) {
            $viewRenderer->initView();
        } 
        
        $view = $viewRenderer->view;
        
        $view->idioma = $request->getParam('idioma');
        $view->modulo = $request->getModuleName();
        $view->controlador = $request->getControllerName();
        $view->accion = $request->getActionName();
       
        
        $view->ACL = new Eticketsecure_Utils_ACL();
        $view->ACL = $view->ACL->initACL();
        
        $view->form_login = new Application_Form_Login();


        $view->cargador_arr = array ('idioma' => $view->idioma, 'module' => $view->modulo, 'controller' =>  $view->controlador, 'action' => $view->accion);
        
        // Comenzamos con la Autorizacion
        $auth = Zend_Auth::getInstance();
        if($auth->hasIdentity()){
            $user = $auth->getIdentity();
            $id_usuario = $user->id;
        }else{
            $id_usuario = null;
        }
        $SessionEticketSecure = new Application_Model_SessionEticketSecure($flag_reinicia_compra_time,$id_usuario);
        $view->SessionEticketSecure = $SessionEticketSecure;        
        $resource = $view->modulo."-".$view->controlador;
        $privellege = $view->accion;
        if(!$view->ACL->has($resource)) {
            throw new Exception('No se ha encontrado el recurso');
        }
        //si no tenemos permiso redirigimos a login
        if(!$view->ACL->isAllowed($SessionEticketSecure->getRolId(), $resource, $privellege)) {
            $view->SessionEticketSecure->setBackupRequest($request);
            $request->setModuleName('')
                    ->setControllerName('usuario')
                    ->setActionName('login')
                    ->setDispatched(true);
        }else{
            $Request_backup = $view->SessionEticketSecure->getBackupRequest();
            $url_arr = $Request_backup->getParams();
            if(!isset($url_arr["module"]) || !isset($url_arr["controller"]) || !isset($url_arr["action"])){
                $view->SessionEticketSecure->setBackupRequest($request);  
            }
        }
        
    }
    
}
