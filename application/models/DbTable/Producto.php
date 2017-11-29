<?php

class Application_Model_DbTable_Producto extends Zend_Db_Table_Abstract
{

    protected $_name = 'producto';
        

    public function getProductoById($id)
    {
        $sql = "select a.id,a.codigo_referencia,a.id_empresa,a.iva,a.representacion_xml,b.id_categoria from producto as a
                left join relacion_categoria_producto as b
                on a.id=b.id_producto
                where a.id=".$id;
        $db = $this->getAdapter();
        $res = $db->query($sql);
        $producto = $res->fetch();
        return $producto;
    } 

    public function addProducto($id_empresa,$representacion_xml,$iva)
    {
        $data = array(
                'id_empresa' => $id_empresa,
                'representacion_xml'=>$representacion_xml,                                          
                'iva'=>$iva,                      
                );
        $c=$this->insert($data);
        return $c;
    } 
    
    public function deleteProducto($id)
    {
        $sql = "delete from producto where id =".$id;
        $db = $this->getAdapter();
      
        $res = $db->query($sql);
        return $res->rowCount();
    } 
    
    
    public function setIva($id,$iva)
    {       
        $data = array(
                'iva' => $iva 
                );
        $affected_rows= $this->update($data, 'id = ' . $id);
        return $affected_rows;
    }
    
    public function setXml($id,$representacion_xml)
    {       
        $data = array(
                'representacion_xml' => $representacion_xml 
                );
        $affected_rows= $this->update($data, 'id = ' . $id);
        return $affected_rows;
    }
    
    public function setProducto($id,$representacion_xml,$iva){
        $data = array(
                'iva' => $iva,
                'representacion_xml' => $representacion_xml 
                );
        $affected_rows= $this->update($data, 'id = ' . $id);
        return $affected_rows;
    }

}

