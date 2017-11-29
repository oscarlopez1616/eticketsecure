<?php

class Application_Model_InterfaceCategoriaEticketSecure extends Application_Model_InterfaceEticketSecure
{
    private $_controller;
    private $_rest_ful_service;
    
    public function __construct(){
        parent::__construct();
        $this->_controller= "/categoria/index/index.php";
        $this->_rest_ful_service = "ServiceCategoria";
        
        //cache
        $frontendOptions = array(
           'lifetime' => NULL, // valida siempre hasta que se reconstruya
           'automatic_serialization' => true
        );

        $backendOptions = array(
            'cache_dir' => './tmp/' // Carpeta donde alojar los archivos de caché
        );

        $this->_cache = Zend_Cache::factory('Core',
                                     'File',
                                     $frontendOptions,
                                     $backendOptions);
    }
    
    public function addCategoria($representacion_xml,$publicado,$id_parent){
        if($this->_flag_web_service){
            $url ="&representacion_xml=".urlencode($representacion_xml)."&publicado=".$publicado.= "&id_parent=".$id_parent; 
            $webservice=$this->getUrlWebService($this->_controller,"&method=addCategoria".$url);
            $data=$this->restFul($webservice);
            if($data->addCategoria->status == "failed") throw new Exception('InterfaceCategoriaException');
            $data=Eticketsecure_Utils_XML2Array::createArray($data,$this->_rest_ful_service,"addCategoria");
        }else{
            $categorias = new Application_Model_DbTable_Categoria ();
            $data=$categorias->addCategoria($this->_id_empresa,$representacion_xml,$publicado,$id_parent);
        }
        return $data;
    }
    
    public function deleteCategoria($id){
        if($this->_flag_web_service){
            $url ="&id=".$id; 
            $webservice=$this->getUrlWebService($this->_controller,"&method=deleteCategoria".$url);
            $data=$this->restFul($webservice);
            if($data->deleteCategoria->status == "failed") throw new Exception('InterfaceCategoriaException');
            $data=Eticketsecure_Utils_XML2Array::createArray($data,$this->_rest_ful_service,"deleteCategoria");
        }else{
            $categorias = new Application_Model_DbTable_Categoria();
            $data=$categorias->deleteCategoria($id);
            if ($data==0) throw new Exception('No se ha borrado nada');   
        }
        return $data;
    }
    
    public function getCategoriaById($id){
        // Verificar si la cache existe: si no crearla
        if(!$result = $this->_cache->load('getCategoriaByIdx'.$id)) {
            if($this->_flag_web_service){
                $webservice=$this->getUrlWebService($this->_controller,"&method=getCategoriaById&id=".$id);
                $data=$this->restFul($webservice);
                if($data->getCategoriaById->status == "failed") throw new Exception('InterfaceCategoriaException');
                $data=Eticketsecure_Utils_XML2Array::createArray($data,$this->_rest_ful_service,"getCategoriaById");
            }else{
                $categorias = new Application_Model_DbTable_Categoria ();
                $data=$categorias->getCategoriaById($id);
                if ($data==NULL)  throw new Exception('No existen categorias para este id');
            }
            if($this->_flag_cache) $this->_cache->save($data, 'getCategoriaByIdx'.$id);
            return $data;
        }else{
            if($this->_flag_web_service){
                return simplexml_load_string($result);   
            }else{
                return $result;
            }
        }
    }  
    
    /**
     * Funcion que funcionar para buscar sobre cualquier campo de representacion_xml
     * @param int $id
     * @param string $name_campo_orden
     * @param string $operator
     * @param mixed(int,string) $valor_a_operar
     * @param string $valor_a_operar por defecto es "int","string"
     * @param string $order_type por defecto es "ASC" o "DESC"
     * @param string $limit_start 0 por defecto es el valor donde empiezan los resultados
     * @param mixed(int,string) $limit_stop "MAX" por defecto MAX que son todos si no el un entero donde acabarn los resultados
     * @return string
     */
    public function getIdProductoArrByIdCategoriaByFieldValue($id_categoria_artefacto,$name_campo_orden, $operator, $valor_a_operar, 
            $type_valor_a_operar="int",$order_type="ASC",$limit_start=0,$limit_stop="MAX"){  
        
        if($this->_flag_web_service){
            $url ="&id=".$id_categoria_artefacto."&name_campo_orden=".urlencode($name_campo_orden)."&operator=".urlencode($operator);
            $url.="&valor_a_operar=".urlencode($valor_a_operar)."&type_valor_a_operar=".urlencode($type_valor_a_operar)."&order_type=".urlencode($order_type);
            $url.="&limit_start=".urlencode($limit_start)."&limit_stop=".urlencode($limit_stop);

            $webservice=$this->getUrlWebService($this->_controller,"&method=getIdProductoArrByIdCategoriaByFieldValue".$url);
            $data=$this->restFul($webservice);
            if($data->getIdProductoArrByIdCategoriaByFieldValue->status == "failed") throw new Exception('InterfaceProductoException');
            $data=Eticketsecure_Utils_XML2Array::createArray($data,$this->_rest_ful_service,"getIdProductoArrByIdCategoriaByFieldValue");
        }else{
            $categorias = new Application_Model_DbTable_Categoria ();
            $data=$categorias->getIdProductoArrByIdCategoriaByFieldValue($id_categoria_artefacto, $name_campo_orden, $operator, $valor_a_operar, $type_valor_a_operar, $order_type,$limit_start,$limit_stop);
            if ($data==NULL)  throw new Exception('No existen Productos para este id_categoria');
        }
        $data_temp_arr = array();
        foreach($data as $node){
            $data_temp_arr[]=$node["id"];
        }
        $data=$data_temp_arr;
        return $data;
    }      
    
