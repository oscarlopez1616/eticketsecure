<?php

class Application_Model_LineaCarritoCompraSesion extends Application_Model_AbstractLineaCarritoCompra
{
    /**
     *
     * @var array("tipo"=>$tipo , "nombre_especial_butaca_sesion","descripcion_especial_butaca_sesion","id_especial_butaca_sesion") 
     */
    protected $_tipo_arr;
    protected $_nombre_sala;
    protected $_nombre_zona;
    protected $_num_butaca;
    protected $_flag_numerada;    
    protected $_fila_butaca;
    protected $_nombre_sesion;
    protected $_date_time_sesion;
    protected $_nombre_descuento;
    protected $_id_zona;
    protected $_id_butaca;
    protected $_id_descuento;
    
    function __construct($id=NULL,$id_producto=NULL,$id_parent=NULL,$tipo_linea_producto=NULL,
            $cantidad=NULL,$pvp=NULL,$iva=NULL,$id_session=NULL,$id_agrupador=NULL,$id_usuario=NULL,$id_compra=NULL,$tipo_arr=array(),$nombre_sala=NULL, $nombre_zona=NULL, 
            $num_butaca=NULL,$flag_numerada=NULL,$fila_butaca=NULL, $nombre_sesion=NULL, $date_time_sesion=NULL,$nombre_descuento=NULL,
            $id_zona=NULL,$id_butaca=NULL,$id_descuento=NULL) {
        
        parent::__construct($id, $id_producto, $id_parent, $tipo_linea_producto, $cantidad, $pvp, $iva, $id_session,$id_agrupador, $id_usuario, $id_compra);
        $this->_tipo_arr = $tipo_arr;
        $this->_nombre_sala = $nombre_sala;
        $this->_nombre_zona = $nombre_zona;
        $this->_num_butaca = $num_butaca;
        $this->_flag_numerada = $flag_numerada;
        $this->_fila_butaca = $fila_butaca;
        $this->_nombre_sesion = $nombre_sesion;
        $this->_date_time_sesion = $date_time_sesion;
        $this->_nombre_descuento = $nombre_descuento;
        $this->_id_zona = $id_zona;
        $this->_id_butaca = $id_butaca;
        $this->_id_descuento = $id_descuento;
    }

    
    protected function loadAtributosRepresentacionXml($linea_carrito_simple_xml){
        $this->_tipo_arr = array();
        $this->_tipo_arr["tipo"] = (string)$linea_carrito_simple_xml->tipo->tipo;
        $this->_tipo_arr["nombre_especial_butaca_sesion"] = (string)$linea_carrito_simple_xml->tipo->nombre_especial_butaca_sesion;
        $this->_tipo_arr["descripcion_especial_butaca_sesion"] = (string)$linea_carrito_simple_xml->tipo->descripcion_especial_butaca_sesion;
        $this->_tipo_arr["id_especial_butaca_sesion"] = (string)$linea_carrito_simple_xml->tipo->id_especial_butaca_sesion;
        $this->_nombre_sala = (string)$linea_carrito_simple_xml->nombre_sala;
        $this->_nombre_zona = (string)$linea_carrito_simple_xml->nombre_zona;
        $this->_num_butaca = (int)$linea_carrito_simple_xml->num_butaca;
        $this->_flag_numerada = (int)$linea_carrito_simple_xml->flag_numerada;
        $this->_fila_butaca = (int)$linea_carrito_simple_xml->fila_butaca;
        $this->_nombre_sesion = (string)$linea_carrito_simple_xml->nombre_sesion;
        $this->_date_time_sesion = (string)$linea_carrito_simple_xml->date_time_sesion;  
        $this->_nombre_descuento = (string)$linea_carrito_simple_xml->nombre_descuento;  
        $this->_id_zona = (int)$linea_carrito_simple_xml->id_zona;  
        $this->_id_butaca = (int)$linea_carrito_simple_xml->id_butaca;
        if($linea_carrito_simple_xml->id_descuento == ""){
            $this->_id_descuento = NULL;
        }else{
            $this->_id_descuento = (int)$linea_carrito_simple_xml->id_descuento;   
        }
    }
    
    public function getTipo() {
        return $this->_tipo_arr["tipo"];
    }

    public function setTipo($tipo) {
        $this->_tipo_arr["tipo"] = $tipo;
    }
    
    public function getNombreEspecialButacaSesion() {
        return $this->_tipo_arr["nombre_especial_butaca_sesion"];
    }

