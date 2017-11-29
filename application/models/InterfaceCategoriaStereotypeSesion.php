<?php

class Application_Model_InterfaceCategoriaStereotypeSesion  extends Application_Model_InterfaceCategoriaEticketSecure
{
    private $_id_categoria;

    public function __construct(){
        parent::__construct();
        $this->_id_categoria = 1;//1 es el id_categoria de Sesion
    }
    
    public function getIdProductoArrByDateInterval($DateTime,$page,$Order="ASC"){

        if($this->_flag_web_service){

        }else{
            $RelacionCategoriaProducto = new Application_Model_DbTable_CategoriaStereotypeSesion ();
            $data=$RelacionCategoriaProducto->getIdProductoArrByIdCategoriaAndDateTimeInterval($this->_id_categoria, $DateTime,$page,$Order="ASC");
            if ($data==NULL)  throw new Exception('1-No existen id_productos para este estos parametros');
        }
        
        return $data;
    }  
    
    public function getIdProductoArrFromNow($Order="ASC"){

        if($this->_flag_web_service){

        }else{
            $RelacionCategoriaProducto = new Application_Model_DbTable_CategoriaStereotypeSesion ();
            $data=$RelacionCategoriaProducto->getIdProductoArrFromNow($this->_id_categoria,$Order="ASC");
            if ($data==NULL)  throw new Exception('2-No existen id_productos para este estos parametros');
        }
        return $data;
    }  
    
    public function getIdProductoArrFromDateTime($DateTime,$Order="ASC"){

        if($this->_flag_web_service){

        }else{
            $RelacionCategoriaProducto = new Application_Model_DbTable_CategoriaStereotypeSesion ();
            $data=$RelacionCategoriaProducto->getIdProductoArrFromDateTime($this->_id_categoria,$DateTime,$Order="ASC");
            if ($data==NULL)  throw new Exception('3-No existen id_productos para este estos parametros');
        }
        return $data;
    }  

}

