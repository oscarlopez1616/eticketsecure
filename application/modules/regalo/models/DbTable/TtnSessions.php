<?php

class Regalo_Model_DbTable_TtnSessions extends Zend_Db_Table_Abstract
{


    protected $_name = 'ttn_sessions';
    
    protected function _setupDatabaseAdapter()
    {
        $reg_bbdd_teatreneu_arr=Zend_Registry::get('bbdd_teatreneu');
        
        $config = new Zend_Config($reg_bbdd_teatreneu_arr);
        $db = Zend_Db::factory($config->database);
        $this->_db = $db;
        parent::_setupDatabaseAdapter();
    }

    public function getByIdEspectacleAndDataAndHoraMasGrandeQueNowOrderByData($id_espectacle){
        $sql = 'select * from ttn_sessions
                where idespectacle='.$id_espectacle.' 
                and data>"'.date("Y-m-d").'"
                and entrades>0
                and funcio_anullada=0
                Order By data and hora';
        $db = $this->getAdapter();
        $res = $db->query($sql);
        $data = $res->fetchAll();
        if (!$data)  throw new Exception('getByIdEspectacleAndDataAndHoraMasGrandeQueNowOrderByDataAndHora');
        return $data;  
    }
    
    public function getById($id){
        $sql = 'select * from ttn_sessions
                where id='.$id;
        $db = $this->getAdapter();
        $res = $db->query($sql);
        $data = $res->fetch();
        if (!$data)  throw new Exception('getById');
        return $data;  
    }
    
    public function setEntradas($id,$entrades){
        $sesion = $this->getById($id);
        $entrades = $sesion["entrades"]-$entrades;
        $data = array(
                'entrades' => $entrades 
                );
        $affected_rows= $this->update($data, 'id = ' . $id);
        if ($affected_rows==0)  throw new Exception('setEntradas');
        return $affected_rows;   
    }
}

