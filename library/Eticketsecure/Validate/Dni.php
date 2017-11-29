<?php

class Eticketsecure_Validate_Dni extends Zend_Validate_Abstract
{
    const INVALID_FORMAT    = 'invalidFormat';
    const NOT_VALID         = 'notValid';
    
    public function isValid($value)
    {
        $translate = Zend_Registry::get('Zend_Translate');
        $value = strtoupper($value);
    
        for ($i = 0; $i < 9; $i ++){
        $num[$i] = substr($value, $i, 1);
        }
    
        if (!preg_match('(^[0-9]{8}[A-Z]{1}$)', $value)){
            $this->_messages =  array(self::INVALID_FORMAT=> $translate->_('form.validator.dni.format'));
            return false;
        }
    
        if ($num[8] != substr('TRWAGMYFPDXBNJZSQVHLCKE', substr($value, 0, 8) % 23, 1)){
            $this->_messages =  array(self::NOT_VALID=> $translate->_('form.validator.dni.invalid'));
            return false;
        }
        
        return true;
    }
}
?>
