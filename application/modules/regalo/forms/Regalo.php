<?php

class Regalo_Form_Regalo extends Zend_Form
{
    public function init()
    {
        $this->setAttrib('id', 'form_regalo');
        $this->setMethod('post');        
        
        $this->setDecorators(array(
            array('ViewScript', array('viewScript' => 'form/_regalo.phtml')),
            'Form'
        ));
               
        //FIELDS
        
        $imagen              = new Zend_Form_Element_Text('imagen');
        $nombre_comprador    = new Zend_Form_Element_Text('nombre_comprador');
        $nombre_receptor     = new Zend_Form_Element_Text('nombre_receptor');
        $numero_entradas     = new Zend_Form_Element_Text('numero_entradas');
        $flag_email_receptor = new Zend_Form_Element_Text('flag_email_receptor');
        $email_receptor      = new Zend_Form_Element_Text('email_receptor');
        
        //FILTERS & VALIDATORS
        
        $imagen     ->addFilter('StringTrim');
        
        $nombre_comprador   ->setRequired(true);
        $nombre_comprador   ->addValidator(new Eticketsecure_Validate_NotEmpty());
        $nombre_comprador   ->addFilter('StringTrim');
        
        $nombre_receptor   ->setRequired(true);
        $nombre_receptor   ->addValidator(new Eticketsecure_Validate_NotEmpty());
        $nombre_receptor   ->addFilter('StringTrim');
        
        $numero_entradas   ->setRequired(true);
        $numero_entradas   ->setValue('2');
        
        $flag_email_receptor ->setRequired(true);
        $flag_email_receptor ->setValue('0');
        
        $email_receptor    ->setRequired(false);
        $email_receptor    ->addFilter('StringTrim');
        $email_receptor    ->addValidator(new Eticketsecure_Validate_NotEmpty());
        $email_receptor    ->addValidator(new Eticketsecure_Validate_EmailAddress());
        
        
        
        //SUBMIT
        $submit = new Zend_Form_Element_Submit('submit');
 
        //ADDELEMENTS
        $this->addElements(array(      
            $imagen,
            $nombre_comprador,
            $nombre_receptor,
            $numero_entradas,
            $flag_email_receptor,
            $email_receptor,
            $submit   
        ));
    }

}

