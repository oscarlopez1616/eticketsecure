<?php

class Application_Model_DbTable_ACLResources extends Zend_Db_Table_Abstract
{

    protected $_name = 'acl_resources';

    public function getAllACLResources()
    {
        $sql = "select DISTINCT(resource) from acl_resources";
        $db = $this->getAdapter();
        $res = $db->query($sql);
        return $res->fetchAll();
    } 

}

