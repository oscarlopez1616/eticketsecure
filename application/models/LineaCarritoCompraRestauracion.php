<?php
/** 
 * siempre tiene id_parent y es el id_session.(por eso esta cambiado el nombre en el constructor y tiene metodos para ayudar al progrador a clarificarlo) 
 * Porque una Restauracion siempre debe pertenecer a una sesion no se puede vender sola
 */
class Application_Model_LineaCarritoCompraRestauracion extends Application_Model_AbstractLineaCarritoCompra
{
    private $_nombre;
    private $_date_time;
    private $_descripcion_servicio;
    private $_detalles;
    private $_more;
    private $_nombre_local;
    private $_telf_local;
    private $_web_local;
    private $_pais_local;
    private $_provincia_local;
    private $_poblacion_local;
    private $_cp_local;
    private $_direccion_local;
    private $_pvp_anterior;
    
    
    function __construct($id=NULL,$id_producto=NULL,$id_sesion=NULL,$tipo_linea_producto=NULL,
            $cantidad=NULL,$pvp=NULL,$iva=NULL,$id_session=NULL,$id_usuario=NULL,$id_compra=NULL,
            $nombre=NULL,$date_time=NULL,$descripcion_servicio=NULL,$detalles=NULL,$more=NULL,
            $nombre_local=NULL,$telf_local=NULL,$web_local=NULL,$pais_local=NULL,$provincia_local=NULL,
            $poblacion_local=NULL,$cp_local=NULL,$direccion_local=NULL,$pvp_anterior=NULL) {
        
        parent::__construct($id, $id_producto, $id_sesion, $tipo_linea_producto, $cantidad, $pvp, $iva, $id_session,0, $id_usuario, $id_compra);
        $this->_nombre = $nombre;
        $this->_date_time = $date_time;
        $this->_descripcion_servicio =$descripcion_servicio;
        $this->_detalles =$detalles;
        $this->_more =$more;
        $this->_nombre_local =$nombre_local;
        $this->_telf_local =$telf_local;
        $this->_web_local =$web_local;
        $this->_pais_local =$pais_local;
        $this->_provincia_local =$provincia_local;
        $this->_poblacion_local =$poblacion_local;
        $this->_cp_local =$cp_local;
        $this->_direccion_local =$direccion_local;
        $this->_pvp_anterior =$pvp_anterior;
    }
    
    
    protected function loadAtributosRepresentacionXml($linea_carrito_simple_xml){
        $this->_nombre = (string)$linea_carrito_simple_xml->nombre;
        $this->_nombre_local = (string)$linea_carrito_simple_xml->nombre_local;
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

    public function setDetalles($detalles) {
        $this->_detalles = $detalles;
    }

    public function getMore() {
        return $this->_more;
    }

    public function setMore($more) {
        $this->_more = $more;
    }

    public function getNombreLocal() {
        return $this->_nombre_local;
    }

    public function setNombreLocal($nombre_local) {
        $this->_nombre_local = $nombre_local;
    }

    public function getTelfLocal() {
        return $this->_telf_local;
    }

    public function setTelfLocal($telf_local) {
        $this->_telf_local = $telf_local;
    }

    public function getWebLocal() {
        return $this->_web_local;
    }

    public function setWebLocal($web_local) {
        $this->_web_local = $web_local;
    }

    public function getPaisLocal() {
        return $this->_pais_local;
    }

    public function setPaisLocal($pais_local) {
        $this->_pais_local = $pais_local;
    }

    public function getProvinciaLocal() {
        return $this->_provincia_local;
    }

    public function setProvinciaLocal($provincia_local) {
        $this->_provincia_local = $provincia_local;
    }

    public function getPoblacionLocal() {
        return $this->_poblacion_local;
    }

    public function setPoblacionLocal($poblacion_local) {
        $this->_poblacion_local = $poblacion_local;
    }

    public function getCpLocal() {
        return $this->_cp_local;
    }

    public function setCpLocal($cp_local) {
        $this->_cp_local = $cp_local;
    }

    public function getDireccionLocal() {
        return $this->_direccion_local;
    }

    public function setDireccionLocal($direccion_local) {
        $this->_direccion_local = $direccion_local;
    }

    public function getPvpAnterior() {
        return $this->_pvp_anterior;
    }

    public function setPvpAnterior($pvp_anterior) {
        $this->_pvp_anterior = $pvp_anterior;
    }

    
    /**
     * Para recordar que LineaCarritoCompraRestauracion implementa IdSesion atraves de IdParent
     * @return int
     */
    public function getIdSesion(){
        return parent::getIdParent();
    }
    
    /**
     * Para recordar que LineaCarritoCompraRestauracion implementa IdSesion atraves de IdParent
     * @param int $id_sesion
     */
    public function setIdSesion($id_sesion){
        parent::setIdParent($id_sesion);
    }

    protected function setCantidadSpecificLineaCarritoCompra($cantidad){}
    
    protected function getAsXmlAtributosRepresentacionXml(){
        $string_xml ="\t<nombre>".htmlentities($this->_nombre, ENT_XML1)."</nombre>\n";
        $string_xml.="\t<date_time>".htmlentities($this->_date_time, ENT_XML1)."</date_time>\n";
        $string_xml.="\t<pvp_anterior>".$this->_pvp_anterior."</pvp_anterior>\n";
        $string_xml.="\t<descripcion_servicio>".htmlentities($this->_descripcion_servicio, ENT_XML1)."</descripcion_servicio>\n";
        $string_xml.="\t<detalles>".htmlentities($this->_detalles, ENT_XML1)."</detalles>\n";
        $string_xml.="\t<more>".htmlentities($this->_more, ENT_XML1)."</more>\n";
        $string_xml.="\t<local>\n";
        $string_xml.="\t\t<nombre_local>".htmlentities($this->_nombre_local, ENT_XML1)."</nombre_local>\n";
        $string_xml.="\t\t<telf_local>".htmlentities($this->_telf_local, ENT_XML1)."</telf_local>\n";
        $string_xml.="\t\t<web_local>".htmlentities($this->_web_local, ENT_XML1)."</web_local>\n";
        $string_xml.="\t\t<pais_local>".htmlentities($this->_pais_local, ENT_XML1)."</pais_local>\n";
        $string_xml.="\t\t<provincia_local>".htmlentities($this->_provincia_local, ENT_XML1)."</provincia_local>\n";
        $string_xml.="\t\t<poblacion_local>".htmlentities($this->_poblacion_local, ENT_XML1)."</poblacion_local>\n";
        $string_xml.="\t\t<cp_local>".htmlentities($this->_cp_local, ENT_XML1)."</cp_local>\n";
        $string_xml.="\t\t<direccion_local>".htmlentities($this->_direccion_local, ENT_XML1)."</direccion_local>\n";
        $string_xml.="\t</local>\n";
        return $string_xml;
    }
    
    protected function addSpecificLineaCarritoCompra($params_arr,$last_insert_id){
    }   
    
    protected function writeSpecificLineaCarritoCompra($params_arr){
    }   
    
    protected function deleteSpecificLineaCarritoCompra($params_arr){
    } 
}

