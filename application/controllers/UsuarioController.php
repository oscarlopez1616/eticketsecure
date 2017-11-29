<?php
class UsuarioController extends Zend_Controller_Action
{

    public function init()
    {
        $this->_helper->_layout->setLayout('usuario-layout');           
        $this->view->idioma =  $this->_getParam('idioma', 'ca');
    }

    public function indexAction()
    {
        // action body
        $this->_helper->layout->disableLayout();
    }

    public function logoutAction()
    {
        $auth = Zend_Auth::getInstance();
        $auth->clearIdentity();
        
        //Para que funcione el logout y poder hacer pruebas, desactivamos el layout y vista
        $this->_helper->viewRenderer->setNoRender(true);
        
    }

    public function loginAction()
    {
        $auth = Zend_Auth::getInstance();
        if ($auth->hasIdentity()) {
            $this->view->message = "bypass";
        }else{
            $this->view->message = "no_bypass";
            $Request = $this->view->SessionEticketSecure->getBackupRequest();
            $url_arr = $Request->getParams();
            $utils = new Eticketsecure_Utils_Request();
            $user_params = $utils->requestParamsToStrig($Request->getUserParams());
            $this->view->backup_request_modulo= $url_arr["module"];
            $this->view->backup_request_controlador= $url_arr["controller"];
            $this->view->backup_request_accion= $url_arr["action"];
            if($this->view->backup_request_modulo == NULL || $this->view->backup_request_controlador == NULL || $this->view->backup_request_accion == NULL){// si cualquier es -1
                    throw new Exception('loginAction Parametros Mal construidos');
            }

            $ruta = Zend_Registry::get('ruta');
            $form = new Application_Form_Login();
            $form->setAction($ruta['dominio'].$ruta['base'].'/'.$this->view->idioma.'/default/usuario/login/'.$user_params);
            if ($this->getRequest()->isPost()) {
                if (! $form->isValid($_POST)) {

                } else {
                    $data = $form->getValues();
                    $authAdapter = new Zend_Auth_Adapter_DbTable(Zend_Db_Table::getDefaultAdapter());

                    $authAdapter->setTableName('usuario')->setCredentialColumn('password')->setIdentityColumn('email')->setCredentialTreatment('MD5(?)');

                    $authAdapter->setIdentity($data['email']);
                    $authAdapter->setCredential($data['password']);
                    $auth = Zend_Auth::getInstance();
                    $result = $auth->authenticate($authAdapter);
                    if ($result->isValid()) {
                        $userInfo = $authAdapter->getResultRowObject(null, array('password'));
                        $authStorage = $auth->getStorage();
                        $authStorage->write($userInfo);
                        $Usuario = new Application_Model_Usuario();
                        $Usuario->loadByEmail($data['email']);
                        $Usuario->setFechaUltimoLogin(date("Y-m-d H:i:s"));
                        $Usuario->write();
                        if($this->view->backup_request_modulo != -1  && $this->view->backup_request_controlador != -1 && $this->view->backup_request_accion != -1){
                            $this->_helper->redirector->gotoUrl("/".$this->view->idioma.'/'.$this->view->backup_request_modulo.'/'.$this->view->backup_request_controlador.'/'.$this->view->backup_request_accion.'/'.$user_params);
                        }else{
                            $this->_helper->redirector->gotoUrl("/".$this->view->idioma.'/'.$this->view->modulo.'/'.$this->view->controlador."/".$this->view->accion.'/'.$user_params);
                        }
                    } else {
                        $form->getElement('email')->addError('Credenciales no vàlidas');
                    }
                }
                $form->populate($form->getValues());
            }
            $form_registro = new Application_Form_Registro();
            $form_registro ->setAction($ruta['dominio'].$ruta['base'].'/'.$this->view->idioma.'/default/usuario/register/'.$user_params);
            $form_recuperar = new Application_Form_Recuperar();
            $form_recuperar ->setAction($ruta['dominio'].$ruta['base'].'/'.$this->view->idioma.'/default/usuario/recuperar/'.$user_params);
            $this->view->form_recuperar = $form_recuperar;
            $this->view->form_registro = $form_registro;
            $this->view->form_login = $form; 
        }
    }

