<?php

class Application_Model_DbTable_Defender extends Zend_Db_Table_Abstract
{

    protected $_name = 'defender';
    
    public function getAll()
    {
        $sql = "select * from defender";
        $db = $this->getAdapter();
        $res = $db->query($sql);
        $datos = $res->fetchAll();
        return $datos;
    } 
    
    public function getById($id)
    {
        $sql = "select * from defender where id=".$id;
        $db = $this->getAdapter();
        $res = $db->query($sql);
        $datos = $res->fetchAll();
        return $datos[0];
    }
    
    public function getByNombreProceso($nombre_proceso)
    {
        $sql = "select * from defender where nombre_proceso='".$nombre_proceso."'";
        $db = $this->getAdapter();
        $res = $db->query($sql);
        $datos = $res->fetchAll();
        return $datos[0];
    }
    
    public function addDefender($nombre_proceso,$umbral,$umbral_excedido)
    {
        $data = array(
                'nombre_proceso' => $nombre_proceso,
                'umbral'=>$umbral,                                   
                'umbral_excedido'=>$umbral_excedido                                   
                );
        $c=$this->insert($data);
        return $c;
    } 
    
    public function setDefenderById($id,$nombre_proceso,$umbral,$umbral_excedido)
    {
        $data = array(
                'nombre_proceso' => $nombre_proceso,
                'umbral'=>$umbral,
                'umbral_excedido'=>$umbral_excedido 
                );
        $res=$this->update($data, 'id = ' . $id);
        return $res;
    }
    
    public function deleteDefenderById($id)
    {
        $sql = "delete from defender where id =".$id;
        $db = $this->getAdapter();
      
        $res = $db->query($sql);
        return $res->rowCount();
    }  

}

