<?php

class Application_Model_InterfaceCategoriaArtefactoEticketSecure extends Application_Model_InterfaceEticketSecure
{
    private $_controller;
    private $_rest_ful_service;
    
    public function __construct(){
        parent::__construct();
        $this->_controller= "/categoria-artefacto/index/index.php";
        $this->_rest_ful_service = "ServiceCategoriaArtefacto";
        
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
    
    public function addCategoriaArtefacto($representacion_xml,$publicado,$id_parent){
        if($this->_flag_web_service){
            $url ="&representacion_xml=".urlencode($representacion_xml)."&publicado=".$publicado.= "&id_parent=".$id_parent; 
            $webservice=$this->getUrlWebService($this->_controller,"&method=addCategoriaArtefacto".$url);
            $data=$this->restFul($webservice);
            if($data->addCategoriaArtefacto->status == "failed") throw new Exception('InterfaceCategoriaArtefactoException');
            $data=Eticketsecure_Utils_XML2Array::createArray($data,$this->_rest_ful_service,"addCategoriaArtefacto");
        }else{
            $categoriasArtefacto = new Application_Model_DbTable_CategoriaArtefacto ();
            $data=$categoriasArtefacto->addCategoriaArtefacto($this->_id_empresa,$representacion_xml,$publicado,$id_parent);
        }
        return $data;
    }
    
    public function deleteCategoriaArtefacto($id){
        if($this->_flag_web_service){
            $url ="&id=".$id; 
            $webservice=$this->getUrlWebService($this->_controller,"&method=deleteCategoriaArtefacto".$url);
            $data=$this->restFul($webservice);
            if($data->deleteCategoriaArtefacto->status == "failed") throw new Exception('InterfaceCategoriaArtefactoException');
            $data=Eticketsecure_Utils_XML2Array::createArray($data,$this->_rest_ful_service,"deleteCategoriaArtefacto");
        }else{
            $categoriasArtefacto = new Application_Model_DbTable_CategoriaArtefacto();
            $data=$categoriasArtefacto->deleteCategoriaArtefacto($id);
            if ($data==0) throw new Exception('No se ha borrado nada');   
        }
        return $data;
    }
    
    public function getCategoriaArtefactoById($id){
        // Verificar si la cache existe: si no crearla
        if(!$result = $this->_cache->load('getCategoriaArtefactoByIdx'.$id)) {
            if($this->_flag_web_service){
                $webservice=$this->getUrlWebService($this->_controller,"&method=getCategoriaArtefactoById&id=".$id);
                $data=$this->restFul($webservice);
                if($data->getCategoriaArtefactoById->status == "failed") throw new Exception('InterfaceCategoriaArtefactoException');
                $data=Eticketsecure_Utils_XML2Array::createArray($data,$this->_rest_ful_service,"getCategoriaArtefactoById");
            }else{
                $categoriasArtefacto = new Application_Model_DbTable_CategoriaArtefacto ();
                $data=$categoriasArtefacto->getCategoriaArtefactoById($id);
                if ($data==NULL)  throw new Exception('No existen CategoriasArtefacto para este id');
            }
            if($this->_flag_cache) $this->_cache->save($data, 'getCategoriaArtefactoByIdx'.$id);
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
    public function getIdArtefactoArrByIdCategoriaByFieldValue($id_categoria_artefacto,$name_campo_orden, $operator, $valor_a_operar, 
            $type_valor_a_operar="int",$order_type="ASC",$limit_start=0,$limit_stop="MAX"){  
        
        if($this->_flag_web_service){
            $url ="&id=".$id_categoria_artefacto."&name_campo_orden=".urlencode($name_campo_orden)."&operator=".urlencode($operator);
            $url.="&valor_a_operar=".urlencode($valor_a_operar)."&type_valor_a_operar=".urlencode($type_valor_a_operar)."&order_type=".urlencode($order_type);
            $url.="&limit_start=".urlencode($limit_start)."&limit_stop=".urlencode($limit_stop);

            $webservice=$this->getUrlWebService($this->_controller,"&method=getIdArtefactoArrByIdCategoriaByFieldValue".$url);
            $data=$this->restFul($webservice);
            if($data->getIdArtefactoArrByIdCategoriaByFieldValue->status == "failed") throw new Exception('InterfaceArtefactoException');
            $data=Eticketsecure_Utils_XML2Array::createArray($data,$this->_rest_ful_service,"getIdArtefactoArrByIdCategoriaByFieldValue");
        }else{
            $categoriasArtefacto = new Application_Model_DbTable_CategoriaArtefacto ();
            $data=$categoriasArtefacto->getIdArtefactoArrByIdCategoriaByFieldValue($id_categoria_artefacto, $name_campo_orden, $operator, $valor_a_operar, $type_valor_a_operar, $order_type,$limit_start,$limit_stop);
            if ($data==NULL)  throw new Exception('No existen Artefactos para este id_categoria_atefacto');
        }
        $data_temp_arr = array();
        foreach($data as $node){
            $data_temp_arr[]=$node["id"];
        }
        $data=$data_temp_arr;
        return $data;
    }  
    
    public function setCategoriaArtefactoById($id,$representacion_xml,$publicado){
        if($this->_flag_web_service){
            $url ="&id=".$id."&representacion_xml=".urlencode($representacion_xml)."&publicado=".$publicado; 
            $webservice=$this->getUrlWebService($this->_controller,"&method=setCategoriaArtefactoById".$url);
            $data=$this->restFul($webservice);
            if($data->setCategoriaArtefactoById->status == "failed" && $data->setCategoriaArtefactoById->error!='No se ha editado nada')
                throw new Exception('InterfaceCategoriaArtefactoException');
            $data=Eticketsecure_Utils_XML2Array::createArray($data,$this->_rest_ful_service,"setCategoriaArtefactoById");
        }else{
            $categoriasArtefacto = new Application_Model_DbTable_CategoriaArtefactoArtefacto();
            $data=$categoriasArtefacto->setCategoriaById($id, $this->_id_empresa, $representacion_xml,$publicado);
            if ($data==0) throw new Exception('No se ha editado nada'); 
        }
        return $data;
    }      
    
    public function getIdCategoriaArrArtefactoByAltura($altura){  
        if($this->_flag_web_service){
            $webservice=$this->getUrlWebService($this->_controller,"&method=getIdCategoriaArrArtefactoByAltura&altura=".$altura);  
            $data=$this->restFul($webservice);
            if($data->getIdCategoriaArrArtefactoByAltura->status == "failed") throw new Exception('No se puede crear de categoriasArtefacto para esta altura');
            $data=Eticketsecure_Utils_XML2Array::createArray($data,$this->_rest_ful_service,"getIdCategoriaArrArtefactoByAltura");
        }else{
            $categoriasArtefacto = new Application_Model_DbTable_CategoriaArtefacto ();
            $data=$categoriasArtefacto->getIdCategoriaArrArtefactoByAltura($altura);
            if ($data==NULL)  throw new Exception('No existen CategoriasArtefacto para esta Altura');
        }
        $data_temp_arr = array();
        foreach($data as $node){
            $data_temp_arr[]=$node["id"];
        }
        $data=$data_temp_arr;
        return $data;
    }
    
    public function getIdCategoriaArrArtefactoByAlturaByIdParent($altura,$id_parent){
       if($this->_flag_web_service){
            $webservice=$this->getUrlWebService($this->_controller,"&method=getIdCategoriaArrArtefactoByAlturaByIdParent&altura=".$altura."&id_parent=".$id_parent);
            $data=$this->restFul($webservice);
            if($data->getIdCategoriaArrArtefactoByAlturaByIdParent->status == "failed") throw new Exception('No se puede crear de categoriasArtefacto para esta altura y este id_parent'); 
            $data=Eticketsecure_Utils_XML2Array::createArray($data,$this->_rest_ful_service,"getIdCategoriaArrArtefactoByAlturaByIdParent");
        }else{
            $categoriasArtefacto = new Application_Model_DbTable_CategoriaArtefacto ();
            $data=$categoriasArtefacto->getIdCategoriaArrArtefactoByAlturaByIdParent($altura,$id_parent);
            if ($data==NULL)  throw new Exception('No existen CategoriasArtefacto para esta Altura');
       }
        $data_temp_arr = array();
        foreach($data as $node){
            $data_temp_arr[]=$node["id"];
        }
        $data=$data_temp_arr;
        return $data;
    }
    
    public function getIdSubCategoriaArrArtefacto($id){
       if($this->_flag_web_service){
            $webservice=$this->getUrlWebService($this->_controller,"&method=getIdSubCategoriaArrArtefacto&id=".$id);  
            $data=$this->restFul($webservice);
            if($data->getIdSubCategoriaArrArtefacto->status == "failed") throw new Exception('No se puede crear de SubcategoriasArtefacto para esta id'); 
            $data=Eticketsecure_Utils_XML2Array::createArray($data,$this->_rest_ful_service,"getIdSubCategoriaArrArtefacto");
       }else{
            $categoriasArtefacto = new Application_Model_DbTable_CategoriaArtefacto ();
            $data=$categoriasArtefacto->getIdSubCategoriaArrArtefacto($id); 
            if ($data==NULL)  throw new Exception('No existen CategoriasArtefacto para esta Altura');
       }
        $data_temp_arr = array();
        foreach($data as $node){
            $data_temp_arr[]=$node["id"];
        }
        $data=$data_temp_arr;
        return $data;
    }
    
    /**
    * Borra la cache de todo el CategoriaArtefacto identificado con $id
    * @param integer $id el identificador de ese producto en la BBDD
    */  
    public function deleteCachesCategoriaArtefacto($id){
        if($result = $this->_cache->load('getCategoriaArtefactoByIdx'.$id))$this->_cache->remove('getCategoriaArtefactoByIdx'.$id);  
    }
}

