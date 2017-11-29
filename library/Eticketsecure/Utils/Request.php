<?php
class Eticketsecure_Utils_Request {


    /**
     * Retorna los parametros en string,
     * Pasa getParams de sobre Un Request a string.
     * @return string
     */
    function requestParamsToStrig($user_params_arr) {
        $string ="";
        foreach($user_params_arr as $key=>$user_param){
            $string =$string."/".$key."/".$user_param;
        }
        return $string =$string."/";
    }


}
?>
