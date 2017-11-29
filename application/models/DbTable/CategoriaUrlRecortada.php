<?php

class Application_Model_DbTable_CategoriaUrlRecortada extends Zend_Db_Table_Abstract
{

    protected function _setupTableName()
    {
        $this->_name = 'categoria_url_recortada';
        $this->_id_empresa = 1;
        parent::_setupTableName();
    }
    
    public function deleteById($id)
    {
        $sql = "delete from categoria_url_recortada where id =".$id;
        $db = $this->getAdapter();
        $res = $db->query($sql);
        $count = $res->rowCount();
        if ($count==0)  throw new Exception('deleteById');
        return $count;
    }      
    
    public function getById($id)
    {
        $sql = "select * from categoria_url_recortada where id=".$id;
        $db = $this->getAdapter();
        $res = $db->query($sql);
        $datos = $res->fetch();
        if (!$datos)  throw new Exception('getById');
        return $datos;
    } 
      
    
    public function add($nombre)
    {
        $data = array(
                'id_empresa' => $this->_id_empresa,
                'nombre'=>$nombre          
                );
        $c=$this->insert($data);
        return $c;
    }
    
    public function setById($id,$nombre)
    {
        $data = array(
                'id_empresa' => $this->_id_empresa,
                'nombre'=>$nombre 
                );
        $res=$this->update($data, 'id = ' . $id);
        if ($res==0)  throw new Exception('setById');
        return $res;
    }

}