    public function registerAction()
    {
        $Request = $this->view->SessionEticketSecure->getBackupRequest();
        $url_arr = $Request->getParams();
        $utils = new Eticketsecure_Utils_Request();
        $user_params = $utils->requestParamsToStrig($Request->getUserParams());
        $this->view->backup_request_modulo= $url_arr["module"];
        $this->view->backup_request_controlador= $url_arr["controller"];
        $this->view->backup_request_accion= $url_arr["action"];
        if($this->view->backup_request_modulo == NULL || $this->view->backup_request_controlador == NULL || $this->view->backup_request_accion == NULL){// si cualquier es -1
                throw new Exception('RegisterAction Parametros Mal construidos');
        }
        $ruta = Zend_Registry::get('ruta');
        $form = new Application_Form_Registro();
        $form->setAction($ruta['dominio'].$ruta['base'].'/'.$this->view->idioma.'/default/usuario/register/'.$user_params);
        if ($this->getRequest()->isPost()) {
            if (! $form->isValid($_POST)) {
                $form->populate($form->getValues());
            } else {                
                $id = NULL; 
                $id_role = 2;//rol del club_GrupTeatreNeu
                $email = $form->getElement('email')->getValue();
                $password = $form->getElement('password')->getValue();
                $password_md5 = md5($form->getElement('password')->getValue());
                $nombre = $form->getElement('nombre')->getValue();
                $apellidos = $form->getElement('apellidos')->getValue();
                $telefono_movil= $form->getElement('telefono_movil')->getValue();
                $codigo_postal = $form->getElement('codigo_postal')->getValue();
                $UsuarioDireccion_envio_predefinida = new Application_Model_UsuarioDireccion(); 
                $telefono = "";//no lo pedimos en este proceso
                $fecha_alta=date("Y-m-d H:i:s");
                $fecha_ultimo_login="0-0-0 0:0:0"; 
                $idioma_predefinido=$this->view->idioma; 
                $flag_opt_in=1; 
                $activo=0;
                $fecha_nacimiento= "";
                $Usuario = new Application_Model_Usuario($id, $id_role, $email, $password_md5, $nombre, $apellidos, $fecha_nacimiento, $telefono, $telefono_movil, $codigo_postal, $UsuarioDireccion_envio_predefinida, $fecha_alta, $fecha_ultimo_login, $idioma_predefinido, $flag_opt_in, $activo);
                $id_usuario = $Usuario->add();
                $Mail = new Application_Model_Mail($email, $nombre);
                $Mail->enviaMailRegistro();
                $this->_request->setPost(array('email' => $email, 'password' => $password));
                $this->_forward("login", "usuario", "default",$Request->getUserParams());
            }
        }
        
        $form_login = new Application_Form_Login();
        $form_login->setAction($ruta['dominio'].$ruta['base'].'/'.$this->view->idioma.'/default/usuario/login/'.$user_params);
        $form_recuperar = new Application_Form_Recuperar();
        $form_recuperar->setAction($ruta['dominio'].$ruta['base'].'/'.$this->view->idioma.'/default/usuario/recuperar/'.$user_params);
        $this->view->form_recuperar = $form_recuperar;
        $this->view->form_registro = $form;
        $this->view->form_login = $form_login;
        $this->renderScript('usuario/login.phtml');
    }

