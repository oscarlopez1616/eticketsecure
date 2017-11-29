<?php
class Zend_View_Helper_ContentPageBreaker {

    /**
     * 
     * @param string $contenido
     * @param string $flag_system_readmore default "all" todo el contenido sin el <!--pagebreak-->, 
     * "admin" todo el contenido con el <!--pagebreak-->,
     * "intro" la parte de introduccion del contenido antes del <!--pagebreak-->,
     * "extended" la parte extendida del contenido después del <!--pagebreak-->
     * "numeric"  devuele el numero de caracteres pasado como parametro $count
     * @param integer $count numero de caracteres a devolver
     * @return string
     */
    public function getContentHtmlSystemReadmore($contenido, $flag_system_readmore="all",$count=0) {
        if ($flag_system_readmore=="admin"){
            return $contenido;
        }else if ($flag_system_readmore=="all"){
            $contenido = str_replace('<!--pagebreak-->', '', $contenido);
            if ($count!=0) $contenido=substr($contenido, 0, $count);
            return $contenido;
        }else if ($flag_system_readmore=="intro"){
            $contenido_arr = explode('<!--pagebreak-->', $contenido);
            if (isset($contenido_arr[0])){
                $contenido =$contenido_arr[0];
                if ($count!=0) $contenido=substr($contenido_arr[0], 0, $count);
                return $contenido;
            }
            else return NULL;
        }else if ($flag_system_readmore=="extended"){
            $contenido_arr = explode('<!--pagebreak-->', $contenido);
            if (isset($contenido_arr[1])){
                $contenido =$contenido_arr[1];
                if ($count!=0) $contenido=substr($contenido_arr[1], 0, $count);
                return $contenido;
            }
            else return NULL;
        }else if ($flag_system_readmore=="numeric"){
            return substr($contenido, 0, $count);
        }
    }
 
}
?>
