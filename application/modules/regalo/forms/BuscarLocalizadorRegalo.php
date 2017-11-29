<?php

class Regalo_Form_BuscarLocalizadorRegalo extends Zend_Form
{

    public function init()
    {
        $this->setAttrib('id', 'form_buscar_localizador_regalo');
        $this->setMethod('post');        
        
        $this->setDecorators(array(
            array('ViewScript', array('viewScript' => 'form/_buscar_localizador_regalo.phtml')),
            'Form'
        ));
               
        //FIELDS
       
        $localizador_compra    = new Zend_Form_Element_Text('localizador_compra');
        
        //FILTERS & VALIDATORS

        
        $localizador_compra   ->setRequired(true);
        $localizador_compra   ->addValidator(new Eticketsecure_Validate_NotEmpty());
        
        //SUBMIT
        $submit = new Zend_Form_Element_Submit('submit');
 
        //ADDELEMENTS
        $this->addElements(array(      
            $localizador_compra,
            $submit   
        ));
    }

}