    public function recuperarAction()
    {
        $Request = $this->view->SessionEticketSecure->getBackupRequest();
        $url_arr = $Request->getParams();
        $utils = new Eticketsecure_Utils_Request();
        $user_params = $utils->requestParamsToStrig($Request->getUserParams());
        $this->view->backup_request_modulo= $url_arr["module"];
        $this->view->backup_request_controlador= $url_arr["controller"];
        $this->view->backup_request_accion= $url_arr["action"];
        if($this->view->backup_request_modulo == NULL || $this->view->backup_request_controlador == NULL || $this->view->backup_request_accion == NULL){// si cualquier es -1
                throw new Exception('RecuperarAction Parametros Mal construidos');
        }   
        $ruta = Zend_Registry::get('ruta');
        $form = new Application_Form_Recuperar();
        $form->setAction($ruta['dominio'].$ruta['base'].'/'.$this->view->idioma.'/default/usuario/recuperar/'.$user_params);
        if ($this->getRequest()->isPost()) {
            if ($form->isValid($_POST)) { 
                $email = $form->getElement('email')->getValue();
                 $Usuario = new Application_Model_Usuario();
                try{// si no salta la excepcion del interface_usuario es que existe el user si no no existe. 
                    $Usuario->loadByEmail($email);
                    $flag_existe_usuario = true;
                } catch (Exception $ex) {
                    $flag_existe_usuario = false;
                }
                $translate = Zend_Registry::get('Zend_Translate');
                if($flag_existe_usuario){
                    $ruta = Zend_Registry::get('ruta'); 
                    $id_usuario = $Usuario->getId();
                    $link_recuperar = $ruta['dominio'].$ruta['base'].'/'.$this->view->idioma.'/usuario/restablecer/p1/'.  urlencode(md5($id_usuario)).'/p2/'.  urlencode(base64_encode($email));
                    $nombre = $Usuario->getNombre();
                    $Mail = new Application_Model_Mail($email, $nombre);
                    $Mail->enviaMailRecuperar($link_recuperar);
                    $this->view->success_message = $translate->_('usuario.login.form.recuperar.success');   
                }else{
                    $this->view->error_message = $translate->_('usuario.login.form.recuperar.error');   
                    $this->view->set_error_message = 1;
                }
            }else {
                $this->view->set_error_message = 1;
            }
            $form->populate($form->getValues());
        }
        $form_login = new Application_Form_Login();
        $form_login->setAction($ruta['dominio'].$ruta['base'].'/'.$this->view->idioma.'/default/usuario/login/'.$user_params);
        $form_registro = new Application_Form_Registro();
        $form_registro ->setAction($ruta['dominio'].$ruta['base'].'/'.$this->view->idioma.'/default/usuario/register/'.$user_params);
        
        $this->view->form_recuperar = $form;
        $this->view->form_registro = $form_registro;
        $this->view->form_login = $form_login;
        $this->renderScript('usuario/login.phtml');
    }

    public function restablecerAction()
    {       
        $id_usuario_md5 = urldecode($this->_getParam('p1', -1));
        $email_base_64 = urldecode($this->_getParam('p2', -1));
        $SecurizeDefender = new Eticketsecure_Utils_SecurizeDefender();
        $flag_usuario_correcto = $SecurizeDefender->SecurizeProcessByIdUsuarioMd5AndEmailBase64($id_usuario_md5, $email_base_64, "recuperar-password");
        if($flag_usuario_correcto){
            $Request = $this->view->SessionEticketSecure->getBackupRequest();
            $url_arr = $Request->getParams();
            $utils = new Eticketsecure_Utils_Request();
            $user_params = $utils->requestParamsToStrig($Request->getUserParams());
            $this->view->backup_request_modulo= $url_arr["module"];
            $this->view->backup_request_controlador= $url_arr["controller"];
            $this->view->backup_request_accion= $url_arr["action"];
            if($this->view->accion == NULL || $this->view->controlador == NULL || $this->view->modulo == NULL){// si cualquier es -1
                    throw new Exception('RecuperarAction Parametros Mal construidos');
            }   
             $form = new Application_Form_Restablecer();
             $this->view->form_restablecer = $form;

             if ($this->getRequest()->isPost()) {
                if ($form->isValid($_POST)) {
                    // DADO POR EL CONTROL DE ACCESO //
                    $password = $form->getElement('password')->getValue();
                    $password_md5 = md5($form->getElement('password')->getValue());
                    $Usuario = new Application_Model_Usuario();
                    $Usuario->loadByEmail(base64_decode($email_base_64));
                    $Usuario->setPassword($password_md5);
                    try{// por si pone las mismas contraseñas no vea un error
                        $Usuario->write();     
                    } catch (Exception $ex) {}
                    $this->_request->setPost(array('email' => base64_decode($email_base_64), 'password' => $password));
                    $this->_forward("login", "usuario", "default",$Request->getUserParams());
                }
                $form->populate($form->getValues());
            }
        }    
    }

}





