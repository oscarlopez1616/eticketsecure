<?php

class Regalo_Model_DbTable_TtnEspectacles extends Zend_Db_Table_Abstract
{

    protected $_name = 'ttn_espectacles';
    
    protected function _setupDatabaseAdapter()
    {
        $reg_bbdd_teatreneu_arr=Zend_Registry::get('bbdd_teatreneu');
        
        $config = new Zend_Config($reg_bbdd_teatreneu_arr);
        $db = Zend_Db::factory($config->database);
        $this->_db = $db;
        parent::_setupDatabaseAdapter();
    }
    
    public function getAllByDataMasGrandeQueHoy()
    {
        $sql = 'select a.id as id_espectacle,b.id as id_sessions,a.espai,a.titol,a.espai,
            descripcio_curt_es,descripcio_curt_ca,a.foto_gran,a.youtube 
            from ttn_espectacles  as a
            inner join ttn_sessions as b on a.id = b.idespectacle
            where  b.data>"'.date("Y-m-d").date("H:i:s").'" and b.entrades>0
            group by a.id
            order by titol and a.id and b.data and b.hora';
        $db = $this->getAdapter();
        $res = $db->query($sql);
        $data = $res->fetchAll();
        if (!$data)  throw new Exception('getAllByDataMasGrandeQueHoy');
        return $data;
    }
    
    public function getInfantilByDataMasGrandeQueHoy()
    {
        $sql = 'select a.id as id_espectacle,b.id as id_sessions,a.espai,a.titol,a.espai,
            descripcio_curt_es,descripcio_curt_ca,a.foto_gran,a.youtube 
            from ttn_espectacles  as a
            inner join ttn_sessions as b on a.id = b.idespectacle
            where  b.data>"'.date("Y-m-d").'" and b.entrades>0 and (a.infantil=1 or a.familiar=1) 
                group by a.id
                order by titol and a.id and b.data and b.hora';
        $db = $this->getAdapter();
        $res = $db->query($sql);
        $data = $res->fetchAll();
        if (!$data)  throw new Exception('getInfantilByDataMasGrandeQueHoy');
        return $data;
    }
    
    public function getById($id)
    {
        $sql = 'select * from ttn_espectacles where id='.$id;
        $db = $this->getAdapter();
        $res = $db->query($sql);
        $data = $res->fetch();
        if (!$data)  throw new Exception('getById');
        return $data;
    }
    
}

