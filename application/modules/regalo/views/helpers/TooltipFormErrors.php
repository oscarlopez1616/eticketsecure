<?php
class Zend_View_Helper_TooltipFormErrors {
 
    function tooltipFormErrors($error_arr) {
        $ruta = Zend_Registry::get('ruta'); 
        $ruta_gui = $ruta['dominio'].$ruta['base'].$ruta['admin']['gui'];
        if (count($error_arr)==0) {
            return '';
        }else{
            $error_message='';
            foreach ($error_arr as $one_error){
                $error_message.='- '.$one_error;
            }
            return '<img class="hastip" src="'.$ruta_gui.'/qm_error.png" title="'.$error_message.'" />';
        }
            
    }
    
}
?>