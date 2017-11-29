<?php

class Application_Model_DbTable_RelacionCategoriaArtefactoArtefacto extends Zend_Db_Table_Abstract
{

    protected $_name = 'relacion_categoria_artefacto_artefacto';
    
    public function getIdArtefactoArrByIdCategoriaArtefacto($id_categoria_artefacto,$num_pagina="all",$elementos_pagina="all")
    {
        $sql = "select id_artefacto from relacion_categoria_artefacto_artefacto where id_categoria_artefacto=".$id_categoria_artefacto;
        if($num_pagina!= "all" && $elementos_pagina!="all"){
          $offset = $num_pagina*$elementos_pagina;  
          $sql.=" limit ".$offset.",".$elementos_pagina." ";  
        }
        $db = $this->getAdapter();
        $res = $db->query($sql);
        $datos_arr = $res->fetchAll();
        return $datos_arr;
    }
    
    public function addRelacionCategoriaArtefacto($id_artefacto,$id_categoria_artefacto)
    {
        $data = array(
                'id_artefacto' => $id_artefacto,
                'id_categoria_artefacto'=>$id_categoria_artefacto                     
                );
        $c=$this->insert($data);
        return $c;
    } 


}

