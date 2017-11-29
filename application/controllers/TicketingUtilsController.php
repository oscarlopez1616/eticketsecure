<?php

class TicketingUtilsController extends Zend_Controller_Action
{

    public function init()
    {
        $this->view->flag_json = $this->_getParam('flag_json', 0);
        $this->_helper->layout()->disableLayout();
        $this->getHelper("viewRenderer")->setNoRender();
        $this->view->idioma =  $this->_getParam('idioma', 'es');
    }

    public function postDispatch()
    {
        if ($this->view->flag_json){    
            //Con el parametro de PAGE ya cargamos el array con la lista de sesiones esperada (para hacer el test creamo vista_arr_2 en testFrontTicketing.php)
            $this->_helper->json($this->view->vista_arr); 
        }
        
    }

    public function fechaCreacionOfSessionEticketSecureAction()
    {
        $this->view->vista_arr = array();
        $SessionEticketSecure = new Application_Model_SessionEticketSecure();
        $this->view->vista_arr["fechaCreacionOfSessionEticketSecureAction"] = $SessionEticketSecure->getFechaCreacion();
    }

    public function qrAction()
    {
        $this->_helper->layout()->disableLayout();
        $text =             urldecode($this->_getParam('ct', 'empty'));
        $background_color = $this->_getParam('bgc', 'ffffff');
        $code_color =       $this->_getParam('cc', '000000');
        $option_padding =   $this->_getParam('op', 0);
        $option_size =      $this->_getParam('os', 5);
        
        
        $qr_params = array(
                    'text'  => $text, 
                    'backgroundColor' => "#".strtoupper($background_color), 
                    'foreColor' => "#".strtoupper($code_color), 
                    'padding' => $option_padding,  //array(10,5,10,5),
                    'moduleSize' => $option_size);
        
        $renderer_params = array('imageType' => 'jpg');
        Zend_Matrixcode::render('qrcode', $qr_params, 'image', $renderer_params);
    }

    public function recortadorUrlAction()
    {
        $id_recorte = $this->_getParam('id_recorte',-1);
        $redirigidor = $this->_getParam('redirigidor',-1);
        if($id_recorte!=-1 && $redirigidor!=-1){
            if($redirigidor=="ticketex"){//es recortados url del tipo localizador y por lo tanto securizado con defender
                $SecurizeDefender = new Eticketsecure_Utils_SecurizeDefender();
                $flag_recortador_url_correcto=$SecurizeDefender->SecurizeRecortadorUrlByLocalizador($id_recorte, "recortador-url-localizador");
                if(!$flag_recortador_url_correcto) exit("No redirect");
            }
            $RecortadorUrl = new Application_Model_RecortadorUrl();
            $RecortadorUrl->loadByIdRecorte($id_recorte,$redirigidor);
            $url_completa = $RecortadorUrl->getUrlCompleta();
            $this->_helper->redirector->gotoUrl($url_completa);
        }else{
            exit("No redirect");
        }
    }

}





