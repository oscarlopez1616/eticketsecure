<?php

class Eticketsecure_Validate_EmailAddress extends Zend_Validate_EmailAddress
{
 
  /**
   * Returns true if and only if $value is a email adress
   * @param string $value
   * @return boolean
   */
  public function isValid($value)
  {
    $response = parent::isValid($value);
    if (!$response)
    {
      $translate = Zend_Registry::get('Zend_Translate');
      $this->_messages =
        array(self::INVALID => $translate->_('form.validator.email.invalid'));
    }
    return $response;
  }
}

?>
