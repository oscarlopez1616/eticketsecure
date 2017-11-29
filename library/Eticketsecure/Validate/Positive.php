<?php

class Eticketsecure_Validate_Positive extends Zend_Validate_GreaterThan
{
 
  public function isValid($value)
  {
    $response = parent::isValid($value);
    if (!$response)
    {
      $this->_messages =
        array(self::NOT_GREATER => "El valor debe ser un número positivo");
    }
    return $response;
  }
}

?>
