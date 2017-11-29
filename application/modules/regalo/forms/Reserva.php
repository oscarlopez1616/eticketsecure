<?php

class Regalo_Form_Reserva extends Zend_Form
{

    public function init()
    {

        $this->setAttrib('id', 'form_reserva');
        $this->setMethod('post');        
        
        $this->setDecorators(array(
            array('ViewScript', array('viewScript' => 'form/_reserva.phtml')),
            'Form'
        ));
               
        //FIELDS
        
        $email               = new Zend_Form_Element_Text('email');
        $id_compra           = new Zend_Form_Element_Text('id_compra');
        $id_producto         = new Zend_Form_Element_Text('id_producto');
        $id_obra             = new Zend_Form_Element_Text('id_obra');
        $id_sesion           = new Zend_Form_Element_Text('id_sesion');
        
        //FILTERS & VALIDATORS

        $email      ->setRequired(true);
        $email      ->addFilter('StringTrim');
        $email      ->addValidator(new Eticketsecure_Validate_EmailAddress());
        
        $id_compra      ->setRequired(true);        
        
        $id_producto    ->setRequired(true);        
        
        $id_obra        ->setRequired(true);        
        
        $id_sesion      ->setRequired(true);
        
        //SUBMIT
        $submit = new Zend_Form_Element_Submit('submit');
 
        //ADDELEMENTS
        $this->addElements(array(      
            $email,
            $id_compra,
            $id_producto,
            $id_obra,
            $id_sesion,
            $submit   
        ));
    }

}

