<?php

class Application_Model_InterfaceArtefactoEticketSecure extends Application_Model_InterfaceEticketSecure
{
    private $_controller;
    private $_rest_ful_service;
    private $_cache;

    public function __construct(){
        parent::__construct();
        $this->_controller= "/artefacto/index/index.php";
        $this->_rest_ful_service = "ServiceArtefacto";
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
      
   public function loadCacheOperacionArtefacto($id,$nombre_operacion){
        return $this->_cache->load('operacionArtefactoByIdx'.$id.'x'.$nombre_operacion);
   }
    
   /**
    * Devuelve representacion_xml , iva y codigo_referencia de un artefacto
    * @param integer $id el identificador de ese producto en la BBDD
    * @return xml
    */  
    public function getArtefactoById($id){
        // Verificar si la cache existe: si no crearla
        if(!$result = $this->_cache->load('getArtefactoByIdx'.$id)) {
            if($this->_flag_web_service){
                $url ="&id=".$id; 
                $webservice=$this->getUrlWebService($this->_controller,"&method=getArtefactoById".$url);
                $data=$this->restFul($webservice);
                if($data->getArtefactoById->status == "failed") throw new Exception('InterfaceArtefactoException');
                $data=Eticketsecure_Utils_XML2Array::createArray($data,$this->_rest_ful_service,"getArtefactoById");
            }else{
                $Artefacto = new Application_Model_DbTable_Artefacto ();
                $data=$Artefacto->getArtefacto($id);
                if ($data==NULL)  throw new Exception('No existe Artefacto para Estos Parametros');
            }
            if($this->_flag_cache) $this->_cache->save($data, 'getArtefactoByIdx'.$id);
            return $data;
        }else{
            if($this->_flag_web_service){
                return simplexml_load_string($result);   
            }else{
                return $result;
            }
        }  
    }
        
    public function getIdArtefactoArrByColeccionXMLAndIdCategoriaArtefactoAndIdArtefactoColeccion($coleccion_xml, $id_categoria_artefacto, $id_artefacto_coleccion){
        if($this->_flag_web_service){
            //EMPTY
        }else{
            $Artefacto= new Application_Model_DbTable_Artefacto();
            $data=$Artefacto->getIdArtefactoArrByColeccionXMLAndIdCategoriaArtefactoAndIdArtefactoColeccion($coleccion_xml, $id_categoria_artefacto, $id_artefacto_coleccion);
            if ($data==NULL)  throw new Exception('No existe Artefactos para esa coleccion_xml, id_categoria_artefacto, id_artefacto_coleccion');
        }
        $data_temp_arr = array();
        foreach($data as $node){
            $data_temp_arr[]=$node["id_artefacto"];
        }
        $data=$data_temp_arr;
        return $data;    
        
        
    }
    
    /**
    * Devuelve un array con los id de artefactos que pertenecen a la categoriaArtefacto $id_categoria_artefacto
    * @param integer $id_categoria_artefacto
    * @param integer $num_pagina numero de pagina en la consulta
    * @param integer $elementos_pagina numero de elementos por pagina en la consulta
    * @return array[] 
    */ 
    public function getIdArtefactoArrByIdCategoriaArtefacto($id_categoria_artefacto,$num_pagina="all",$elementos_pagina="all"){  
        if($this->_flag_web_service){

        }else{
             $RelacionCategoriaArtefactoArtefacto = new Application_Model_DbTable_RelacionCategoriaArtefactoArtefacto ();
             $data=$RelacionCategoriaArtefactoArtefacto->getIdArtefactoArrByIdCategoriaArtefacto($id_categoria_artefacto,$num_pagina,$elementos_pagina);
             if ($data==NULL)  throw new Exception('No existe Artefactos para esa categoriaArtefacto');
        }
        $data_temp_arr = array();
        foreach($data as $node){
            $data_temp_arr[]=$node["id_artefacto"];
        }
        $data=$data_temp_arr;
        return $data;
    }  

    
    public function setArtefactoById($id,$representacion_xml){
        if ($this->_flag_web_service){
            $url ="&id=".$id."&representacion_xml=".urlencode($representacion_xml); 
            $webservice=$this->getUrlWebService($this->_controller,"&method=setArtefactoById".$url);
            $data=$this->restFul($webservice);
            if($data->setArtefactoById->status == "failed" && $data->setArtefactoById->error!='No se ha editado nada')
                throw new Exception('InterfaceArtefactoException');
            $data=Eticketsecure_Utils_XML2Array::createArray($data,$this->_rest_ful_service,"setArtefactoById");
        }else{
            $Artefacto = new Application_Model_DbTable_Artefacto ();
            $data=$Artefacto->setArtefacto($id, $representacion_xml);
        }
        $this->deleteCachesArtefacto($id);
        return $data;
    } 


    public function addArtefacto($id_categoria_artefacto,$representacion_xml){
        if ($this->_flag_web_service){
            $url ="&id_categoria_artefacto=".$id_categoria_artefacto."&representacion_xml=".urlencode($representacion_xml); 
            $webservice=$this->getUrlWebService($this->_controller,"&method=addArtefacto".$url);
            $data=$this->restFul($webservice);
            if($data->addArtefacto->status == "failed") throw new Exception('InterfaceArtefactoException');
            $data=Eticketsecure_Utils_XML2Array::createArray($data,$this->_rest_ful_service,"addArtefacto");
        }else{
            $Artefacto = new Application_Model_DbTable_Artefacto ();
            $RelacionCategoriaArtefacto = new Application_Model_DbTable_RelacionCategoriaArtefactoArtefacto();
            $last_insert_id=$Artefacto->addArtefacto($this->_id_empresa, $representacion_xml);
            $RelacionCategoriaArtefacto->addRelacionCategoriaArtefacto($last_insert_id, $id_categoria_artefacto);
            $data = $last_insert_id;
        }
        return $data;
    }    
    
    public function deleteArtefactoById($id){
        if ($this->_flag_web_service){
            $url ="&id=".$id;
            $webservice=$this->getUrlWebService($this->_controller,"&method=deleteArtefactoById".$url);
            $data=$this->restFul($webservice);
            if($data->deleteArtefactoById->status == "failed") throw new Exception('InterfaceArtefactoException');
            $data=Eticketsecure_Utils_XML2Array::createArray($data,$this->_rest_ful_service,"deleteArtefactoById");
        }else{
            $Artefacto = new Application_Model_DbTable_Artefacto ();
            $data=$Artefacto->deleteArtefacto($id);
        }
        $this->deleteCachesArtefacto($id);
        return $data;
    }   
      
    public function salvarCacheOperacionesModelo($id,$data,$nombre_operacion){
        if($id==NULL) throw new Exception('InterfaceArtefactoException');
        if($this->_flag_cache) $this->_cache->save($data, 'operacionArtefactoByIdx'.$id.'x'.$nombre_operacion);
    }

    /**
    * Borra la cache de todo el Artefacto identificado con $id
    * @param integer $id el identificador de ese producto en la BBDD
    */  
    public function deleteCachesArtefacto($id){
        if($this->_cache->load('getArtefactoByIdx'.$id)!== FALSE) $this->_cache->remove('getArtefactoByIdx'.$id);    
        $this->removeCacheOperacionesModelo($id);            
    }
    
    private function removeCacheOperacionesModelo($id){
        $handler = opendir('./tmp/');
        $results = array();
        while ($file = readdir($handler)){
            if ($file != "." && $file != "..") {
                $results[] = $file;
            } 
        }
        closedir($handler);   
		
        foreach($results as $file){
            if (strpos($file, 'operacionArtefactoByIdx'.$id)!==FALSE){
                unlink('./tmp/'.$file);
            }
        }
    }
    
}

