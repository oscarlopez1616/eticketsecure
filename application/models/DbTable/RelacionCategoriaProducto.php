<?php

class Application_Model_DbTable_RelacionCategoriaProducto extends Zend_Db_Table_Abstract
{

    protected $_name = 'relacion_categoria_producto';
    
    public function getIdProductoArrByIdCategoria($id_categoria,$num_pagina="all",$elementos_pagina="all")
    {
        $sql = "select id_producto from relacion_categoria_producto where id_categoria=".$id_categoria;
        if($num_pagina!= "all" && $elementos_pagina!="all"){
          $offset = $num_pagina*$elementos_pagina;  
          $sql.=" limit ".$offset.",".$elementos_pagina." ";  
        }
        $db = $this->getAdapter();
        $res = $db->query($sql);
        $datos_arr = $res->fetchAll();
        return $datos_arr;
    }
    
    public function getIdProductoArrByIdCategoriaArr($id_categoria_arr,$num_pagina="all",$elementos_pagina="all")
    {
        $sql = "select id_producto from relacion_categoria_producto where";
        for($i=0;$i<count($id_categoria_arr);$i++){
          if($i>0)$sql.=" or ";
            $sql.=" id_categoria=".$id_categoria_arr[$i];
        }
        
        if($num_pagina!= "all" && $elementos_pagina!="all"){
          $offset = $num_pagina*$elementos_pagina;  
          $sql.=" limit ".$offset.",".$elementos_pagina." ";  
        }
        $db = $this->getAdapter();
        $res = $db->query($sql);
        $datos_arr = $res->fetchAll();
        return $datos_arr;
    }
    
    
    public function addRelacionCategoria($id_producto,$id_categoria)
    {
        $data = array(
                'id_producto' => $id_producto,
                'id_categoria'=>$id_categoria                    
                );
        $c=$this->insert($data);
        return $c;
    } 


}

