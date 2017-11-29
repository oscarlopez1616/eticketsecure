<?php

class Application_Form_Recuperar extends Zend_Form
{

    public function init()
    {
        $this->setAttrib('id', 'form_recuperar');
        $this->setMethod('post');
        
        $this->setDecorators(array(
            array('ViewScript', array('viewScript' => 'form/_recuperar.phtml')),
            'Form'
        ));
            
        //FIELDS        
        $email = new Zend_Form_Element_Text('email');

        
        //FILTERS VALIDATORS & OTHERS
        $email      ->setRequired(true);
        $email      ->addValidator(new Eticketsecure_Validate_EmailAddress());
        
        //SUBMIT
        $submit = new Zend_Form_Element_Submit('submit');
 
        //ADDELEMENTS
        $this->addElements(array(      
            $email,
            $submit   
        ));
        
    }


}

