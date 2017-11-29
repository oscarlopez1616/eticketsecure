<?php

class Application_Model_DbTable_FormaPago extends Zend_Db_Table_Abstract
{

    protected $_name = 'forma_pago';
    
    public function setPublicada($id,$publicada)
    {       
        $data = array(
                'publicada' => $publicada 
                );
        $res=$this->update($data, 'id = ' . $id);
        return $res;
    } 
    
    public function getAll($id_empresa)
    {
        $sql = "select * from forma_pago where id_empresa=".$id_empresa;
        $db = $this->getAdapter();
        $res = $db->query($sql);
        $datos = $res->fetchAll();
        return $datos;
    } 
    
    public function get($id)
    {
        $sql = "select * from forma_pago where id=".$id;
        $db = $this->getAdapter();
        $res = $db->query($sql);
        $datos = $res->fetchAll();
        return $datos[0];
    }  

}

