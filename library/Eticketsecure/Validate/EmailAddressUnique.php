<?php

class Eticketsecure_Validate_EmailAddressUnique extends Zend_Validate_Abstract
{
  const NOT_VALID         = 'notValid';
  /**
   * Returns true if and only if $value is a email adress
   * @param string $value es el $email a comprobar
   * @return boolean
   */
  public function isValid($value)
  {
      try{// si existe el email sera duplicado y error
        $InterfaceUsuario = new Application_Model_InterfaceUsuarioEticketSecure();
        $id = $InterfaceUsuario->getIdByEmail($value);
        $Usuario = new Application_Model_Usuario();
        $Usuario->load($id["id"]);
        $translate = Zend_Registry::get('Zend_Translate');
        $this->_messages = array(self::NOT_VALID => $translate->_('form.validator.email.unique'));
        return false;
    }catch (Exception $e){
       return true; 
    }
  }
}

?>
