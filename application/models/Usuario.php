<?php

class Application_Model_Usuario
{
    protected $_id;
    protected $_id_role;
    protected $_email;
    protected $_password;    
    protected $_nombre;
    protected $_apellidos;
    protected $_fecha_nacimiento;
    protected $_telefono;
    protected $_telefono_movil;
    protected $_codigo_postal;
    protected $_UsuarioDireccion_envio_predefinida;
    protected $_fecha_alta;
    protected $_fecha_ultimo_login;
    protected $_idioma_predefinido;
    protected $_flag_opt_in;
    protected $_activo;
    protected $_InterfaceUsuarioEticketSecure;
    
    public function __construct($id=NULL,$id_role=NULL,$email=NULL,$password=NULL,$nombre=NULL,$apellidos=NULL,$fecha_nacimiento=NULL,
            $telefono=NULL,$telefono_movil=NULL,$codigo_postal=NULL,$UsuarioDireccion_envio_predefinida= NULL,
            $fecha_alta=NULL,$fecha_ultimo_login=NULL,$idioma_predefinido=NULL,$flag_opt_in=NULL,$activo=NULL){
        
        $this->_InterfaceUsuarioEticketSecure = new Application_Model_InterfaceUsuarioEticketSecure(); 

        $this->_id= $id;
        $this->_id_role= $id_role;
        $this->_email= $email;
        $this->_password= $password;
        $this->_nombre= $nombre;
        $this->_apellidos= $apellidos;
        $this->_fecha_nacimiento= $fecha_nacimiento;
        $this->_telefono= $telefono;
        $this->_telefono_movil= $telefono_movil;
        $this->_codigo_postal= $codigo_postal;
        $this->_UsuarioDireccion_envio_predefinida= $UsuarioDireccion_envio_predefinida;
        $this->_fecha_alta= $fecha_alta;
        $this->_fecha_ultimo_login= $fecha_ultimo_login;
        $this->_idioma_predefinido= $idioma_predefinido;
        $this->_flag_opt_in= $flag_opt_in;
        $this->_activo= $activo;
    } 

    public function load($id){
        $this->_id=$id;
        $data = $this->_InterfaceUsuarioEticketSecure->getUsuarioById($id);
        $this->_email= $data["email"];
        $this->_id_role= $data["id_role"];
        $this->_password= $data["password"];
        $this->_fecha_alta= $data["fecha_alta"];
        $this->_fecha_ultimo_login= $data["fecha_ultimo_login"];
        $this->_idioma_predefinido= $data["idioma_predefinido"];
        $this->_flag_opt_in= $data["flag_opt_in"];
        $this->_activo= $data["activo"];
        $data = $data["representacion_xml"];
        $artefacto_simple_xml= simplexml_load_string($data);
        $this->_nombre= (string)$artefacto_simple_xml->nombre;
        $this->_apellidos= (string)$artefacto_simple_xml->apellidos;
        $this->_fecha_nacimiento= (string)$artefacto_simple_xml->fecha_nacimiento;
        $this->_telefono= (string)$artefacto_simple_xml->_telefono;
        $this->_telefono_movil= (string)$artefacto_simple_xml->telefono_movil;
        $this->_codigo_postal= (string)$artefacto_simple_xml->codigo_postal;
        
        $direccion_envio_predefinida = $artefacto_simple_xml->direccion;
        $pais= (string)$direccion_envio_predefinida->pais;
        $provincia= (string)$direccion_envio_predefinida->provincia;
        $poblacion= (string)$direccion_envio_predefinida->poblacion;
        $cp= (string)$direccion_envio_predefinida->cp;
        $direccion= (string)$direccion_envio_predefinida->direccion;
        $numero= (string)$direccion_envio_predefinida->numero;
        $piso= (string)$direccion_envio_predefinida->piso;
        $escalera= (string)$direccion_envio_predefinida->escalera;
        $telf_movil= (string)$direccion_envio_predefinida->telf_movil;
        $telf= (string)$direccion_envio_predefinida->telf;
        $UsuarioDireccion = new Application_Model_UsuarioDireccion($pais, $provincia, $poblacion, $cp, $direccion, $numero, $piso, $escalera, $telf_movil, $telf);
        $this->_UsuarioDireccion_envio_predefinida = $UsuarioDireccion;
    }
    
    public function loadByEmail($email){
        $data = $this->_InterfaceUsuarioEticketSecure->getUsuarioByEmail($email);  
        $this->load($data["id"]);
    }
    
    public function getId(){
        return $this->_id;
    }
    
    public function getIdRole() {
        return $this->_id_role;
    }

    public function setIdRole($id_role) {
        $this->_id_role = $id_role;
    }

    public function getTelefono() {
        return $this->_telefono;
    }

    public function setTelefono($telefono) {
        $this->_telefono = $telefono;
    }

    public function getTelefonoMovil() {
        return $this->_telefono_movil;
    }

    public function setTelefonoMovil($telefono_movil) {
        $this->_telefono_movil = $telefono_movil;
    }

        
    public function getEmail(){
        return $this->_email;
    }
    
    public function getPassword(){
        return $this->_nombre;
    }
    
