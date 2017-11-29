<?php

class Eticketsecure_Validate_NotEmpty extends Zend_Validate_NotEmpty
{
 
  /**
   * Returns true if and only if $value is a int number
   * @param string $value
   * @return boolean
   */
  public function isValid($value)
  {
      $translate = Zend_Registry::get('Zend_Translate');
    $response = parent::isValid($value);
    if (!$response)
    {
      $this->_messages =
        array(self::INVALID => $translate->_('form.validator.field.empty'));
    }
    return $response;
  }
}

?>
