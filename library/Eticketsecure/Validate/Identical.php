<?php

class Eticketsecure_Validate_Identical extends Zend_Validate_Identical
{
 
  /**
   * Returns true if and only if $value is a email adress
   * @param string $value
   * @return boolean
   */
  public function isValid($value, $context = null)
    {
        $translate = Zend_Registry::get('Zend_Translate');
        $this->_messages =
        array(  
                self::NOT_SAME=> $translate->_('form.validator.field.identical')
            );
        
        $this->_setValue((string) $value);

        if (($context !== null) && isset($context) && array_key_exists($this->getToken(), $context)) {
            $token = $context[$this->getToken()];
        } else {
            $token = $this->getToken();
        }

        if ($token === null) {
            $this->_error(self::MISSING_TOKEN);
            return false;
        }

        $strict = $this->getStrict();
        if (($strict && ($value !== $token)) || (!$strict && ($value != $token))) {
            $this->_messages =
            array(self::NOT_SAME=> $translate->_('form.validator.field.identical'));
            return false;
        }

        return true;
    }
}

?>
  