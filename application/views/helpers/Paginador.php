<?php
class Zend_View_Helper_Paginador{
    
    function paginador($pagina_actual, $num_paginas, $script_function, $url_base) {
        $html= '<div id="pases_big_paginador">';
        
        if($pagina_actual>0){
            $html.= '<a href="javascript:'.$script_function.'(\''.$url_base.'?json=1&page='.($pagina_actual-1).'\');"><</a>';
        }else{
            $html.= '<span><</span>';
        }

        if ($pagina_actual > 4) {
            $html.= '<a href="javascript:'.$script_function.'(\''.$url_base.'?json=1&page=0\');">1</a><span class="paginador-suspensive">...</span>';
        }
        for($i=0; $i<$num_paginas; $i++){
            if (($i+5) > $pagina_actual && ($i-5) < $pagina_actual) {
                if ($pagina_actual != $i) $html.='<a href="javascript:'.$script_function.'(\''.$url_base.'?json=1&page='.$i.'\');">'.($i+1).'</a>';
                if ($pagina_actual == $i) $html.='<a class="paginador-actual" href="#">'.($i+1).'</a>';
            }
        }
                
        if ($pagina_actual < ($num_paginas-5)){
            $html.= '<span class="paginador-suspensive">...</span><a href="javascript:'.$script_function.'(\''.$url_base.'?json=1&page='.($num_paginas-1).'\');">'.$num_paginas.'</a>';
        }
        
        if(($pagina_actual+1)<$num_paginas){
            $html.='<a href="javascript:'.$script_function.'(\''.$url_base.'?json=1&page='.($pagina_actual+1).'\')">></a>';
        }else{
            $html.= '<span>></span>';
        }
        
        $html.= '</div>';
        
        return $html;
    }
    
}
?>