<?php

class Application_Form_Restablecer extends Zend_Form
{

    public function init()
    {
        $this->setAttrib('id', 'form_restablecer');
        $this->setMethod('post');
        
        $this->setDecorators(array(
            array('ViewScript', array('viewScript' => 'form/_restablecer.phtml')),
            'Form'
        ));
            
        //FIELDS        
        $password = new Zend_Form_Element_Password('password');
        $confirm_password = new Zend_Form_Element_Password('confirm_password');
        
        //FILTERS VALIDATORS & OTHERS
        $password       ->setRequired(true);   
        $password       ->addValidator(new Eticketsecure_Validate_NotEmpty());
        $confirm_password ->setRequired(true);   
        $confirm_password ->addValidator(new Eticketsecure_Validate_NotEmpty());
        $confirm_password ->addFilter('StringTrim');
        $confirm_password ->addValidator(new Eticketsecure_Validate_Identical('password'));
        
        //SUBMIT
        $submit = new Zend_Form_Element_Submit('submit');
 
        //ADDELEMENTS
        $this->addElements(array(      
            $password,
            $confirm_password,
            $submit   
        ));
        
    }


}

