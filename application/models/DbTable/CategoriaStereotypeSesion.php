<?php

class Application_Model_DbTable_CategoriaStereotypeSesion extends Zend_Db_Table_Abstract
{

    protected $_name = 'categoria_producto';
    
    
    public function getIdProductoArrFromDateTime($id_categoria,$DateTime,$Order="ASC")
    {
        $fecha_inicial = $DateTime->format("Y-m-d H:i:s");
        $sql = "select *
                from
                (select a.id,STR_TO_DATE(ExtractValue (a.representacion_xml, '//date_time'), '%Y-%m-%d %h:%i:%s') as datetime
                from producto as a
                left JOIN relacion_categoria_producto as b
                on  a.id=b.id_producto
                where b.id_categoria=".$id_categoria.") as datos

                where datetime > '".$fecha_inicial."' Order By datetime ".$Order;
        $db = $this->getAdapter();
        $res = $db->query($sql);
        $datos_arr = $res->fetchAll();
        return $datos_arr;
    }
    
    public function getIdProductoArrFromNow($id_categoria,$Order="ASC")
    {
        $fecha_inicial = date("Y-m-d H:i:s");
        $sql = "select *
                from
                (select a.id,STR_TO_DATE(ExtractValue (a.representacion_xml, '//date_time'), '%Y-%m-%d %h:%i:%s') as datetime
                from producto as a
                left JOIN relacion_categoria_producto as b
                on  a.id=b.id_producto
                where b.id_categoria=".$id_categoria.") as datos

                where datetime > '".$fecha_inicial."' Order By datetime ".$Order;
        $db = $this->getAdapter();
        $res = $db->query($sql);
        $datos_arr = $res->fetchAll();
        return $datos_arr;
    }
    
    
    public function getIdProductoArrByIdCategoriaAndDateTimeInterval($id_categoria,$DateTime,$page,$Order="ASC")
    {
        $OperaDateTime = new DateTime($DateTime->format("Y-m-d H:i:s"));
        $fecha_inicial = $OperaDateTime->format("Y-m-d H:i:s");
        $dias = 7;
        if($page >0 ){
            $dias = $dias*$page;
            $string_interval = (string)($dias." day");
            $DateInterval = DateInterval::createFromDateString($string_interval);
            $fecha_inicial = $OperaDateTime->add($DateInterval)->format("Y-m-d H:i:s");
        }
        $string_interval = (string)("6 day");
        $DateInterval = DateInterval::createFromDateString($string_interval);
        $fecha_final = $OperaDateTime->add($DateInterval)->format("Y-m-d H:i:s");
        
        $sql = "select *
                from
                (select a.id,STR_TO_DATE(ExtractValue (a.representacion_xml, '//date_time'), '%Y-%m-%d %h:%i:%s') as datetime
                from producto as a
                left JOIN relacion_categoria_producto as b
                on  a.id=b.id_producto
                where b.id_categoria=".$id_categoria.") as datos

                where datetime BETWEEN '".$fecha_inicial."' and '".$fecha_final."' Order By datetime ".$Order;
        $db = $this->getAdapter();
        $res = $db->query($sql);
        $datos_arr = $res->fetchAll();
        return $datos_arr;
    }


}


