<?php

class Application_Model_DbTable_ACLRoles extends Zend_Db_Table_Abstract
{

    protected $_name = 'acl_roles';

    public function getACLRoleByIdRole($id_role)
    {
        $sql = "select *,(select b.name from acl_roles as b where a.id_inherit_allow=b.id) as name_inherit_allow 
                from acl_roles as a
                where a.id=".$id_role."
                ORDER BY id ASC";
        $db = $this->getAdapter();
        $res = $db->query($sql);
        return $res->fetch();
    } 
    
    public function getAllACLRoles()
    {
        $sql = "select *,(select b.name from acl_roles as b where a.id_inherit_allow=b.id) as name_inherit_allow 
                from acl_roles as a
                ORDER BY id ASC";
        $db = $this->getAdapter();
        $res = $db->query($sql);
        return $res->fetchAll();
    } 
    
    public function getAllACLRolePermissionResources()
    {
        $sql = "SELECT rol.name as role_name, re.resource as resource_name, re.privilege as resource_privilege FROM acl_roles as rol 
            JOIN acl_roles_resources as rr ON rol.id = rr.id_role 
            JOIN acl_resources as re ON re.id = rr.id_resource";
        $db = $this->getAdapter();
        $res = $db->query($sql);
        return $res->fetchAll();
    }   
}

