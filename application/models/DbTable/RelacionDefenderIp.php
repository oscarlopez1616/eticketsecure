<?php

class Application_Model_DbTable_RelacionDefenderIp extends Zend_Db_Table_Abstract
{

    protected $_name = 'relacion_defender_ip';


    public function getAll()
    {
        $sql = "select * from relacion_defender_ip";
        $db = $this->getAdapter();
        $res = $db->query($sql);
        $datos = $res->fetchAll();
        return $datos;
    }

    public function getById($id)
    {
        $sql = "select * from relacion_defender_ip where id=".$id;
        $db = $this->getAdapter();
        $res = $db->query($sql);
        $datos = $res->fetchAll();
        return $datos[0];
    }
    
    public function getByIdDefenderAndIp($id_defender,$ip)
    {
        $sql = "select * from relacion_defender_ip where id_defender=".$id_defender." and ip='".$ip."'";
        $db = $this->getAdapter();
        $res = $db->query($sql);
        $datos = $res->fetch();
        if (!$datos)  throw new Exception('No existe RelacionDefenderIp para este id_defender y esta ip');
        return $datos;
    }
    
    public function addRelacionDefenderIp($id_defender,$ip,$intentos,$fecha_ultimo_intento,$num_umbrales_excedidos)
    {
        $data = array(
                'id_defender' => $id_defender,
                'ip'=>$ip,                     
                'intentos' => $intentos,
                'fecha_ultimo_intento'=>$fecha_ultimo_intento,                     
                'num_umbrales_excedidos' => $num_umbrales_excedidos                  
                );
        $c=$this->insert($data);
        return $c;
    } 
    
    public function setRelacionDefenderIpById($id,$id_defender,$ip,$intentos,$fecha_ultimo_intento,$num_umbrales_excedidos)
    {
        $data = array(
                'id_defender' => $id_defender,
                'ip'=>$ip,                     
                'intentos' => $intentos,
                'fecha_ultimo_intento'=>$fecha_ultimo_intento,                     
                'num_umbrales_excedidos' => $num_umbrales_excedidos                   
                );
        $res=$this->update($data, 'id = ' . $id);
        return $res;
    }
    
    public function deleteRelacionDefenderIpById($id)
    {
        $sql = "delete from relacion_defender_ip where id =".$id;
        $db = $this->getAdapter();
      
        $res = $db->query($sql);
        return $res->rowCount();
    } 
}

