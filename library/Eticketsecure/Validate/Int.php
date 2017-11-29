<?php

class Eticketsecure_Validate_Int extends Zend_Validate_Int
{
 
  /**
   * Returns true if and only if $value is a int number
   * @param string $value
   * @return boolean
   */
  public function isValid($value)
  {
    $response = parent::isValid($value);
    if (!$response)
    {
      $this->_messages =
        array(self::INVALID => "El valor debe ser un número entero");
    }
    return $response;
  }
}

?>
