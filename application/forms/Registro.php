<?php

class Application_Form_Registro extends Zend_Form
{

    public function init()
    {
        $this->setAttrib('id', 'form_registro');
        $this->setMethod('post');
        
        $this->setDecorators(array(
            array('ViewScript', array('viewScript' => 'form/_registro.phtml')),
            'Form'
        ));
            
        //FIELDS        
        $nombre = new Zend_Form_Element_Text('nombre');
        $apellidos = new Zend_Form_Element_Text('apellidos');
        $email = new Zend_Form_Element_Text('email');
        $confirm_email = new Zend_Form_Element_Text('confirm_email');
        $password = new Zend_Form_Element_Password('password');
        $confirm_password = new Zend_Form_Element_Password('confirm_password');
        $codigo_postal = new Zend_Form_Element_Text('codigo_postal');
        $telefono_movil = new Zend_Form_Element_Text('telefono_movil');

        
        //FILTERS VALIDATORS & OTHERS
        $nombre     ->setRequired(true);
        $nombre     ->addValidator(new Eticketsecure_Validate_NotEmpty());
        $nombre     ->addFilter('StringTrim');
        
        $apellidos     ->setRequired(true);
        $apellidos     ->addValidator(new Eticketsecure_Validate_NotEmpty());
        $apellidos     ->addFilter('StringTrim');
        
        $email          ->setRequired(true);
        $email          ->addFilter('StringTrim');
        $email          ->addValidator(new Eticketsecure_Validate_NotEmpty());
        $email          ->addValidator(new Eticketsecure_Validate_EmailAddress());
        $email          ->addValidator(new Eticketsecure_Validate_EmailAddressUnique());
        
        $confirm_email  ->setRequired(true);
        $confirm_email  ->addFilter('StringTrim');
        $confirm_email  ->addValidator(new Eticketsecure_Validate_NotEmpty());
        $confirm_email  ->addValidator(new Eticketsecure_Validate_Identical('email'));
        
        $password       ->setRequired(true);   
        $password       ->addValidator(new Eticketsecure_Validate_NotEmpty());
        $confirm_password ->setRequired(true);   
        $confirm_password ->addValidator(new Eticketsecure_Validate_NotEmpty());
        $confirm_password ->addFilter('StringTrim');
        $confirm_password ->addValidator(new Eticketsecure_Validate_Identical('password'));
        
        $codigo_postal  ->setRequired(true);   
        $codigo_postal  ->addFilter('StringTrim');
        $codigo_postal  ->addValidator(new Eticketsecure_Validate_NotEmpty());
        $codigo_postal  ->addValidator(new Eticketsecure_Validate_CodigoPostal());   
        
        $telefono_movil ->setRequired(true);   
        $telefono_movil ->addValidator(new Eticketsecure_Validate_NotEmpty());
        $telefono_movil ->addFilter('StringTrim');
        $telefono_movil ->addValidator(new Eticketsecure_Validate_TelefonoMovil());
        
        //SUBMIT
        $submit = new Zend_Form_Element_Submit('submit');
 
        //ADDELEMENTS
        $this->addElements(array(      
            $nombre,
            $apellidos,
            $email,
            $confirm_email,
            $password,
            $confirm_password,
            $codigo_postal,
            $telefono_movil,
            $submit   
        ));
        
    }


}

