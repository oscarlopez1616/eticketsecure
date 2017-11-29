<?php

class Application_Model_InterfaceProductoStereotypeRestauracion  extends Application_Model_InterfaceProductoEticketSecure
{
    private $_id_categoria_arr;

    public function __construct(){
        parent::__construct();
        $restauracion_arr = $reg_webservice=Zend_Registry::get('restauracion');
        $num_tipos_restauracion = $restauracion_arr["count"];
        for($i=0;$i<$num_tipos_restauracion;$i++){
            $this->_id_categoria_arr[] = $restauracion_arr["id_categoria"][$i];
        }
    }
    
    /**
     * Devuelve todas las restauraciones para una Sesion
     * @param int $id_sesion
     * @param integer $num_pagina numero de pagina en la consulta
     * @param integer $elementos_pagina numero de elementos por pagina en la consulta
     * @return array("id_producto" => int
     *                "distancia"   => float)
     * @throws Exception
     */
    public function getIdRestauracionArrByIdSesion($id_sesion,$num_pagina="all",$elementos_pagina="all"){
        if($this->_flag_web_service){

        }else{
            $InterfaceGeo  = new Application_Model_InterfaceGeoEticketSecure();
            $perimetro_km = 3;
            $data = array();
            $Sesion = new Application_Model_Sesion();
            $Sesion->load($id_sesion);
            $id_sala = $Sesion->getIdSala();
            $Sala = new Application_Model_Sala();
            $Sala->load($id_sala);
            $Coordenadas_sala = $Sala->getCoordenadas();
            $id_producto_arr = array();
            $restauracion_arr = $reg_webservice=Zend_Registry::get('restauracion');
            $num_tipos_restauracion = $restauracion_arr["count"];
            for($i=0;$i<$num_tipos_restauracion;$i++){//cargamos todos los objetos restauraciones de todos los tipos_restauracion
                $id_categoria_arr[] = $restauracion_arr["id_categoria"][$i];
            }
            $temp_arr = $this->getIdProductoArrByIdCategoriaArr($id_categoria_arr,$num_pagina,$elementos_pagina);
            foreach($temp_arr as $temp){
                $id_producto_arr[]= $temp; 
            }
            $id_producto_arr = array_unique($id_producto_arr);
            $data_temp = array();
            $count = 0;
            foreach($id_producto_arr as $id_producto){
                $Restauracion = new Application_Model_Restauracion(null);
                $Restauracion->load($id_producto);
                $Coordenadas_restauracion = $Restauracion->getCoordenadas();
                $data = $InterfaceGeo->esProximo($Coordenadas_restauracion,$Coordenadas_sala,$perimetro_km);
                if($data["es_proximo"]){//si es true incluyo esa restauracion en data
                    $data_temp[$count]["id_producto"] = $id_producto;
                    $data_temp[$count]["distancia"] = $data["distancia"];
                    $count = $count+1;
                }
            }           
            if (count($data_temp)==0)  throw new Exception('No existen Restauraciones para este estos parametros');
        }
        return $data_temp;
    }  

}

