<?php

class Application_Model_DbTable_Pais extends Zend_Db_Table_Abstract
{

    protected $_name = 'pais';
    
    public function getCountryNameArrByIsoLangCode($country_code)
    {
        $sql = "select country_name, country_code  from pais where iso_lang='".$country_code."'";
        $db = $this->getAdapter();
        $res = $db->query($sql);
        if ($res->rowCount()==0) throw new Exception('No existen info para este codigo_postal y este country_code'); 
        $geo_arr= $res->fetchAll();
        return $geo_arr;
    }  
    
}

