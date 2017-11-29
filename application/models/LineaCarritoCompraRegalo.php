<?php


class Application_Model_LineaCarritoCompraRegalo extends Application_Model_AbstractLineaCarritoCompra
{
    private $_nombre;
    private $_date_time;
    private $_descripcion_servicio;
    private $_detalles;
    private $_ruta_imagen;
    private $_nombre_receptor_regalo;
    private $_email_receptor_regalo;
    private $_nombre_comprador_regalo;
    
    function __construct($id=NULL,$id_producto=NULL,$id_parent=NULL,$tipo_linea_producto=NULL,
            $cantidad=NULL,$pvp=NULL,$iva=NULL,$id_session=NULL,$id_usuario=NULL,$id_compra=NULL,
            $nombre=NULL,$date_time=NULL,$descripcion_servicio=NULL,$detalles=NULL,$ruta_imagen=NULL,
            $nombre_receptor_regalo=NULL,$email_receptor_regalo=NULL,$nombre_comprador_regalo=NULL) {
        
        parent::__construct($id, $id_producto, $id_parent, $tipo_linea_producto, $cantidad, $pvp, $iva, $id_session,0, $id_usuario, $id_compra);
        $this->_nombre = $nombre;
        $this->_date_time = $date_time;
        $this->_descripcion_servicio =$descripcion_servicio;
        $this->_detalles =$detalles;
        $this->_ruta_imagen =$ruta_imagen;
        $this->_nombre_receptor_regalo =$nombre_receptor_regalo;
        $this->_email_receptor_regalo =$email_receptor_regalo;
        $this->_nombre_comprador_regalo =$nombre_comprador_regalo;
    }
    
    
    protected function loadAtributosRepresentacionXml($linea_carrito_simple_xml){
        $this->_nombre = (string)$linea_carrito_simple_xml->nombre;
        $this->_date_time = (string)$linea_carrito_simple_xml->date_time;
        $this->_descripcion_servicio = (string)$linea_carrito_simple_xml->descripcion_servicio;
        $this->_detalles = (string)$linea_carrito_simple_xml->detalles;
        $this->_ruta_imagen = (string)$linea_carrito_simple_xml->ruta_imagen;
        $this->_nombre_receptor_regalo = (string)$linea_carrito_simple_xml->nombre_receptor_regalo;
        $this->_email_receptor_regalo = (string)$linea_carrito_simple_xml->email_receptor_regalo;
        $this->_nombre_comprador_regalo = (string)$linea_carrito_simple_xml->nombre_comprador_regalo;
    }

    public function getNombre() {
        return $this->_nombre;
    }

    public function setNombre($nombre) {
        $this->_nombre = $nombre;
    }

    public function getDate_time() {
        return $this->_date_time;
    }

    public function setDate_time($date_time) {
        $this->_date_time = $date_time;
    }

    public function getDescripcion_servicio() {
        return $this->_descripcion_servicio;
    }

    public function setDescripcion_servicio($descripcion_servicio) {
        $this->_descripcion_servicio = $descripcion_servicio;
    }

    public function getDetalles() {
        return $this->_detalles;
    }
    
    public function getNombreCompradorRegalo() {
        return $this->_nombre_comprador_regalo;
    }

    public function setDetalles($detalles) {
        $this->_detalles = $detalles;
    }

    public function getRutaImagen() {
        return $this->_ruta_imagen;
    }

    public function setRutaImagen($ruta_imagen) {
        $this->_ruta_imagen = $ruta_imagen;
    }
    
    public function getNombreReceptorRegalo() {
        return $this->_nombre_receptor_regalo;
    }

    public function setNombreReceptorRegalo($nombre_receptor_regalo) {
        $this->_nombre_receptor_regalo = $nombre_receptor_regalo;
    }
    
    public function getEmailReceptorRegalo() {
        return $this->_email_receptor_regalo;
    }

    public function setEmailReceptorRegalo($email_receptor_regalo) {
        $this->_email_receptor_regalo = $email_receptor_regalo;
    }
    
    public function setNombreCompradorRegalo($nombre_comprador_regalo) {
        $this->_nombre_comprador_regalo = $nombre_comprador_regalo;
    }

    
   protected function setCantidadSpecificLineaCarritoCompra($cantidad){}
    
    protected function getAsXmlAtributosRepresentacionXml(){
        $string_xml ="\t<nombre>".htmlentities($this->_nombre, ENT_XML1)."</nombre>\n";
        $string_xml.="\t<date_time>".htmlentities($this->_date_time, ENT_XML1)."</date_time>\n";
        $string_xml.="\t<descripcion_servicio>".htmlentities($this->_descripcion_servicio, ENT_XML1)."</descripcion_servicio>\n";
        $string_xml.="\t<detalles>".htmlentities($this->_detalles, ENT_XML1)."</detalles>\n";
        $string_xml.="\t<ruta_imagen>".htmlentities($this->_ruta_imagen, ENT_XML1)."</ruta_imagen>\n";
        $string_xml.="\t<nombre_receptor_regalo>".htmlentities($this->_nombre_receptor_regalo, ENT_XML1)."</nombre_receptor_regalo>\n";
        $string_xml.="\t<email_receptor_regalo>".htmlentities($this->_email_receptor_regalo, ENT_XML1)."</email_receptor_regalo>\n";
        $string_xml.="\t<nombre_comprador_regalo>".htmlentities($this->_nombre_comprador_regalo, ENT_XML1)."</nombre_comprador_regalo>\n";
        return $string_xml;
    }
    
    protected function addSpecificLineaCarritoCompra($params_arr,$last_insert_id){
    }   
    
    protected function writeSpecificLineaCarritoCompra($params_arr){
    }   
    
    protected function deleteSpecificLineaCarritoCompra($params_arr){
    } 
}

