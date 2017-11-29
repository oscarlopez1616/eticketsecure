<?php

class Application_Model_DbTable_RecortadorUrl extends Zend_Db_Table_Abstract
{
    protected function _setupTableName()
    {
        $this->_name = 'recortador_url';
        $this->_id_empresa = 1;
        parent::_setupTableName();
    }
    
    public function deleteById($id)
    {
        $sql = "delete from recortador_url where id =".$id;
        $db = $this->getAdapter();
        $res = $db->query($sql);
        $count = $res->rowCount();
        if ($count==0)  throw new Exception('deleteById');
        return $count;
    }      
    
    public function getById($id)
    {
        $sql = "select * from recortador_url where id=".$id;
        $db = $this->getAdapter();
        $res = $db->query($sql);
        $datos = $res->fetch();
        if (!$datos)  throw new Exception('getById');
        return $datos;
    } 
    
    public function getLastInsertId()
    {
        $sql = "select  LAST_INSERT_ID() from recortador_url";
        $db = $this->getAdapter();
        $res = $db->query($sql);
        $datos = $res->fetch();
        if (!$datos)  throw new Exception('getById');
        return $datos;
    } 
    
    public function getByUrlCompleta($url_completa)
    {
        $sql = "select * from recortador_url where url_completa='".$url_completa."'";
        $db = $this->getAdapter();
        $res = $db->query($sql);
        $datos = $res->fetch();
        if (!$datos)  throw new Exception('getByUrlCompleta');
        return $datos;
    } 
    
    public function getByUrlRecortada($url_recortada)
    {
        $sql = "select * from recortador_url where url_recortada='".$url_recortada."'";
        $db = $this->getAdapter();
        $res = $db->query($sql);
        $datos = $res->fetch();
        if (!$datos)  throw new Exception('getByUrlCompleta');
        return $datos;
    } 
    
    public function getByIdCategoria($id_categoria)
    {
        $sql = "select * from recortador_url where id_categoria=".$id_categoria;
        $db = $this->getAdapter();
        $res = $db->query($sql);
        $datos = $res->fetchAll();
        if (!$datos)  throw new Exception('getByIdCategoria');
        return $datos;
    } 
      
    
    public function add($url_completa,$url_recortada,$id_categoria)
    {
        $data = array(
                'id_empresa' => $this->_id_empresa,
                'url_completa'=>$url_completa,
                'url_recortada'=>$url_recortada,
                'id_categoria'=>$id_categoria            
                );
        $c=$this->insert($data);
        return $c;
    }
    
    public function setById($id,$url_completa,$url_recortada,$id_categoria)
    {
        $data = array(
                'id_empresa' => $this->_id_empresa,
                'url_completa'=>$url_completa,
                'url_recortada'=>$url_recortada,
                'id_categoria'=>$id_categoria            
                );
        $res=$this->update($data, 'id = ' . $id);
        if ($res==0)  throw new Exception('setById');
        return $res;
    }


}

