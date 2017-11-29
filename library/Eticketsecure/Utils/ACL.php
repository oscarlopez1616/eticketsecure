<?php
class Eticketsecure_Utils_ACL {

    /**
     * 
     * @return \Zend_Acl
     */
    public function initACL() {

        $InterfaceACL = new Application_Model_InterfaceACLEticketSecure();
        
        $ACL = new Zend_Acl();

        // Add roles and Inheritance
        $rsRoles = $InterfaceACL->getAllACLRoles();
        foreach ($rsRoles as $row){ 
            if(isset($row['name_inherit_allow'])){
                if($row['name_inherit_allow']!=""){
                    $ACL->addRole(new Zend_Acl_Role($row['name']),new Zend_Acl_Role($row['name_inherit_allow']));
                }else{
                    $ACL->addRole(new Zend_Acl_Role($row['name']));
                }
            }else{
                $ACL->addRole(new Zend_Acl_Role($row['name']));
            }
        }
        // Add resources
        $rsResources = $InterfaceACL->getAllACLResources();         
        
        foreach ($rsResources as $row) $ACL->add(new Zend_Acl_Resource($row['resource']));

        // Add permissions

        $rsRolesResources = $InterfaceACL->getAllACLRolePermissionResources();

        foreach ($rsRolesResources as $row) $ACL->allow($row['role_name'], $row['resource_name'],$row['resource_privilege']);
        
        return $ACL;
    }
    
    
    
}
?>
