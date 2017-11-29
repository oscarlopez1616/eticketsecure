<?php

class Application_Model_InterfaceProductoEticketSecure extends Application_Model_InterfaceEticketSecure
{
    private $_controller;
    private $_rest_ful_service;
    private $_cache;
    public function __construct(){
        parent::__construct();
        $this->_controller= "/producto/index/index.php";
        $this->_rest_ful_service = "ServiceProducto";
    }
      
   /**
    * Devuelve representacion_xml , iva y codigo_referencia de un producto
    * @param integer $id el identificador de ese producto en la BBDD
    * @return xml
    */  
    public function getProductoById($id){
        if($this->_flag_web_service){
            $url ="&id=".$id; 
            $webservice=$this->getUrlWebService($this->_controller,"&method=getProductoById".$url);
            $data=$this->restFul($webservice);
            if($data->getProductoById->status == "failed") throw new Exception('InterfaceProductoException');
            $data=Eticketsecure_Utils_XML2Array::createArray($data,$this->_rest_ful_service,"getProductoById");
        }else{
            $Producto = new Application_Model_DbTable_Producto ();
            $data=$Producto->getProductoById($id);
            if ($data==NULL)  throw new Exception('No existe productos para esa id');
        }
        return $data;
    }
    
    
   /**
    * Devuelve un array con los id de productos que pertenecen a la categoria $id_categoria
    * @param integer $id_categoria
    * @param integer $num_pagina numero de pagina en la consulta
    * @param integer $elementos_pagina numero de elementos por pagina en la consulta
    * @return array[] 
    */   
    public function getIdProductoArrByIdCategoria($id_categoria,$num_pagina="all",$elementos_pagina="all"){  
        if($this->_flag_web_service){

        }else{
            $relacion_categoria_productos = new Application_Model_DbTable_RelacionCategoriaProducto ();
            $data=$relacion_categoria_productos->getIdProductoArrByIdCategoria($id_categoria,$num_pagina="all",$elementos_pagina="all");
            if ($data==NULL)  throw new Exception('No existe productos para esa categoria');
        }
        $data_temp_arr = array();
        foreach($data as $node){
            $data_temp_arr[]=$node["id_producto"];
        }
        $data=$data_temp_arr;
        return $data;
    }
    
   /**
    * Devuelve un array con los id de productos que pertenecen a la categoria $id_categoria
    * @param array(0=>id_categoria) $id_categoria_arr
    * @param integer $num_pagina numero de pagina en la consulta
    * @param integer $elementos_pagina numero de elementos por pagina en la consulta
    * @return array[] 
    */   
    public function getIdProductoArrByIdCategoriaArr($id_categoria_arr,$num_pagina="all",$elementos_pagina="all"){  
        if($this->_flag_web_service){

        }else{
            $relacion_categoria_productos = new Application_Model_DbTable_RelacionCategoriaProducto ();
            $data=$relacion_categoria_productos->getIdProductoArrByIdCategoriaArr($id_categoria_arr,$num_pagina,$elementos_pagina);
            if ($data==NULL)  throw new Exception('No existe productos para esa categoria');
        }
        $data_temp_arr = array();
        foreach($data as $node){
            $data_temp_arr[]=$node["id_producto"];
        }
        $data=$data_temp_arr;
        return $data;
    }
    
    public function setProductoById($id,$representacion_xml,$iva){
        if ($this->_flag_web_service){
            $url ="&id=".$id."&representacion_xml=".urlencode($representacion_xml)."&iva=".$iva; 
            $webservice=$this->getUrlWebService($this->_controller,"&method=setProductoById".$url);
            $data=$this->restFul($webservice);
            if($data->setProductoById->status == "failed" && $data->setProductoById->error!='No se ha editado nada')
                throw new Exception('InterfaceProductoException');
            $data=Eticketsecure_Utils_XML2Array::createArray($data,$this->_rest_ful_service,"setProductoById");
        }else{
            $productos = new Application_Model_DbTable_Producto ();
            $data=$productos->setProducto($id, $representacion_xml, $iva);
            if ($data==0) throw new Exception('No se ha editado nada');
        }
        return $data;
    } 
    
    public function addProducto($id_categoria,$representacion_xml,$iva){
        if ($this->_flag_web_service){
            $url ="&id_categoria=".$id_categoria."&representacion_xml=".urlencode($representacion_xml)."&iva=".$iva; 
            $webservice=$this->getUrlWebService($this->_controller,"&method=addProducto".$url);
            $data=$this->restFul($webservice);
            if($data->addProducto->status == "failed") throw new Exception('InterfaceProductoException');
            $data=Eticketsecure_Utils_XML2Array::createArray($data,$this->_rest_ful_service,"addProducto");
        }else{
            $productos = new Application_Model_DbTable_Producto ();
            $RelacionCategoria = new Application_Model_DbTable_RelacionCategoriaProducto();
            $last_insert_id=$productos->addProducto($this->_id_empresa, $representacion_xml,$iva);
            $RelacionCategoria->addRelacionCategoria($last_insert_id, $id_categoria);
            $data=$last_insert_id;
        }
        return $data;
    }    
    
    public function deleteProductoById($id){
        if ($this->_flag_web_service){
            $url ="&id=".$id;
            $webservice=$this->getUrlWebService($this->_controller,"&method=deleteProductoById".$url);
            $data=$this->restFul($webservice);
            if($data->deleteProductoById->status == "failed") throw new Exception('InterfaceProductoException');
            $data=Eticketsecure_Utils_XML2Array::createArray($data,$this->_rest_ful_service,"deleteProductoById");
            $this->deleteCachesArtefacto($id);
        }else{
            $productos = new Application_Model_DbTable_Producto ();
            $data=$productos->deleteProducto($id);
            if ($data==0) throw new Exception('No se ha borrado nada');
        }
        return $data;      
    }   
    
}

