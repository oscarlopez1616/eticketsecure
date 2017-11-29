<?php

class Application_Model_RecortadorUrl
{
    private $_id;
    private $_url_completa;
    private $_url_recortada;
    private $_id_categoria;
    private $_RecortadorUrlDb;
    
    
    public function __construct($id=NULL,$url_completa=NULL,$url_recortada=NULL,$id_categoria=NULL)
    {        
        $this->_id=$id;
        $this->url_completa=$url_completa;
        $this->_url_recortada=$url_recortada;
        $this->_id_categoria=$id_categoria;
        $this->_RecortadorUrlDb= new Application_Model_DbTable_RecortadorUrl();
    } 

    public function load($id){
        if(!isset($id))    throw new Exception('load');
        $this->_id=$id; 
        $data=$this->_RecortadorUrlDb->getById($id);
        $this->_url_completa=$data["url_completa"]; 
        $this->_url_recortada=$data["url_recortada"]; 
        $this->_id_categoria=$data["id_categoria"];      
    }
    
    public function loadByUrlRecortada($url_recortada){
        if(!isset($url_recortada))    throw new Exception('loadByUrlRecortada');
        $data=$this->_RecortadorUrlDb->getByUrlRecortada($url_recortada);
        $this->load($data["id"]);
    }
    
    public function loadByIdRecorte($id_recorte,$redirigidor){
        if(!isset($id_recorte))    throw new Exception('loadByIdRecorte');
        $url_recortada = "/".$redirigidor."/".$id_recorte;
        $this->loadByUrlRecortada($url_recortada);
    }
    
    public function getId() {
        return $this->_id;
    }

    public function getUrlCompleta() {
        return $this->_url_completa;
    }

    public function getUrlRecortada() {
        return $this->_url_recortada;
    }
    
    public function getIdCategoria() {
        return $this->_id_categoria;
    }
    
    /**
     * Usa el redirigidor recortador.redirigidor_localizador
     * @param string $url_completa
     * @param string $localizador
     * @param int $id_categoria
     */
    public function creaUrlRecortadaParaTicketsConLocalizador($url_completa,$localizador,$id_categoria){    
        try {//si existe lo cargamos
            $data = $this->_RecortadorUrlDb->getByUrlCompleta($url_completa);
            $this->load($data["id"]);
            return;
        } catch (Exception $ex) {//si no existe lo creamos
            $this->_url_completa = $url_completa;
            $this->_id_categoria = $id_categoria;
            $recortador = Zend_Registry::get('recortador');
            $redirigidor = "/ticketex";
            $this->_url_recortada = $redirigidor."/".$localizador;
            $this->add();
        }
    }
    
    /**
     * Usa el redirigidor recortador.redirigidor_normal
     * @param string $url_completa
     * @param int $id_categoria
     */
    public function creaUrlRecortada($url_completa, $id_categoria){    
        try {//si existe lo cargamos
            $data = $this->_RecortadorUrlDb->getByUrlCompleta($url_completa);
            $this->load($data["id"]);
            return;
        } catch (Exception $ex) {//si no existe lo creamos
            $this->_url_completa = $url_completa;
            $this->_id_categoria = $id_categoria;
            try{
                $last_insert_id = $this->_RecortadorUrlDb->getLastInsertId();
                $this->_url_recortada = $last_insert_id+1;
            } catch (Exception $ex) {// es porque todavia no se ha metido nada en esta tabla y el primer id disponible es 1
                $this->_url_recortada = 1;
            }
            $redirigidor = "/re";
            $this->_url_recortada = $redirigidor."/".$this->_url_recortada;
            $this->add();
        }
    }

    private function add(){
        $url_completa=$this->_url_completa;
        $url_recortada=$this->_url_recortada;
        $id_categoria=$this->_id_categoria;
        $data=$this->_RecortadorUrlDb->add($url_completa, $url_recortada, $id_categoria);
        $this->_id=$data;
        return $data; ;
    }
    
    private function write(){
        if(!isset($this->_id)) throw new Exception("write");
        $url_completa=$this->_url_completa;
        $url_recortada=$this->_url_recortada;
        $id_categoria=$this->_id_categoria;
        $data=$this->_RecortadorUrlDb->setById($this->_id,$url_completa, $url_recortada, $id_categoria);
        $this->_id=$data;
        return $data; ;
    }  
    
    private function delete(){
        if(!isset($this->_id)) throw new Exception("delete");
        $data=$this->_RecortadorUrlDb->deleteById($id);
        return $data;   
    } 
}





