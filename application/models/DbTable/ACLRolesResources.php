<?php

class Application_Model_DbTable_ACLRolesResources extends Zend_Db_Table_Abstract
{

    protected $_name = 'acl_roles_resources';
    
    public function getAllACLRolesResources()
    {
        $sql = "select * from acl_roles_resources";
        $db = $this->getAdapter();
        $res = $db->query($sql);
        return $res->fetchAll();
    } 
}

