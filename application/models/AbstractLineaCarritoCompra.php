<?php

abstract class Application_Model_AbstractLineaCarritoCompra
{
    protected $_id;
    protected $_id_session;
    protected $_id_agrupador;
    protected $_id_producto;
    protected $_id_parent;
    protected $_fecha_insercion;
    /**
     * Tipos de linea producto:
     * LineaCarritoCompraSesion
     * LineaCarritoCompraRestauracion
     * LineaCarritoCompraPack
     * @var string 
     */
    protected $_tipo_linea_producto;
    protected $_cantidad;
    protected $_pvp;
    protected $_iva;
    protected $_id_usuario;
    protected $_id_compra;
    protected $_localizador;
    protected $_CarritoCompraDbTable;

    
    public function __construct($id=NULL,$id_producto=NULL,
            $id_parent=NULL,$tipo_linea_producto=NULL,
            $cantidad=NULL,$pvp=NULL,$iva=NULL,$id_session=NULL,$id_agrupador=NULL,$id_usuario=NULL,
            $id_compra=NULL){// le pasamos $id_usuario para poder en el administrador cargar las lineas de otros usuarios
        
        $this->_CarritoCompraDbTable = new Application_Model_DbTable_CarritoCompra();  
        $this->_id=$id; 
        $this->_id_session = $id_session;
        $this->_id_agrupador = $id_agrupador;
        $this->_id_usuario = $id_usuario;
        $this->_id_producto= $id_producto;
        $this->_id_parent= $id_parent;
        $this->_fecha_insercion= date('Y-m-d H:i:s');
        $this->_tipo_linea_producto= $tipo_linea_producto;
        $this->_cantidad= $cantidad;
        $this->_pvp= $pvp;
        $this->_iva= $iva;
        $this->_id_compra= $id_compra;
        $this->setLocalizador();
    }

    /**
     * Este metodo abstracto hara que la clase implmentadora ejecute lo que tenga que ejecutar al aumentar o disminuir
     * la cantidad de un producto añadido al carito
     * @param int $cantidad
     * @return boolean si es correcto se seteara la cantidad si no no.
     */
    abstract protected function setCantidadSpecificLineaCarritoCompra($cantidad);
    
    /**
     * Este metodo abstracto hara el load 
     * que implemente esta clase abstracta (en representacion_xml en eticketsecure2)
     * @param simple_xml_object $carrito_simple_xml
     */
    abstract protected function loadAtributosRepresentacionXml($linea_carrito_simple_xml);
    
    
    /**
    * Este metodo abastracto retorna
    * la Serializacion como XML de los atributos de la clase que implemente la clase abstracta
    * @return string
    */  
    abstract protected function getAsXmlAtributosRepresentacionXml(); 
    
    /**
    * @param integer $last_insert_id el id donde se inserto el carritoCompra
    * @param array[] $params_arr parametros que puede necesitar el metodo protected addSpecificCarritoCompra($params_arr)
    * Este metodo abastracto
    * Permite implementar logicas adicionales de write()
    */  
    abstract protected function addSpecificLineaCarritoCompra($params_arr,$last_insert_id);   
    
    /**
    * @param array[] $params_arr  parametros que puede necesitar el metodo protected writeSpecificCarritoCompra$params_arr)
    * Este metodo abastracto
    * Permite implementar logicas adicionales de write()
    */  
    abstract protected function writeSpecificLineaCarritoCompra($params_arr);   
    
    /**
    * @param array[] $params_arr parametros que puede necesitar el metodo protected deleteSpecificCarritoCompra($params_arr)
    * Este metodo abastracto
    * Permite implementar logicas adicionales de delete()
    */  
    abstract protected function deleteSpecificLineaCarritoCompra($params_arr);  
    
    public function load($id){
        if(!isset($id))  throw new Exception('load');
        $this->_id=$id;   
        $data = $this->_CarritoCompraDbTable->getLineaCarritoById($id);
        $this->_id_session= $data["id_session"];
        $this->_id_agrupador= $data["id_agrupador"];
        $this->_id_producto= $data["id_producto"];
        $this->_id_parent= $data["id_parent"];
        $this->_fecha_insercion= $data["fecha_insercion"];
        $this->_tipo_linea_producto= $data["tipo_linea_producto"];
        $this->_cantidad= $data["cantidad"];
        $this->_pvp= $data["pvp"];
        $this->_iva= $data["iva"];
        $this->_id_usuario= $data["id_usuario"];
        $this->_id_compra= $data["id_compra"];
        $this->_localizador= $data["localizador"];
        $data = $data["representacion_xml"];
        $linea_carrito_simple_xml= simplexml_load_string($data);
        $this->loadAtributosRepresentacionXml($linea_carrito_simple_xml); 
    }
    
    public function loadByLocalizador($localizador){
        if(!isset($localizador))  throw new Exception('loadByLocalizador');
        $data = $this->_CarritoCompraDbTable->getLineaCarritoByLocalizador($localizador);
        $this->load($data["id"]);
    }
    
    public function getId(){
        return $this->_id;
    }
    
    public function getIdSession(){
        return $this->id_session;
    }
    
    public function getIdAgrupador() {
        return $this->_id_agrupador;
    }

    public function setIdAgrupador($id_agrupador) {
        $this->_id_agrupador = $id_agrupador;
    }    

