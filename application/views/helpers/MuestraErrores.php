<?php
class Zend_View_Helper_MuestraErrores{
 
    function muestraErrores($error_arr, $field_name, $orientation_icon='normal') {
        if (count($error_arr)==0) {
            return '';
        }else{
            if ($orientation_icon == 'short'){
                $icon_class="icon-pop-form-error-short";
            }
            if ($orientation_icon == 'regalo'){
                $icon_class="icon-pop-form-error icon-pop-form-error-regalo";
            }
            
            if ($orientation_icon == 'regalo-2'){
                $icon_class="icon-pop-form-error icon-pop-form-error-regalo-2";
            }
            if ($orientation_icon == 'normal'){
                $icon_class='icon-pop-form-error';
            }   
            $salida ='<div class="'.$icon_class.'" onmouseenter="$(\'#pop-window-error-id-'.$field_name.'\').show();" onmouseleave="$(\'#pop-window-error-id-'.$field_name.'\').hide();"></div>';    
            $salida .='<div id="pop-window-error-id-'.$field_name.'" class="holder-pop-form-error">';
                foreach ($error_arr as $key=>$one_error){
                    $salida .='<span>'.$one_error.'</span>';
                    if (count($error_arr<$key-1)) $salida .='<br />';
                }
            $salida .='</div>';
            return $salida;
        }
            
    }
    
}
?>