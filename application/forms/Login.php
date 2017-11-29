<?php

class Application_Form_Login extends Zend_Form
{

    public function init()
    {
        $this->setAttrib('id', 'form_login');
        $this->setMethod('post');
        
        $this->setDecorators(array(
            array('ViewScript', array('viewScript' => 'form/_login.phtml')),
            'Form'
        ));
            
        //FIELDS        
        $email = new Zend_Form_Element_Text('email');
        $password = new Zend_Form_Element_Password('password');

        
        //FILTERS VALIDATORS & OTHERS
        $email      ->setRequired(true);
        $email      ->addValidator(new Eticketsecure_Validate_NotEmpty());
        $email      ->addValidator(new Eticketsecure_Validate_EmailAddress());
        $password   ->setRequired(true);   
        $password   ->addValidator(new Eticketsecure_Validate_NotEmpty());
        
        //SUBMIT
        $submit = new Zend_Form_Element_Submit('submit');
 
        //ADDELEMENTS
        $this->addElements(array(      
            $email,
            $password,
            $submit   
        ));
        
    }


}

