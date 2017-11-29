<?php

class Application_Model_Compra
{
    private $_id;     
    private $_id_usuario;    
    private $_fecha;    
    private $_id_forma_pago;    
    private $_pagada;    
    private $_enviada;    
    private $_procesada;
    private $_DireccionEnvio;//direccion envio
    private $_gastos_envio;
    private $_localizador;
    private $_CompraDbTable;

    public function __construct($id=NULL,$id_usuario=NULL,$fecha=NULL,$id_forma_pago=NULL,$pagada=NULL,$enviada=NULL,
            $procesada=NULL,$DireccionEnvio=NULL,$gastos_envio=NULL,$localizador=NULL){
        
        $this->_CompraDbTable = new Application_Model_DbTable_Compra();      
        $this->_id=$id;       
        $this->_id_usuario=$id_usuario;    
        $this->_fecha=$fecha;    
        $this->_id_forma_pago=$id_forma_pago;    
        $this->_pagada=$pagada;    
        $this->_enviada=$enviada;    
        $this->_procesada=$procesada;
        if($DireccionEnvio == NULL){
            $this->_DireccionEnvio= new Application_Model_UsuarioDireccion();
        }else{
            $this->_DireccionEnvio= $DireccionEnvio;
        }
        $this->_gastos_envio=$gastos_envio;
        $this->setLocalizador();//se genera un unique localizador para esta compra
    }
    
    public function load($id){
        $this->_id=$id;
        $data=$this->_CompraDbTable->getCompraById($id);
        $this->_id_usuario=$data["id_usuario"]; 
        $this->_fecha=$data["fecha"];    
        $this->_id_forma_pago=$data["id_forma_pago"];     
        $this->_pagada=$data["pagada"];    
        $this->_enviada=$data["enviada"];    
        $this->_procesada=$data["procesada"];  
        $this->_gastos_envio=$data["gastos_envio"];
        $this->_localizador=$data["localizador"];
        $data = $data["direccion_xml"];
        $direccion_xml = simplexml_load_string($data);
        $this->_DireccionEnvio = new Application_Model_UsuarioDireccion((string)$direccion_xml->direccion->pais,(string)$direccion_xml->direccion->provincia,(string)$direccion_xml->direccion->poblacion,(string)$direccion_xml->direccion->cp,(string)$direccion_xml->direccion->direccion);
    }
    
    public function loadByLocalizador($localizador){
        $data=$this->_CompraDbTable->getCompraByLocalizador($localizador);
        $id=$data["id"];
        $this->load($id);
    }
      
    public function getId(){
        return $this->_id;
    } 
    
    public function getIdUsuario(){
        return $this->_id_usuario;
    } 
    
    public function setIdUsuario($id_usuario){
        $this->_id_usuario=$id_usuario;
    } 
    
    public function getFecha(){
        return $this->_fecha;
    } 
    
    public function setFecha($fecha){
        $this->_fecha=$fecha;
    } 
    
    public function getIdFormaPago(){
        return $this->_id_forma_pago;
    }
    
    public function setIdFormaPago($id_forma_pago){
        $this->_id_forma_pago=$id_forma_pago;
    } 
    
    public function getPagada(){
        return $this->_pagada;
    } 
    
    public function setPagada($pagada){
        $this->_pagada=$pagada;
    } 
    
    public function getEnviada(){
        return $this->_enviada;
    } 
    
    public function setEnviada($enviada){
        $this->_enviada=$enviada;
    } 
    
    public function getProcesada(){
        return $this->_procesada;
    } 
    
    public function setProcesada($procesada){
        $this->_procesada=$procesada;
    } 
    
    public function getDireccionEnvio(){
        return $this->_DireccionEnvio;
    }
    
    public function setDireccionEnvio($DireccionEnvio){
        $this->_DireccionEnvio=$DireccionEnvio;
    } 
    
    public function getGastosEnvio(){
        return $this->_gastos_envio;
    } 
    
    public function setGastosEnvio($gastos_envio){
        $this->_gastos_envio=$gastos_envio;
    }
    
    public function getLocalizador() {
        return $this->_localizador;
    }

    private function setLocalizador() {
        $this->_localizador = uniqid();
    }
        /**
     * Retorna el carrito de esa compra
     * @return \Application_Model_CarritoCompra
     * @throws Exception
     */
    public function getCarrito(){
        if(!isset($this->_id)) throw new Exception("getCarritoException");
        $CarritoCompra = new Application_Model_CarritoCompra(NULL,NULL);
        $CarritoCompra->loadByIdCompra($this->_id);
        return $CarritoCompra;
    }
    
    /**
     * 
     * @param int $id_compra_base_64
     * @param string $email_md5
     * @return boolean
     * @throws Exception Compra Incorrecta
     */
    public function esConcordanteEmailMd5ByIdCompra($email_md5){
        $Usuario = new Application_Model_Usuario();
        $Usuario->load($this->_id_usuario);
        $this_email_md5 = md5($Usuario->getEmail());
        if($this_email_md5 == $email_md5){
            return true;
        }else{
            return false;
        }
    }
    
    public function write(){
        if(!isset($this->_id)) throw new Exception("writeCompraException");
        $id_compra=$this->_id;
        $id_usuario=$this->_id_usuario;
        $fecha=$this->_fecha;
        $id_forma_pago=$this->_id_forma_pago;
        $pagada=$this->_pagada;
        $enviada=$this->_enviada;
        $procesada=$this->_procesada;
        $direccion_xml = $this->_DireccionEnvio->getAsXml();
        $gastos_envio=$this->_gastos_envio;
        $localizador=$this->_localizador;
        $data=$this->_CompraDbTable->setCompraById($id_compra, $id_usuario, $fecha, $id_forma_pago, $pagada, $enviada, $procesada, $direccion_xml, $gastos_envio,$localizador);
        return $data;  
    } 
    
    /**
     * 
     * @param Application_Model_CarritoCompra $CarritoCompra
     * @return type
     */ 
    public function add(&$CarritoCompra=null){
        $id_usuario=$this->_id_usuario;
        $fecha=$this->_fecha;
        $id_forma_pago=$this->_id_forma_pago;
        $pagada=$this->_pagada;
        $enviada=$this->_enviada;
        $procesada=$this->_procesada;
        $direccion_xml = $this->_DireccionEnvio->getAsXml();
        $gastos_envio=$this->_gastos_envio;   
        $localizador=$this->_localizador;
        $data=$this->_CompraDbTable->addCompra($id_usuario, $fecha, $id_forma_pago, $pagada, $enviada, $procesada, $direccion_xml, $gastos_envio,$localizador);
        $this->_id=$data;
        $CarritoCompra->load();
        return $data; 
    } 
    /**
     * 
     * @param Application_Model_CarritoCompra $CarritoCompra
     * @return type
     * @throws Exception
     */
    public function delete(&$CarritoCompra=NULL){
        if(!isset($this->_id)) throw new Exception("deleteCompraException");
        $data=$this->_CompraDbTable->deleteCompraById($this->_id);
        if($CarritoCompra!=NULL){//actualizamos el carrito para que no se quede desactualizado despues de borrar la compra
            $CarritoCompra->load(); 
        } 
        return $data;     
    }        
}

   
   