    public function setPassword($password){
       $this->_password= $password;
    }
    
    public function getNombre(){
        return $this->_nombre;
    }
    
    public function setNombre($nombre){
       $this->_nombre= $nombre;
    }
    
    public function getApellidos(){
        return $this->_apellidos;
    }
    
    public function setApellidos($apellidos){
        $this->_apellidos= $apellidos;

    }
    
    public function getFechaNacimiento(){
        return $this->_fecha_nacimiento;
    }
    
    public function setFechaNacimiento($fecha_nacimiento){
        $this->_fecha_nacimiento= $fecha_nacimiento;

    }
    
    public function getCodigoPostal(){
        return $this->_codigo_postal;
    }
    
    public function setCodigoPostal($codigo_postal){
        $this->_codigo_postal= $codigo_postal;

    }
    
    public function getUsuarioDireccionEnvioPredefinida(){
        return $this->_UsuarioDireccion_envio_predefinida;
    }
    
    public function setUsuarioDireccionEnvioPredefinida($UsuarioDireccion_envio_predefinida){
        $this->_UsuarioDireccion_envio_predefinida= $UsuarioDireccion_envio_predefinida;

    }
    public function getFechaAlta() {
        return $this->_fecha_alta;
    }

    public function getFechaUltimoLogin() {
        return $this->_fecha_ultimo_login;
    }

    public function getIdiomaPredefinido() {
        return $this->_idioma_predefinido;
    }

    public function getFlagOptIn() {
        return $this->_flag_opt_in;
    }

    public function setFechaAlta($fecha_alta) {
        $this->_fecha_alta = $fecha_alta;
    }

    public function setFechaUltimoLogin($fecha_ultimo_login) {
        $this->_fecha_ultimo_login = $fecha_ultimo_login;
    }

    public function setIdiomaPredefinido($idioma_predefinido) {
        $this->_idioma_predefinido = $idioma_predefinido;
    }

    public function setFlagOptIn($flag_opt_in) {
        $this->_flag_opt_in = $flag_opt_in;
    }

    public function getActivo() {
        $this->_activo;
    }

    public function setActivo($activo) {
        $this->_activo = $activo;
    }

        
    
    /**
     *  
     * @param string $password tine que ser el $this->_password codificado en md5
     */
    public function esPasswordCorrectoForReiniciarPassword($password){
        if(md5($password) == $this->_password){
            return true;
        }
        return false;
    }
    
    public function getPasswordForReiniciarPassword(){
        return md5($this->_password);
    }
    
    public function getAsXml(){
        $string_xml="<representacion_xml>\n";
        $string_xml.="\t<nombre>".$this->_nombre."</nombre>\n";
        $string_xml.="\t<apellidos>".$this->_apellidos."</apellidos>\n";
        $string_xml.="\t<fecha_nacimiento>".$this->_fecha_nacimiento."</fecha_nacimiento>\n";
        $string_xml.="\t<telefono>".$this->_telefono."</telefono>\n";
        $string_xml.="\t<telefono_movil>".$this->_telefono_movil."</telefono_movil>\n";
        $string_xml.="\t<codigo_postal>".$this->_codigo_postal."</codigo_postal>\n";
        $string_xml.=$this->_UsuarioDireccion_envio_predefinida->getAsXml();
        $string_xml.="\n</representacion_xml>\n";	
        return $string_xml;
    }
    
    public function write(){
        if(!isset($this->_id)) throw new Exception("writeUsuario");
        $id = $this->_id;
        $email = $this->_email;
        $id_role = $this->_id_role;
        $password = $this->_password;
        $fecha_alta= $this->_fecha_alta;
        $fecha_ultimo_login= $this->_fecha_ultimo_login;
        $idioma_predefinido= $this->_idioma_predefinido;
        $flag_opt_in= $this->_flag_opt_in;
        $activo= $this->_activo;
        $representacion_xml = $this->getAsXml();   
        $data = $this->_InterfaceUsuarioEticketSecure->setUsuarioById($id, $email, $id_role, $password,$fecha_alta,$fecha_ultimo_login,$idioma_predefinido,$flag_opt_in,$activo,$representacion_xml);
        return $data;        
    }
  
    public function add(){
        $email = $this->_email;
        $id_role = $this->_id_role;
        $password = $this->_password;
        $fecha_alta= $this->_fecha_alta;
        $fecha_ultimo_login= $this->_fecha_ultimo_login;
        $idioma_predefinido= $this->_idioma_predefinido;
        $flag_opt_in= $this->_flag_opt_in;
        $activo= $this->_activo;
        $representacion_xml = $this->getAsXml();   
        $data=$this->_InterfaceUsuarioEticketSecure->addUsuario($email, $id_role, $password, $fecha_alta,$fecha_ultimo_login,$idioma_predefinido,$flag_opt_in,$activo,$representacion_xml);
        $this->_id=$data;
        return $data;          
    }  
    
    public function delete(){
        if(!isset($this->_id)) throw new Exception("deleteUsuarioException");
        $data=$this->_InterfaceUsuarioEticketSecure->deleteUsuarioById($this->_id);
        return $data;      
    }  
    
}

