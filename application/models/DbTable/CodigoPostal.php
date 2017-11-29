<?php

class Application_Model_DbTable_CodigoPostal extends Zend_Db_Table_Abstract
{

    protected $_name = 'codigo_postal';
    
    public function getZona1NameByCountryCodeAndCodigoPostal($country_code,$codigo_postal)
    {
        $sql = "select zona1_name from codigo_postal where country_code='".$country_code."' and codigo_postal='".$codigo_postal."'";
        $db = $this->getAdapter();
        $res = $db->query($sql);
        if ($res->rowCount()==0) throw new Exception('No existen info para este codigo_postal y este country_code'); 
        $geo_arr= $res->fetchAll();
        return $geo_arr;
    }  
    
    public function getZona2NameByCountryCodeAndCodigoPostal($country_code,$codigo_postal)
    {
        $sql = "select zona2_name from codigo_postal where country_code='".$country_code."' and codigo_postal='".$codigo_postal."'";
        $db = $this->getAdapter();
        $res = $db->query($sql);
        if ($res->rowCount()==0) throw new Exception('No existen info para este codigo_postal y este country_code'); 
        $geo_arr= $res->fetchAll();
        return $geo_arr;
    }  
    
}

