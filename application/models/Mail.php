<?php

class Application_Model_Mail
{
    private $_mail;
    private $_email_envio;
    private $_config;
    private $_transport;
    private $_nombre;
    
    
    public function __construct($email, $nombre){
        $conf_mail = Zend_Registry::get('mail');
        $config = array(
            'auth' => 'login',
            'username' => $conf_mail['cuenta'],
            'password' => $conf_mail['password'],
            'port' => $conf_mail['port']);
        $this->_transport = new Zend_Mail_Transport_Smtp($conf_mail['host'],$config);
        $this->_mail = new Zend_Mail('UTF-8');
        $this->_mail->setFrom($conf_mail['cuenta'], 'Club Teatreneu');
        $this->_mail->addTo($email, $nombre);
        $this->_translate = Zend_Registry::get('Zend_Translate');
        $this->_nombre = $nombre;
    }
    
    public function enviaMailCompra(){        
        
        $user_data['nombre']=$this->_nombre;
        
        $html_mail= new Zend_View();
        $html_mail->assign('user_data',$user_data);
        $html_mail->setScriptPath(APPLICATION_PATH . '/views/emails/');
        $body_mail=$html_mail->render('mail_compra.phtml');
        $this->_mail->setSubject('La teva compra d\'entrades del ClubNeu');        
        $this->_mail->setBodyHtml($body_mail);
        //echo $body_mail;die(); // MAIL POR PANTALLA
        $this->_mail->send($this->_transport);     
    }
    
    public function enviaMailEntrada($nombre_amigo){        
        
        $user_data['nombre_amigo']=$nombre_amigo;
        $user_data['nombre']=$this->_nombre;
        
        $html_mail= new Zend_View();
        $html_mail->assign('user_data',$user_data);
        $html_mail->setScriptPath(APPLICATION_PATH . '/views/emails/');
        $body_mail=$html_mail->render('mail_entrada.phtml');
        $this->_mail->setSubject($nombre_amigo.' t\'ha enviat una entrada per anar al teatre');        
        $this->_mail->setBodyHtml($body_mail);
        //echo $body_mail;die(); // MAIL POR PANTALLA
        $this->_mail->send($this->_transport);     
    }
    
    public function enviaMailRegistro(){        
        
        $user_data['nombre']=$this->_nombre;
        $html_mail= new Zend_View();
        $html_mail->assign('user_data',$user_data);
        $html_mail->setScriptPath(APPLICATION_PATH . '/views/emails/');
        $body_mail=$html_mail->render('mail_registro.phtml');
        $this->_mail->setSubject($this->_nombre.', Bienvenido al ClubNeu');        
        $this->_mail->setBodyHtml($body_mail);
        //echo $body_mail;die(); // MAIL POR PANTALLA
        $this->_mail->send($this->_transport);     
    }
    
    public function enviaMailRecuperar($link_recuperar){        
        
        $user_data['nombre']=$this->_nombre;
        $user_data['link_recuperar']=$link_recuperar;
        $html_mail= new Zend_View();
        $html_mail->assign('user_data',$user_data);
        $html_mail->setScriptPath(APPLICATION_PATH . '/views/emails/');
        $body_mail=$html_mail->render('mail_recuperar.phtml');
        $this->_mail->setSubject('Reestablecer contraseña ClubNeu');        
        $this->_mail->setBodyHtml($body_mail);
        //echo $body_mail;die(); // MAIL POR PANTALLA
        $this->_mail->send($this->_transport);     
    }
    
    public function enviaMailRegaloComprador($user_data_arr){        
        $html_mail= new Zend_View();
        $html_mail->assign('user_data_arr',$user_data_arr);
        $html_mail->assign('translate',$this->_translate);
        $html_mail->setScriptPath(APPLICATION_PATH . '/views/emails/');
        $body_mail=$html_mail->render('mail_regalo_comprador.phtml');
        $this->_mail->setSubject($this->_translate->_('mail.comprador.subject').' '.$user_data_arr['nombre_receptor']);        
        $this->_mail->setBodyHtml($body_mail);
        //echo $body_mail;die(); // MAIL POR PANTALLA
        $this->_mail->send($this->_transport);     
    }
    
    public function enviaMailRegaloReceptor($user_data_arr){                
        $html_mail= new Zend_View();
        $html_mail->assign('user_data_arr',$user_data_arr);
        $html_mail->assign('translate',$this->_translate);
        $html_mail->setScriptPath(APPLICATION_PATH . '/views/emails/');
        $body_mail=$html_mail->render('mail_regalo_receptor.phtml');
        $this->_mail->setSubject($user_data_arr["nombre_receptor"].' '.$this->_translate->_('mail.receptor.subject'));
        $this->_mail->setBodyHtml($body_mail);
        //echo $body_mail;die(); // MAIL POR PANTALLA
        $this->_mail->send($this->_transport);     
    }
}

