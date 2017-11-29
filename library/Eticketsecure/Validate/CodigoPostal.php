<?php

class Eticketsecure_Validate_CodigoPostal extends Zend_Validate_Digits
{
    const NOT_LENGTH   = 'notLenght';
    
    public function isValid($value)
    {
        $translate = Zend_Registry::get('Zend_Translate');
        $response = parent::isValid($value);

        if (!$response)
        {
            $this->_messages =array(self::INVALID => $translate->_('form.validator.postal.digits'));
        }
        if (strlen($value)!=5){
            $this->_messages =
            array(self::NOT_LENGTH => $translate->_('form.validator.postal.length'));
        }
        
        return $response;
    }
}
?>
