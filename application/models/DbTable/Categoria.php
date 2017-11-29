<?php

class Application_Model_DbTable_Categoria extends Zend_Db_Table_Abstract
{

    protected $_name = 'categoria';
    
    public function getIdSubCategoriaArr($id)
    {
        $sql = "select id from categoria where id_parent=".$id;
        $db = $this->getAdapter();
        $res = $db->query($sql);
        if ($res->rowCount()==0) throw new Exception('No existen SubCategorias para esta categoria'); 
        $categoria_arr= $res->fetchAll();
        return $categoria_arr;
    }  
      
    
    public function getCategoriaById($id)
    {
        $sql = "select * from categoria where id=".$id;
        $db = $this->getAdapter();
        $res = $db->query($sql);
        $categoria_arr = $res->fetchAll(); 
        return $categoria_arr[0];
    } 
    
    /**
     * Funcion que funcionar para buscar sobre cualquier campo de representacion_xml
     * @param int $id
     * @param string $name_campo_orden
     * @param string $operator
     * @param mixed(int,string) $valor_a_operar
     * @param string $valor_a_operar por defecto es "int","string"
     * @param string $order_type por defecto es "ASC" o "DESC"
     * @param string $limit_start 0 por defecto es el valor donde empiezan los resultados
     * @param mixed(int,string) $limit_stop "MAX" por defecto MAX que son todos si no el un entero donde acabarn los resultados
     * @return string
     */
    public function getIdProductoArrByIdCategoriaByFieldValue($id,$name_campo_orden,$operator,
            $valor_a_operar,$type_valor_a_operar="int",$order_type="ASC",$limit_start=0,$limit_stop="MAX")
    {
        $length_campo_orden = strlen($name_campo_orden)+2;//+2 porque hay que sumar las etiquetas de apertura y cierre xml <>
        if ($type_valor_a_operar=='int'){
            $sql_encontrar_valor_orden="CONVERT(MID(a.representacion_xml,
                                    (LOCATE('<".$name_campo_orden.">',a.representacion_xml))+".$length_campo_orden.",
                                    (LOCATE('</".$name_campo_orden.">',a.representacion_xml))-(LOCATE('<".$name_campo_orden.">',a.representacion_xml))-".$length_campo_orden."), UNSIGNED INTEGER )";
            
        }else{
            $sql_encontrar_valor_orden="MID(a.representacion_xml,
                                    (LOCATE('<".$name_campo_orden.">',a.representacion_xml))+".$length_campo_orden.",
                                    (LOCATE('</".$name_campo_orden.">',a.representacion_xml))-(LOCATE('<".$name_campo_orden.">',a.representacion_xml))-".$length_campo_orden.") ";            
        }
        
        $sql = "select a.id,b.id_categoria,";
        $sql.= $sql_encontrar_valor_orden." as order_field ";
        $sql.="from producto a
               inner join relacion_categoria_producto b
               on a.id=b.id_producto
               where ";
        $sql.=$sql_encontrar_valor_orden;
        if($type_valor_a_operar == "int"){
            $sql.=$operator." ".$valor_a_operar." "; //no hacen falta las comillas del string   
        }else if($type_valor_a_operar == "string"){
            $sql.=$operator." '".$valor_a_operar."' ";//añadimos las comillas del string    
        }
        
        $sql.= "and id_categoria=".$id." ";
        $sql.= "Order By order_field "; 
        if($order_type == "ASC"){
            $sql.= "ASC "; 
        }else if($order_type == "DESC"){
            $sql.= "DESC ";    
        }
        
        if($limit_stop!="MAX"){
            $sql.="LIMIT ".$limit_start." , ".($limit_stop-$limit_start);           
        }
        $db = $this->getAdapter();
        $res = $db->query($sql);
        $categoria_arr = $res->fetchAll(); 
        return $categoria_arr;
    } 
    
    
    public function getIdCategoriaArrByAltura($altura)
    {
        $sql = "select id from categoria where altura=".$altura;
        $db = $this->getAdapter();
        $res = $db->query($sql);
        $categoria_arr = $res->fetchAll();
        return $categoria_arr;
    } 
    
    public function getIdCategoriaArrByAlturaByIdParent($altura,$id_parent) 
    {
        $sql = "select id from categoria where altura=".$altura." and id_parent=".$id_parent;
        $db = $this->getAdapter();
        $res = $db->query($sql);
        $categoria_arr = $res->fetchAll();
        return $categoria_arr;
    } 
    
    private function existeSubCategorias($id)
    {
        $sql = "select count(*) as c from categoria where id_parent = ".$id;
        $db = $this->getAdapter();
        $res = $db->query($sql);
        $existe = $res->fetch();
        return $existe["c"];
    } 
    
    public function deleteCategoria($id)
    {
        if($this->existeSubCategorias($id)>=1) throw new Exception('No se puede Borrar tiene Subcategorias');
        $sql = "delete from categoria where id =".$id;
        $db = $this->getAdapter();
        $res = $db->query($sql);
        return $res->rowCount();
    }
    
    private function getAlturaParent($id_parent)
    {
        $sql = "select altura from categoria where id = ".$id_parent;
        $db = $this->getAdapter();
        $res = $db->query($sql);
        $altura = $res->fetch();
        return $altura["altura"];
    }    
    
    public function addCategoria($id_empresa,$representacion_xml,$publicado,$id_parent)
    {
        if($id_parent==0){
            $altura=0;
        }else{
            $altura=$this->getAlturaParent($id_parent);
            $altura=$altura+1;
        }
        
        $data = array(
                'id_empresa' => $id_empresa,
                'representacion_xml'=>$representacion_xml,
                'id_parent' => $id_parent,
                'publicado'=>$publicado,
                'altura'=>$altura            
                );
        $c=$this->insert($data);
        $info_crea_categoria_arr = array("last_insert_id"=>$c,"altura"=>$altura);
        return $info_crea_categoria_arr;
    }
   
    public function setCategoriaById($id,$id_empresa,$representacion_xml,$publicado)
    {
        $data = array(
                'id_empresa' => $id_empresa,
                'representacion_xml'=>$representacion_xml,
                'publicado'=>$publicado,        
                );
        $res=$this->update($data, 'id = ' . $id);
        return $res;
    }


}

