<?php
class Zend_View_Helper_Tooltip{
 
    function tooltip($tooltip_text, $tooltip_unique_id, $tootip_class, $tooltiptool_class = 'generic-tooltiptool') {
            $salida ='<div class="'.$tooltiptool_class.'" onmouseenter="$(\'#ticketex-tooltip-id-'.$tooltip_unique_id.'\').show();" onmouseleave="$(\'#ticketex-tooltip-id-'.$tooltip_unique_id.'\').hide();"></div>';    
            $salida .='<div id="ticketex-tooltip-id-'.$tooltip_unique_id.'" class="holder-pop-tooltip '.$tootip_class.'">';        
            $salida .=$tooltip_text;
            $salida .='</div>';
            return $salida;    
        }
    
}
?>