    public function setCategoriaById($id,$representacion_xml,$publicado){
        if($this->_flag_web_service){
            $url ="&id=".$id."&representacion_xml=".urlencode($representacion_xml)."&publicado=".$publicado; 
            $webservice=$this->getUrlWebService($this->_controller,"&method=setCategoriaById".$url);
            $data=$this->restFul($webservice);
            if($data->setCategoriaById->status == "failed" && $data->setCategoriaById->error!='No se ha editado nada')
                throw new Exception('InterfaceCategoriaException');
            $data=Eticketsecure_Utils_XML2Array::createArray($data,$this->_rest_ful_service,"setCategoriaById");
        }else{
            $categorias = new Application_Model_DbTable_Categoria();
            $data=$categorias->setCategoriaById($id, $this->_id_empresa, $representacion_xml,$publicado);
            if ($data==0) throw new Exception('No se ha editado nada'); 
        }
        return $data;
    }      
    
    public function getIdCategoriaArrByAltura($altura){  
        if($this->_flag_web_service){
            $webservice=$this->getUrlWebService($this->_controller,"&method=getIdCategoriaArrByAltura&altura=".$altura);  
            $data=$this->restFul($webservice);
            if($data->getIdCategoriaArrByAltura->status == "failed") throw new Exception('No se puede crear de categorias para esta altura');
            $data=Eticketsecure_Utils_XML2Array::createArray($data,$this->_rest_ful_service,"getIdCategoriaArrByAltura");
        }else{
            $categorias = new Application_Model_DbTable_Categoria ();
            $data=$categorias->getIdCategoriaArrByAltura($altura);
            if ($data==NULL)  throw new Exception('No existen Categorias para esta Altura');
        }
        $data_temp_arr = array();
        foreach($data as $node){
            $data_temp_arr[]=$node["id"];
        }
        $data=$data_temp_arr;
        return $data;
    }
    
    public function getIdCategoriaArrByAlturaByIdParent($altura,$id_parent){
       if($this->_flag_web_service){
            $webservice=$this->getUrlWebService($this->_controller,"&method=getIdCategoriaArrByAlturaByIdParent&altura=".$altura."&id_parent=".$id_parent);
            $data=$this->restFul($webservice);
            if($data->getIdCategoriaArrByAlturaByIdParent->status == "failed") throw new Exception('No se puede crear de categorias para esta altura y este id_parent'); 
            $data=Eticketsecure_Utils_XML2Array::createArray($data,$this->_rest_ful_service,"getIdCategoriaArrByAlturaByIdParent");
        }else{
            $categorias = new Application_Model_DbTable_Categoria ();
            $data=$categorias->getIdCategoriaArrByAlturaByIdParent($altura,$id_parent);
            if ($data==NULL)  throw new Exception('No existen categorias para esta Altura');
       }
        $data_temp_arr = array();
        foreach($data as $node){
            $data_temp_arr[]=$node["id"];
        }
        $data=$data_temp_arr;
        return $data;
    }
    
    public function getIdSubCategoriaArr($id){
       if($this->_flag_web_service){
            $webservice=$this->getUrlWebService($this->_controller,"&method=getIdSubCategoriaArr&id=".$id);  
            $data=$this->restFul($webservice);
            if($data->getIdSubCategoriaArr->status == "failed") throw new Exception('No se puede crear de Subcategorias para esta id'); 
            $data=Eticketsecure_Utils_XML2Array::createArray($data,$this->_rest_ful_service,"getIdSubCategoriaArr");
       }else{
            $categorias = new Application_Model_DbTable_Categoria ();
            $data=$categorias->getIdSubCategoriaArr($id); 
            if ($data==NULL)  throw new Exception('No existen categorias para esta Altura');
       }
        $data_temp_arr = array();
        foreach($data as $node){
            $data_temp_arr[]=$node["id"];
        }
        $data=$data_temp_arr;
        return $data;
    }
    
    /**
    * Borra la cache de todo el Categoria identificado con $id
    * @param integer $id el identificador de ese producto en la BBDD
    */  
    public function deleteCachesCategoria($id){
        if($result = $this->_cache->load('getCategoriaByIdx'.$id))$this->_cache->remove('getCategoriaByIdx'.$id);  
    }
}

