<?php

class Regalo_Model_DbTable_ModuleRegaloReservas extends Zend_Db_Table_Abstract
{
    protected function _setupTableName()
    {
        $this->_name = 'module_regalo_reservas';
        $this->_id_empresa = 1;
        parent::_setupTableName();
    }
    
    public function getById($id){
        $sql = 'select * from module_regalo_reservas
                where id='.$id;
        $db = $this->getAdapter();
        $res = $db->query($sql);
        $data = $res->fetch();
        if (!$data)  throw new Exception('getById');
        return $data;  
    }
    
    public function getByIdCompra($id_compra){
        $sql = 'select * from module_regalo_reservas
                where id_compra='.$id_compra;
        $db = $this->getAdapter();
        $res = $db->query($sql);
        $data = $res->fetch();
        if (!$data)  throw new Exception('getByIdCompra',3);
        return $data;  
    }
    
    public function setById($id,$id_sesion_webttneu08,$id_compra,$titulo_espectaculo,$fecha_reserva,
            $numero_entradas,$pvp_iva,$redimido,$fecha_realizacion_reserva,$nombre_teatro,$nombre_sala){
        
        $data = array(
                'id_empresa' => $this->_id_empresa, 
                'id_sesion_webttneu08' => $id_sesion_webttneu08, 
                'id_compra' => $id_compra, 
                'titulo_espectaculo' => $titulo_espectaculo, 
                'fecha_reserva' => $fecha_reserva, 
                'numero_entradas' => $numero_entradas, 
                'pvp_iva' => $pvp_iva, 
                'redimido' => $redimido, 
                'fecha_realizacion_reserva' => $fecha_realizacion_reserva,
                'nombre_teatro' => $nombre_teatro,
                'nombre_sala' => $nombre_sala
                );
        $affected_rows= $this->update($data, 'id = ' . $id);
        if ($affected_rows==0)  throw new Exception('setById');
        return $affected_rows;   
    }
    
    public function setRedimidoById($id,$redimido){
        $data = array(
                'redimido' => $redimido
                );
        $affected_rows= $this->update($data, 'id = ' . $id);
        if ($affected_rows==0)  throw new Exception('setById');
        return $affected_rows;   
    }
    
    public function add($id_sesion_webttneu08,$id_compra,$titulo_espectaculo,$fecha_reserva,
            $numero_entradas,$pvp_iva,$redimido,$fecha_realizacion_reserva,$nombre_teatro,$nombre_sala){
        
        $data = array(
                'id_empresa' => $this->_id_empresa, 
                'id_sesion_webttneu08' => $id_sesion_webttneu08, 
                'id_compra' => $id_compra, 
                'titulo_espectaculo' => $titulo_espectaculo, 
                'fecha_reserva' => $fecha_reserva, 
                'numero_entradas' => $numero_entradas, 
                'pvp_iva' => $pvp_iva, 
                'redimido' => $redimido, 
                'fecha_realizacion_reserva' => $fecha_realizacion_reserva,
                'nombre_teatro' => $nombre_teatro,
                'nombre_sala' => $nombre_sala        
                );
        $c=$this->insert($data);
        return $c;
    }
}

