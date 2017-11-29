<?php

class Application_Model_DbTable_DefenderIpBaneada extends Zend_Db_Table_Abstract
{

    protected $_name = 'defender_ip_baneada';

    public function getAll()
    {
        $sql = "select * from defender_ip_baneada";
        $db = $this->getAdapter();
        $res = $db->query($sql);
        $datos = $res->fetchAll();
        return $datos;
    } 
    
    public function getByIp($ip)
    {
        $sql = "select * from defender_ip_baneada where ip='".$ip."'";
        $db = $this->getAdapter();
        $res = $db->query($sql);
        $datos = $res->fetch();
        return $datos;
    }
    
    public function addDefenderIpBaneada($ip,$fecha_baneo)
    {
        $data = array(
                'ip' => $ip,
                'fecha_baneo'=>$fecha_baneo                             
                );
        $c=$this->insert($data);
        return $c;
    } 
    
    public function setDefenderByIp($ip,$fecha_baneo)
    {
        $data = array(
                'fecha_baneo'=>$fecha_baneo      
                );
        $res=$this->update($data, 'ip = ' . $ip);
        return $res;
    }
    
    public function deleteDefenderByIp($ip)
    {
        $sql = "delete from defender_ip_baneada where ip ='".$ip."'";
        $db = $this->getAdapter();
      
        $res = $db->query($sql);
        return $res->rowCount();
    }  
}

