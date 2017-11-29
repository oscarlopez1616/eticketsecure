<?php

class Application_Model_InterfaceGeoEticketSecure  extends Application_Model_InterfaceEticketSecure
{
    private $_controller;
    private $_cache;
    public function __construct(){
        parent::__construct();
        $this->_controller= "/geo/index/index.php";
        $this->_rest_ful_service = "ServiceGeo";
    }
    
    /**
     * Devuelve true si $coordenadas1 esta en un radio igual a  $perimetro_km de $coordenadas2
     * @param /Application_Model_Coordenadas $Coordenadas1
     * @param /Application_Model_Coordenadas $Coordenadas2
     * @param float $perimetro_km
     * @return array(es_proximo => boolean)
     */
    public function esProximo($Coordenadas1, $Coordenadas2,$perimetro_km){
        if($this->_flag_web_service){

        }else{
            $data = $this->distanciaGeodesicaEntreCoordenadas($Coordenadas1, $Coordenadas2);
            $km = $data["km"];
            $data = array();
            if($perimetro_km<$km){
                $data["es_proximo"] = false;
                $data["distancia"] = $km;
            }else{
                $data["es_proximo"] = true; 
                $data["distancia"] = $km; 
            }
        }
        return $data;
    }
    
    /**
     * Devuelve la distancia geodesica en Kilometros entre la Coordenada1 y la Coordenada2
     * @param /Application_Model_Coordenadas $Coordenadas1
     * @param /Application_Model_Coordenadas $Coordenadas2
     * @return array(km => float) distancia en kilometros de la unidad internacional
     */
    public function distanciaGeodesicaEntreCoordenadas($Coordenadas1, $Coordenadas2){
        if($this->_flag_web_service){

        }else{
            $lat1=$Coordenadas1->getLatitud();
            $long1=$Coordenadas1->getLongitud();
            $lat2=$Coordenadas2->getLatitud();
            $long2=$Coordenadas2->getLongitud();

            $degtorad = 0.01745329;
            $radtodeg = 57.29577951;

            $dlong = ($long1 - $long2);
            $dvalue = (sin($lat1 * $degtorad) * sin($lat2 * $degtorad))
            + (cos($lat1 * $degtorad) * cos($lat2 * $degtorad)
            * cos($dlong * $degtorad));

            $dd = acos($dvalue) * $radtodeg;

            $km = ($dd * 111.302);
            $data = array();
            $data["km"] = $km;
        }
        return $data; 
    }
    
    
    /**
    * Devuelve informacion zona1_name(comunidad autonoma) para un codigo_postal
    * @param string $country_code el identificador de pais al que pertenece esa info geo en la BBDD
    * @param string $codigo_postal el codigo_postal al que pertenece esa info geo en al BBDD
    * @return xml
    */  
    public function getZona1NameByCountryCodeAndCodigoPostal($country_code,$codigo_postal){
        if($this->_flag_web_service){
            $url ="&country_code=".urlencode($country_code)."&codigo_postal=".urlencode($codigo_postal); 
            $webservice=$this->getUrlWebService($this->_controller,"&method=getZona1NameByCountryCodeAndCodigoPostal".$url);
            $data=$this->restFul($webservice);
            if($data->getZona1NameByCountryCodeAndCodigoPostal->status == "failed") throw new Exception('InterfaceGeoException');
            $data=Eticketsecure_Utils_XML2Array::createArray($data,$this->_rest_ful_service,"getZona1NameByCountryCodeAndCodigoPostal");
        }else{        
            $codigoPostal = new Application_Model_DbTable_CodigoPostal ();
            $geo_arr=$codigoPostal->getZona1NameByCountryCodeAndCodigoPostal($country_code, $codigo_postal);
            if (count($geo_arr) == 0) throw new Exception('No existe info geo para estos parametros');  
            $data =$geo_arr[0]; 
        }
        return $data;
    }
    
    /**
    * Devuelve informacion zona2_name(provincia) para un codigo_postal
    * @param string $country_code el identificador de pais al que pertenece esa info geo en la BBDD
    * @param string $codigo_postal el codigo_postal al que pertenece esa info geo en al BBDD
    * @return xml
    */  
    public function getZona2NameByCountryCodeAndCodigoPostal($country_code,$codigo_postal){
        if($this->_flag_web_service){
            $url ="&country_code=".urlencode($country_code)."&codigo_postal=".urlencode($codigo_postal); 
            $webservice=$this->getUrlWebService($this->_controller,"&method=getZona2NameByCountryCodeAndCodigoPostal".$url);
            $data=$this->restFul($webservice);
            if($data->getZona2NameByCountryCodeAndCodigoPostal->status == "failed") throw new Exception('InterfaceGeoException');
            $data=Eticketsecure_Utils_XML2Array::createArray($data,$this->_rest_ful_service,"getZona2NameByCountryCodeAndCodigoPostal");
        }else{        
            $codigoPostal = new Application_Model_DbTable_CodigoPostal ();
            $geo_arr=$codigoPostal->getZona2NameByCountryCodeAndCodigoPostal($country_code, $codigo_postal);
            if (count($geo_arr) == 0) throw new Exception('No existe info geo para estos parametros');  
            $data =$geo_arr[0]; 
        }
        return $data;
    }
    
    /**
    * Devuelve toda la lista de paises, sus country_name para un country_code determinado
    * @param string $country_code el identificador de pais al que pertenece esa info geo en la BBDD
    * @return xml
    */  
    public function getCountryNameArrByIsoLangCode($country_code){
        if($this->_flag_web_service){
            $url ="&country_code=".urlencode($country_code); 
            $webservice=$this->getUrlWebService($this->_controller,"&method=getCountryNameArrByIsoLangCode".$url);
            $data=$this->restFul($webservice);
            if($data->getCountryNameArrByIsoLangCode->status == "failed") throw new Exception('InterfaceGeoException');
            $data=Eticketsecure_Utils_XML2Array::createArray($data,$this->_rest_ful_service,"getCountryNameArrByIsoLangCode");
        }else{        
            $Pais = new Application_Model_DbTable_Pais();
            $geo_arr=$Pais->getCountryNameArrByIsoLangCode($country_code);
            if (count($geo_arr) == 0) throw new Exception('No existe info geo para estos parametros');  
            $data =$geo_arr; 
        }
        return $data;
    }
}

