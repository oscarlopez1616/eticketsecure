<?php

class Eticketsecure_Validate_TelefonoMovil extends Zend_Validate_Digits
{
    const NOT_LENGTH   = 'notLenght';
    const NOT_STARTS   = 'notStarts';
    
    public function isValid($value)
    {
        $translate = Zend_Registry::get('Zend_Translate');
        $response = parent::isValid($value);

        if (!$response){
            $this->_messages =array(self::INVALID => $translate->_('form.validator.movil.digits'));
        }
        
        if (strlen($value)!=9){
            $this->_messages =
            array(self::NOT_LENGTH => $translate->_('form.validator.movil.length'));
            return false;
        }
        
        $tmp = substr($value,0,1);
        if ($tmp != 6 && $tmp != 7){
            $this->_messages =array(self::NOT_STARTS => $translate->_('form.validator.movil.starts'));
            return false;
        }
        
        return $response;
    }
}
?>
