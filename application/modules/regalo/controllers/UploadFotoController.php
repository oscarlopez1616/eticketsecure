<?php

class Regalo_UploadFotoController extends Zend_Controller_Action
{

    public function init()
    {
        $this->_helper->layout()->disableLayout(); 
        $this->view->vista_arr = array(); 
        $this->view->flag_json = $this->_getParam('flag_json', 0);
        if ($this->view->flag_json){
            $this->_helper->layout()->disableLayout();
        }
        $this->view->idioma =  $this->_getParam('idioma', 'es');
    }

    public function postDispatch()
    {
        if ($this->view->flag_json){
         $this->_helper->json($this->view->vista_arr); 
        }
    }
    
    public function indexAction()
    {
        $form = new Regalo_Form_Foto();
        $form ->setAction($this->_helper->url->url(array('idioma' => $this->view->idioma, 'module' => 'regalo', 'controller' => 'upload-foto', 'action' => 'index', 'flag_json' => 1),null, true));
        $this->view->form= $form;
        
        if ($this->getRequest()->isPost()){
            $vista_arr = array();
            $vista_arr['status'] = 'empty';
            $formData = $this->getRequest()->getPost();
            if ($form->isValid($formData)){
                //UPLOAD FILES
                if (!is_array($form->getElement('foto')->getFileName())){
                    $originalFilename = pathinfo($form->getElement('foto')->getFileName());
                    $imagen = 'temp-'.uniqid().'.'.$originalFilename['extension'];
                    $adapter =  $form->getElement('foto')->getTransferAdapter();
                    $file_arr = $adapter->getFileInfo();        
                    $file = "foto";
                    $adapter->addFilter('Rename', $file_arr[$file]["destination"].'/'.$imagen, $file);
                    if (!$adapter->receive($file)) {
                        throw new Exception("fileUploadError"); 
                    }
                    
                    $ruta= Zend_Registry::get('ruta');
                    $ruta_imagen = $ruta['dominio'].$ruta['base'].$ruta['imagenes']['galeria']['regalo_temporal']; 
                    $vista_arr['status']='success';
                    $vista_arr['file_name']= $imagen;
                    $vista_arr['file_location']=$file_arr[$file]["destination"].'/'.$imagen;
                    $vista_arr['file_url']=$ruta_imagen.'/'.$imagen;
                }
                
           }else{
               $vista_arr['status']='error';
               $e_messages = $form->getElement('foto')->getMessages();
               if (isset ($e_messages['fileUploadErrorIniSize'])){
                   $vista_arr['error']='fileUploadErrorIniSize';
               }
               if (isset ($e_messages['fileExtensionFalse'])){
                   $vista_arr['error']='fileExtensionFalse';
               }
               
           }
           $this->view->vista_arr = $vista_arr ;
        }
        $this->view->form = $form;
    }


}

