<?php
class Eticketsecure_Utils_IpFunctions {


    /**
     * Retorna la Ip Real del Usuario
     * @return string
     */
    function getRealIP() {
        if (!empty($_SERVER['HTTP_CLIENT_IP'])){
            return $_SERVER['HTTP_CLIENT_IP'];
        }  
        if (!empty($_SERVER['HTTP_X_FORWARDED_FOR'])){
            return $_SERVER['HTTP_X_FORWARDED_FOR'];
        }
        return $_SERVER['REMOTE_ADDR'];
    }
    
    /**
     * Retorna el HTTP_REFERER
     * @return string
     */
    function getReferer() {
        if (isset($_SERVER["HTTP_REFERER"])){
            $referer =  $_SERVER["HTTP_REFERER"];
        }else{
            $referer = null;
        }
        return $referer;
    }


}
?>