    public function setNombreEspecialButacaSesion($nombre_especial_butaca_sesion) {
        if($this->_tipo_arr["tipo"] == "especial_butaca_sesion"){
            $this->_tipo_arr["nombre_especial_butaca_sesion"] = $nombre_especial_butaca_sesion;
        }else{
            throw new Exception('IlegalAssignationException NO tipo= especial_butaca_sesion');
        }
    }
    
    public function getDescripcionEspecialButacaSesion() {
        return $this->_tipo_arr["descripcion_especial_butaca_sesion"];
    }

    public function setDescripcionEspecialButacaSesion($descripcion_especial_butaca_sesion) {
        if($this->_tipo_arr["tipo"] == "especial_butaca_sesion"){
            $this->_tipo_arr["descripcion_especial_butaca_sesion"] = $descripcion_especial_butaca_sesion;
        }else{
            throw new Exception('IlegalAssignationException NO tipo= especial_butaca_sesion');
        }
    }
    
    public function getIdEspecialButacaSesion() {
        return $this->_tipo_arr["id_especial_butaca_sesion"];
    }

    public function setIdEspecialButacaSesion($id_especial_butaca_sesion) {
        if($this->_tipo_arr["tipo"] == "especial_butaca_sesion"){
            $this->_tipo_arr["id_especial_butaca_sesion"] = $id_especial_butaca_sesion;
        }else{
            throw new Exception('IlegalAssignationException NO tipo= especial_butaca_sesion');
        }
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

    public function getIdZona() {
        return $this->_id_zona;
    }

    public function setIdZona($id_zona) {
        $this->_id_zona = $id_zona;
    }

    public function getIdButaca() {
        return $this->_id_butaca;
    }

    public function setIdButaca($id_butaca) {
        $this->_id_butaca = $id_butaca;
    }

    public function getIdDescuento() {
        return $this->_id_descuento;
    }

    public function setIdDescuento($id_descuento) {
        $this->_id_descuento = $id_descuento;
    }

    
    protected function setCantidadSpecificLineaCarritoCompra($cantidad){}
    
    protected function getAsXmlAtributosRepresentacionXml(){
        $string_xml ="\t<tipo>\n";
        $string_xml.="\t\t<tipo>".htmlentities($this->_tipo_arr["tipo"], ENT_XML1)."</tipo>\n";
        $string_xml.="\t\t<nombre_especial_butaca_sesion>".htmlentities($this->_tipo_arr["nombre_especial_butaca_sesion"], ENT_XML1)."</nombre_especial_butaca_sesion>\n";
        $string_xml.="\t\t<descripcion_especial_butaca_sesion>".htmlentities($this->_tipo_arr["descripcion_especial_butaca_sesion"], ENT_XML1)."</descripcion_especial_butaca_sesion>\n";
        $string_xml.="\t\t<id_especial_butaca_sesion>".htmlentities($this->_tipo_arr["id_especial_butaca_sesion"], ENT_XML1)."</id_especial_butaca_sesion>\n";
        $string_xml.="\t</tipo>\n";
        $string_xml.="\t<nombre_sala>".htmlentities($this->_nombre_sala, ENT_XML1)."</nombre_sala>\n";
        $string_xml.="\t<nombre_zona>".htmlentities($this->_nombre_zona, ENT_XML1)."</nombre_zona>\n";
        $string_xml.="\t<num_butaca>".htmlentities($this->_num_butaca, ENT_XML1)."</num_butaca>\n";
        $string_xml.="\t<flag_numerada>".$this->_flag_numerada."</flag_numerada>\n";
        $string_xml.="\t<fila_butaca>".htmlentities($this->_fila_butaca, ENT_XML1)."</fila_butaca>\n";
        $string_xml.="\t<nombre_sesion>".htmlentities($this->_nombre_sesion, ENT_XML1)."</nombre_sesion>\n";
        $string_xml.="\t<date_time_sesion>".htmlentities($this->_date_time_sesion, ENT_XML1)."</date_time_sesion>\n";
        $string_xml.="\t<nombre_descuento>".htmlentities($this->_nombre_descuento, ENT_XML1)."</nombre_descuento>\n";
        $string_xml.="\t<id_zona>".$this->_id_zona."</id_zona>\n";
        $string_xml.="\t<id_butaca>".$this->_id_butaca."</id_butaca>\n";
        $string_xml.="\t<id_descuento>".$this->_id_descuento."</id_descuento>\n";
        return $string_xml;
    }

    protected function addSpecificLineaCarritoCompra($params_arr,$last_insert_id){
    }   
    
    protected function writeSpecificLineaCarritoCompra($params_arr){
    }   
    
    protected function deleteSpecificLineaCarritoCompra($params_arr){
    }  
    
}

