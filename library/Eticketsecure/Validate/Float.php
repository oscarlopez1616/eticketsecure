<?php

class Eticketsecure_Validate_Float extends Zend_Validate_Float
{
 
  /**
   * Returns true if and only if $value is a float number
   * @param string $value
   * @return boolean
   */
  public function isValid($value)
  {
    $response = parent::isValid($value);
    if (!$response)
    {
      $this->_messages =
        array(self::INVALID => "El valor debe ser un número float");
    }
    return $response;
  }
}

?>
