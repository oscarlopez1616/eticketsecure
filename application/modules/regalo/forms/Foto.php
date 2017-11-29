<?php

class Regalo_Form_Foto extends Zend_Form
{
    public function init()
    {
        $this->setAttrib('id', 'form_foto');
        $this->setAttrib('enctype', 'multipart/form-data');
        $this->setMethod('post');
        
        $ruta= Zend_Registry::get('ruta');
        $ruta_imagen = $ruta['server'].$ruta['base'].$ruta['imagenes']['galeria']['regalo_temporal'];
        
        
        $this->setDecorators(array(
            array('ViewScript', array('viewScript' => 'form/_foto.phtml')),
            'Form'
        ));
               
        //FIELDS
        
        $foto  = new Zend_Form_Element_File('foto');
        $foto   ->addValidator('Extension', false, 'jpg')
                ->addValidator('FilesSize', false, 1000000)
                ->setDestination($ruta_imagen);
        
        //SUBMIT
        $submit = new Zend_Form_Element_Submit('submit');
 
        //ADDELEMENTS
        $this->addElements(array(      
            $foto,
            $submit   
        ));
    }

}

