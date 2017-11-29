<?php

class Application_Model_DbTable_CarritoCompra extends Zend_Db_Table_Abstract
{
    protected function _setupTableName()
    {
        $this->_name = 'carrito_compra';
        $this->_id_empresa = 1;
        parent::_setupTableName();
    }
   
    public function cleanCarrito($segundos_limite)
    {
        $sql = "delete from carrito_compra where id_compra IS NULL and TIMESTAMPDIFF(SECOND,NOW(), fecha_insercion) > ".$segundos_limite;
        $db = $this->getAdapter();
        $res = $db->query($sql);
        $count = $res->rowCount();
        if ($count==0)  throw new Exception('cleanCarrito');
        return $count;
    } 
   
    
    public function deleteLineaCarritoById($id)
    {
        $sql = "delete from carrito_compra where id =".$id;
        $db = $this->getAdapter();
        $res = $db->query($sql);
        $count = $res->rowCount();
        if ($count==0)  throw new Exception('deleteLineaCarritoById');
        return $count;
    } 
    
    public function deleteByIdCompra($id_compra)
    {
        $sql = "delete from carrito_compra where id_compra=".$id_compra;
        $db = $this->getAdapter();
        $res = $db->query($sql);
        $count = $res->rowCount();
        if ($count==0)  throw new Exception('deleteByIdCompra');
        return $count;
    } 
    
    public function deleteCarritoByIdSession($id_session)
    {
        $sql = "delete from carrito_compra where id_session='".$id_session."'";
        $db = $this->getAdapter();
        $res = $db->query($sql);
        $count = $res->rowCount();
        if ($count==0)  throw new Exception('deleteCarritoByIdSession');
        return $count;
    } 
    
    public function deleteCarritoByIdUsuario($id_usuario)
    {
        $sql = "delete from carrito_compra where id_usuario=".$id_usuario;
        $db = $this->getAdapter();
        $res = $db->query($sql);
        $count = $res->rowCount();
        if ($count==0)  throw new Exception('deleteCarritoByIdUsuario');
        return $count;
    }
    
    public function getAllCarrito($id_empresa)
    {
        $sql = "select * from carrito_compra where id_empresa = ".$id_empresa;
        if(!$flag_id_compra) $sql = $sql." and id_compra is NULL";
        $db = $this->getAdapter();
        $res = $db->query($sql);
        return $res->fetchAll();
    } 
    
    public function getCarritoByIdSession($id_session)
    {
        $sql = "select * from carrito_compra where id_session='".$id_session."'";
        $db = $this->getAdapter();
        $res = $db->query($sql);
        $datos = $res->fetchAll();
        if (!$datos)  throw new Exception('getCarritoByIdSession');
        return $datos;
    } 
    
    public function getCarritoByIdUsuario($id_usuario)
    {
        $sql = "select * from carrito_compra where id_usuario=".$id_usuario;
        $db = $this->getAdapter();
        $res = $db->query($sql);
        $datos = $res->fetchAll();
        if (!$datos)  throw new Exception('getCarritoByIdUsuario');
        return $datos;
    } 
    
    public function getCarritoByIdCompra($id_compra)
    {
        $sql = "select * from carrito_compra where id_compra=".$id_compra;
        $db = $this->getAdapter();
        $res = $db->query($sql);
        $datos = $res->fetchAll();
        if (!$datos)  throw new Exception('getCarritoByIdCompra');
        return $datos;
    } 
    
    public function getLineaCarritoById($id)
    {
        $sql = "select * from carrito_compra where id=".$id;
        $db = $this->getAdapter();
        $res = $db->query($sql);
        $datos = $res->fetch();
        if (!$datos)  throw new Exception('getLineaCarritoById');
        return $datos;
    } 
    
    public function getLineaCarritoByLocalizador($localizador)
    {
        $sql = "select * from carrito_compra where localizador='".$localizador."'";
        $db = $this->getAdapter();
        $res = $db->query($sql);
        $datos = $res->fetch();
        if (!$datos)  throw new Exception('getLineaCarritoById');
        return $datos;
    } 
    
    public function setLineaCarritoById($id,$id_session,$id_agrupador,$id_producto,$id_parent,
            $representacion_xml,$fecha_insercion,$tipo_linea_producto,$cantidad,$pvp,$iva,
            $id_usuario,$localizador,$id_compra=NULL)
    {
        if($representacion_xml== "(Null)") $representacion_xml= NULL;
        $data = array(
                'id_session' => $id_session,
                'id_agrupador' => $id_agrupador,
                'id_empresa' => $this->_id_empresa,
                'id_producto' => $id_producto,
                'id_parent' => $id_parent,
                'representacion_xml' => $representacion_xml,
                'fecha_insercion' => $fecha_insercion,
                'tipo_linea_producto' => $tipo_linea_producto,
                'cantidad' => $cantidad,
                'pvp' => $pvp,
                'iva' => $iva,
                'id_usuario' => $id_usuario,
                'localizador' => $localizador,
                'id_compra' => $id_compra
                );
        $c=$this->update($data, "id = " . $id);
        if ($c==0)  throw new Exception('setLineaCarritoById');
        return $c;
    } 
    
    public function addlineaCarrito($id_session,$id_agrupador,$id_producto,$id_parent,
            $representacion_xml,$fecha_insercion,$tipo_linea_producto,$cantidad,$pvp,$iva,$id_usuario,
            $localizador,$id_compra=NULL)
    {
        $data = array(
                'id_session' => $id_session,
                'id_agrupador' => $id_agrupador,
                'id_empresa' => $this->_id_empresa,
                'id_producto'=>$id_producto,
                'id_parent'=>$id_parent,
                'representacion_xml' => $representacion_xml,
                'fecha_insercion' => $fecha_insercion,
                'tipo_linea_producto' => $tipo_linea_producto,
                'cantidad' => $cantidad,
                'pvp' => $pvp,
                'iva' => $iva,
                'localizador' => $localizador,
                'id_usuario' => $id_usuario
                );
        if($id_compra != NULL) $data['id_compra'] = $id_compra;
        $c=$this->insert($data);
        return $c;
    } 
         
}

