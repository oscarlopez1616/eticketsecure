<?php

class Application_Model_DbTable_Artefacto extends Zend_Db_Table_Abstract
{

    protected $_name = 'artefacto';
    
    public function getArtefacto($id)
    {
        $sql = "select a.id,a.id_empresa,a.representacion_xml,b.id_categoria_artefacto from artefacto as a
                left join relacion_categoria_artefacto_artefacto as b
                on a.id=b.id_artefacto
                where a.id=".$id;
        $db = $this->getAdapter();
        $res = $db->query($sql);
        $artefacto = $res->fetch();
        return $artefacto;
    }
    
    public function addArtefacto($id_empresa,$representacion_xml)
    {
        $data = array(
                'id_empresa' => $id_empresa,
                'representacion_xml'=>$representacion_xml                     
                );
        $c=$this->insert($data);
        return $c;
    } 
    
    public function deleteArtefacto($id)
    {
        $sql = "delete from artefacto where id =".$id;
        $db = $this->getAdapter();
      
        $res = $db->query($sql);
        return $res->rowCount();
    } 

    public function setArtefacto($id,$representacion_xml){
        $data = array(
                'representacion_xml' => $representacion_xml 
                );
        $affected_rows= $this->update($data, 'id = ' . $id);
        return $affected_rows;   
    }
    
    public function getIdArtefactoArrByColeccionXMLAndIdCategoriaArtefactoAndIdArtefactoColeccion($coleccion_xml, $id_categoria_artefacto, $id_artefacto_coleccion){
        $sql = "select id_artefacto
                from (
                select ExtractValue (a.representacion_xml, '".$coleccion_xml."') as id_sala, a.id as id_artefacto
                from artefacto as a
                left join relacion_categoria_artefacto_artefacto  as b
                on a.id=b.id_artefacto 
                where b.id_categoria_artefacto = ".$id_categoria_artefacto.") as tabla
                where 
                id_sala like '% ".$id_artefacto_coleccion."' 
                or id_sala like '".$id_artefacto_coleccion."' 
                or id_sala like '".$id_artefacto_coleccion." %' 
                or id_sala like '% ".$id_artefacto_coleccion." %'";
        $db = $this->getAdapter();
        $res = $db->query($sql);
        $id_artefacto_arr = $res->fetchAll();
        return $id_artefacto_arr;
    }

}

