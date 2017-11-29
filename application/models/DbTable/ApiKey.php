<?php

class Application_Model_DbTable_ApiKey extends Zend_Db_Table_Abstract
{

    protected $_name = 'api_key';
    
    public function apiKey($ip,$id_empresa)
    {
        $sql = "select api_key  from api_key where ip='".$ip."' and id_empresa=".$id_empresa;
        $db = $this->getAdapter();
        $res = $db->query($sql);
        $arr = $res->fetch();
        return $arr['api_key'];
    }

}

