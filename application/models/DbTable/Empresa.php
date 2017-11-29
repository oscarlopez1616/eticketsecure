<?php

class Application_Model_DbTable_Empresa extends Zend_Db_Table_Abstract
{

    protected $_name = 'empresa';
    
    public function autenticate($id,$secure)
    {
        $sql = "select count(*) as c from empresa where id=".$id." and secure='".$secure."'";
        $db = $this->getAdapter();
        $res = $db->query($sql);
        $arr = $res->fetch();
        return $arr['c'];
    } 


}