    public function getIdProducto(){
        return $this->_id_producto;
    } 
    
    public function setIdProducto($id_producto){
        $this->_id_producto=$id_producto;
    } 
    
    public function getIdParent(){
        return $this->_id_parent;
    } 
    
    public function setIdParent($id_parent){
        $this->_id_parent=$id_parent;
    } 
    
    public function getFechaInsercion() {
        return $this->_fecha_insercion;
    }

    public function setFechaInsercion($fecha_insercion) {
        $this->_fecha_insercion = $fecha_insercion;
    }
    
    public function getTipoLineaProducto() {
        return $this->_tipo_linea_producto;
    }

    public function setTipoLineaProducto($tipo_linea_producto) {
        $this->_tipo_linea_producto = $tipo_linea_producto;
    }

    public function getCantidad() {
        return $this->_cantidad;
    }
    
    /*
     * Este metodo ejecutara el codigo necesario para setear la cantidad
     */
    public function setCantidad($cantidad) {
        $this->setCantidadSpecificLineaCarritoCompra($cantidad); 
        $this->_cantidad = $cantidad;
    }

    
    public function getPvp() {
        return $this->_pvp;
    }
    

    public function setPvp($pvp) {
        $this->_pvp = $pvp;
    }
    
    public function getIva() {
        return $this->_iva;
    }

    public function setIva($iva) {
        $this->_iva = $iva;
    }
    
    public function getIdUsuario() {
        return $this->_id_usuario;
    }

    public function setIdUsuario($id_usuario) {
        $this->_id_usuario= $id_usuario;
    }

    public function getIdCompra() {
        return $this->_id_compra;
    }
    
    public function setIdCompra($id_compra) {
        $this->_id_compra = $id_compra;
    }
    public function getLocalizador() {
        return $this->_localizador;
    }

    private function setLocalizador() {
        $this->_localizador = uniqid();
    }

    public function getNombreProducto($idioma){
        if($this->_tipo_linea_producto=="LineaCarritoCompraSesion"){
            $Producto = new Application_Model_Sesion();
        }else if($this->_tipo_linea_producto=="LineaCarritoCompraRestauracion"){
            $Producto = new Application_Model_Restauracion();
        }else if($this->_tipo_linea_producto=="LineaCarritoCompraPack"){
            $Producto = new Application_Model_Pack();
        }
        $Producto->load($this->_id_producto);
        $nombre = $Producto->getNombre($idioma);
        return $nombre;
    }
                
    public function getAsXml(){
        $string_xml="<representacion_xml>\n";

        $string_xml.=$this->getAsXmlAtributosRepresentacionXml();
        
        $string_xml.="</representacion_xml>"; 
        
        return $string_xml;
    }    
          
    public function add($params_arr=NULL){
        $id_session=$this->_id_session;
        $id_agrupador=$this->_id_agrupador;
        $id_producto=$this->_id_producto;
        $id_parent=$this->_id_parent;
        $representacion_xml=$this->getAsXml();
        $fecha_insercion = $this->_fecha_insercion;
        $tipo_linea_producto=$this->_tipo_linea_producto;
        $cantidad=$this->_cantidad;
        $pvp=$this->_pvp;
        $iva=$this->_iva;
        $id_usuario=$this->_id_usuario;
        $localizador= $this->_localizador;
        $id_compra= $this->_id_compra;
        $data=$this->_CarritoCompraDbTable->addlineaCarrito($id_session, $id_agrupador,$id_producto, $id_parent, $representacion_xml, $fecha_insercion, $tipo_linea_producto, $cantidad, $pvp, $iva, $id_usuario, $localizador, $id_compra);
        $this->_id=$data;
        $this->addSpecificLineaCarritoCompra($params_arr,$this->_id);
        return $data;         
    } 
    
    public function write($params_arr=array()){
        if(!isset($this->_id)) throw new Exception("writeAbstractLineaCarritoCompraException");
        $this->writeSpecificLineaCarritoCompra($params_arr);
        $id=$this->_id;   
        $id_session=$this->_id_session;
        $id_agrupador=$this->_id_agrupador;
        $id_producto=$this->_id_producto;
        $id_parent=$this->_id_parent;
        $representacion_xml=$this->getAsXml();
        $fecha_insercion = $this->_fecha_insercion;
        $tipo_linea_producto=$this->_tipo_linea_producto;
        $cantidad=$this->_cantidad;
        $pvp=$this->_pvp;
        $iva=$this->_iva;
        $id_usuario=$this->_id_usuario;
        $localizador= $this->_localizador;
        $id_compra= $this->_id_compra;
        $data=$this->_CarritoCompraDbTable->setLineaCarritoById($id, $id_session, $id_agrupador,$id_producto, $id_parent, $representacion_xml, $fecha_insercion, $tipo_linea_producto, $cantidad, $pvp, $iva, $id_usuario, $localizador, $id_compra);
        return $data;
    } 
    
    public function delete($params_arr=array()){
        if(!isset($this->_id)) throw new Exception("deleteAbstractLineaCarritoCompraException");
        $this->deleteSpecificLineaCarritoCompra($params_arr);
        $data =$this->_CarritoCompraDbTable->deleteLineaCarritoById($this->_id);
        return $data;
    }    
    
}




