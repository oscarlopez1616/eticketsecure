<?php

class Application_Model_LineaCarritoCompraPack extends Application_Model_AbstractLineaCarritoCompra
{
    protected $_nombre_sala;
    protected $_nombre_zona;
    protected $_num_butaca;
    protected $_flag_numerada;    
    protected $_fila_butaca;
    protected $_nombre_sesion;
    protected $_date_time_sesion;
    protected $_nombre_descuento;
    
    function __construct($id=NULL,$id_producto=NULL,$id_parent=NULL,$tipo_linea_producto=NULL,
            $cantidad=NULL,$pvp=NULL,$iva=NULL,$id_session=NULL,$id_agrupador=NULL,$id_usuario=NULL,$id_compra=NULL,$nombre_sala=NULL, $nombre_zona=NULL, $num_butaca=NULL, 
            $flag_numerada=NULL,$fila_butaca=NULL, $nombre_sesion=NULL, $date_time_sesion=NULL,$nombre_descuento=NULL) {
        
        parent::__construct($id, $id_producto, $id_parent, $tipo_linea_producto, $cantidad, $pvp, $iva, $id_session,$id_agrupador, $id_usuario, $id_compra);
        $this->_nombre_sala = $nombre_sala;
        $this->_nombre_zona = $nombre_zona;
        $this->_num_butaca = $num_butaca;
        $this->_flag_numerada = $flag_numerada;
        $this->_fila_butaca = $fila_butaca;
        $this->_nombre_sesion = $nombre_sesion;
        $this->_date_time_sesion = $date_time_sesion;
        $this->_nombre_descuento = $nombre_descuento;
    }

    
    protected function loadAtributosRepresentacionXml($linea_carrito_simple_xml){
        $this->_nombre_sala = (string)$linea_carrito_simple_xml->nombre_sala;
        $this->_nombre_zona = (string)$linea_carrito_simple_xml->nombre_zona;
        $this->_num_butaca = (int)$linea_carrito_simple_xml->num_butaca;
        $this->_flag_numerada = (int)$linea_carrito_simple_xml->flag_numerada;
        $this->_fila_butaca = (int)$linea_carrito_simple_xml->fila_butaca;
        $this->_nombre_sesion = (string)$linea_carrito_simple_xml->nombre_sesion;
        $this->_date_time_sesion = (string)$linea_carrito_simple_xml->date_time_sesion;  
        $this->_nombre_descuento = (string)$linea_carrito_simple_xml->nombre_descuento;  
    }
    

    public function getNombreSala() {
        return $this->_nombre_sala;
    }

    public function setNombreSala($nombre_sala) {
        $this->_nombre_sala = $nombre_sala;
    }

    public function getNombreZona() {
        return $this->_nombre_zona;
    }

    public function setNombreZona($nombre_zona) {
        $this->_nombre_zona = $nombre_zona;
    }

    public function getNumButaca() {
        return $this->_num_butaca;
    }

    public function setNumButaca($num_butaca) {
        $this->_num_butaca = $num_butaca;
    }

    public function getFlagNumerada(){
        return $this->_flag_numerada;
    }
    
    public function setFlagNumeradaTrue() {
        $this->_flag_numerada = 1;
    }

    public function setFlagNumeradaFalse() {
        $this->_flag_numerada = 0;
    }

    public function getFilaButaca() {
        return $this->_fila_butaca;
    }

    public function setFilaButaca($fila_butaca) {
        $this->_fila_butaca = $fila_butaca;
    }

    public function getNombreSesion() {
        return $this->_Nombre_sesion;
    }

    public function setNombreSesion($Nombre_sesion) {
        $this->_Nombre_sesion = $Nombre_sesion;
    }

    public function getDateTimeSesion() {
        return $this->_date_time_sesion;
    }

    public function setDateTimeSesion($date_time_sesion) {
        $this->_date_time_sesion = $date_time_sesion;
    }
    
    public function getNombreDescuento() {
        return $this->_nombre_descuento;
    }

    public function setNombreDescuento($nombre_descuento) {
        $this->_nombre_descuento = $nombre_descuento;
    }

    protected function setCantidadSpecificLineaCarritoCompra($cantidad){}
    
    protected function getAsXmlAtributosRepresentacionXml(){
        $string_xml ="\t<nombre_sala>".htmlentities($this->_nombre_sala, ENT_XML1)."</nombre_sala>\n";
        $string_xml.="\t<nombre_zona>".htmlentities($this->_nombre_zona, ENT_XML1)."</nombre_zona>\n";
        $string_xml.="\t<num_butaca>".htmlentities($this->_num_butaca, ENT_XML1)."</num_butaca>\n";
        $string_xml.="\t<flag_numerada>".$this->_flag_numerada."</flag_numerada>\n";
        $string_xml.="\t<fila_butaca>".htmlentities($this->_fila_butaca, ENT_XML1)."</fila_butaca>\n";
        $string_xml.="\t<nombre_sesion>".htmlentities($this->_nombre_sesion, ENT_XML1)."</nombre_sesion>\n";
        $string_xml.="\t<date_time_sesion>".htmlentities($this->_date_time_sesion, ENT_XML1)."</date_time_sesion>\n";
        $string_xml.="\t<nombre_descuento>".htmlentities($this->_nombre_descuento, ENT_XML1)."</nombre_descuento>\n";
        return $string_xml;
    }

    protected function addSpecificLineaCarritoCompra($params_arr,$last_insert_id){
    }   
    
    protected function writeSpecificLineaCarritoCompra($params_arr){
    }   
    
    protected function deleteSpecificLineaCarritoCompra($params_arr){
    }  
    
}

