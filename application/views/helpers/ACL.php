<?php
class Zend_View_Helper_ACL {
    
    /**
     * 
     * @param Zend_Acl $ACL
     * @param string $role_id
     * @param string $resource_module
     * @param string $resource_controller
     * @param string $privilege
     * @param string $out_string es la salida que queremos que muestre en la interfaz grafica
     * @return string
     */
    function ACLLinksByRoleResourcePrivilege($ACL,$role_id,$resource_module,$resource_controller,$privilege,$out_string) {
        $resource = $resource_module."-".$resource_controller;
        try{
            if($ACL->isAllowed($role_id,$resource,$privilege)){ 
                $string =  "";
            }else{
                $string = $out_string;
            }
            return $string;
        }catch (Exception $e) {
            return $out_string;
        }
    } 
    
    
    /**
     * 
     * @param Zend_Acl $ACL
     * @param string $role_id
     * @param string $role_id_descuento
     * @param string $out_string es la salida que queremos que muestre en la interfaz grafica
     * @return string
     */
    function ACLLinksByRole($ACL,$role_id,$role_id_descuento,$out_string) {
        try{
            if ($ACL->inheritsRole($role_id, $role_id_descuento)) {
                $string =  "";
            }else{
                $string = $out_string;
            }
            return $string;
        }catch (Exception $e) {
            return $out_string;
        }
    }
    
}
?>
