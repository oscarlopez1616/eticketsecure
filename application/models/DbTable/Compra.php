<?php

class Application_Model_DbTable_Compra extends Zend_Db_Table_Abstract
{

    protected function _setupTableName()
    {
        $this->_name = 'compra';
        $this->_id_empresa = 1;
        parent::_setupTableName();
    }
    
    public function deleteCompraById($id)
    {
        $sql = "delete from compra where id =".$id;
        $db = $this->getAdapter();
        $res = $db->query($sql);
        $count = $res->rowCount();
        if ($count==0)  throw new Exception('deleteCompraById');
        return $count;
    }      
    
    public function getCompraById($id_compra)
    {
        $sql = "select * from compra where id=".$id_compra;
        $db = $this->getAdapter();
        $res = $db->query($sql);
        $datos = $res->fetch();
        if (!$datos)  throw new Exception('getCompraById');
        return $datos;
    } 
    
    public function getCompraByLocalizador($localizador)
    {
        $sql = "select * from compra where localizador='".$localizador."'";
        $db = $this->getAdapter();
        $res = $db->query($sql);
        $datos = $res->fetch();
        if (!$datos)  throw new Exception('getCompraByLocalizador');
        return $datos;
    } 
    
    public function getCompraByIdUsuario($id_usuario){
        $sql = "select id from compra where id_usuario=".$id_usuario;
        $db = $this->getAdapter();
        $res = $db->query($sql);
        $datos = $res->fetchAll();
        if (!$datos)  throw new Exception('getCompraByIdUsuario');
        $compras_arr = array();
        foreach ($datos as $r){
            $aux = $this->getCompraById($r["id"]);
            $compras_arr[] =$aux;
        }
        return $compras_arr;       
    }
        
    
    public function addCompra($id_usuario,$fecha,$id_forma_pago,$pagada,$enviada,$procesada,$direccion_xml,$gastos_envio,$localizador)
    {
        $data = array(
                'id_empresa' => $this->_id_empresa,
                'id_usuario'=>$id_usuario,
                'fecha'=>$fecha,
                'id_forma_pago'=>$id_forma_pago,
                'pagada'=>$pagada,
                'enviada' => $enviada,
                'procesada'=>$procesada,
                'direccion_xml'=>$direccion_xml,
                'gastos_envio'=>$gastos_envio,              
                'localizador'=>$localizador              
                );
        $c=$this->insert($data);
        return $c;
    }
    
    public function setCompraById($id,$id_usuario,$fecha,$id_forma_pago,$pagada,$enviada,$procesada,$direccion_xml,$gastos_envio,$localizador)
    {
        $data = array(
                'id_empresa' => $this->_id_empresa,
                'id_usuario'=>$id_usuario,
                'fecha'=>$fecha,
                'id_forma_pago'=>$id_forma_pago,
                'pagada'=>$pagada,
                'enviada' => $enviada,
                'procesada'=>$procesada,
                'direccion_xml'=>$direccion_xml,
                'gastos_envio'=>$gastos_envio,
                'localizador'=>$localizador    
                );
        $res=$this->update($data, 'id = ' . $id);
        if ($res==0)  throw new Exception('setCompraById');
        return $res;
    }


}

