<?php
class Zend_View_Helper_Cargador {
    
    public function cargaStyles($cargador_arr = array()) { 
        $ruta = Zend_Registry::get('ruta'); 
        $cargador =  '';
        $ruta_css_comunes = $ruta['dominio'].$ruta['base'].$ruta['comunes']['css']."/";  
        $cargador = '<link media="screen" rel="stylesheet" type="text/css" href="'.$ruta_css_comunes.'style.css" />';
        if ($cargador_arr['module'] == 'admin'){
            $ruta_css = $ruta['dominio'].$ruta['base'].$ruta['admin']['css']."/";    
            
            //ESTILOS COMUNES
            $cargador .= '<link media="screen" rel="stylesheet" type="text/css" href="'.$ruta_css.'common.css" />';
            $cargador .= '<link media="screen" rel="stylesheet" type="text/css" href="'.$ruta_css.'layout.css" />';
            $cargador .= '<link media="screen" rel="stylesheet" type="text/css" href="'.$ruta_css.'buttons.css" />';
            $cargador .= '<link media="screen" rel="stylesheet" type="text/css" href="'.$ruta_css.'forms.css" />';
            $cargador .= '<link media="screen" rel="stylesheet" type="text/css" href="'.$ruta_css.'plugins.css" />';
            
            if ($cargador_arr['controller'] == 'index'){
                $cargador = '<link media="screen" rel="stylesheet" type="text/css" href="'.$ruta_css.'login.css" />';    
            }
            
        }
        
        if ($cargador_arr['module'] == 'frontTeatreneu'){
            $ruta_css = $ruta['dominio'].$ruta['base'].$ruta['frontteatreneu']['css']."/";    
            
            //ESTILOS COMUNES
            $cargador .= '<link media="screen" rel="stylesheet" type="text/css" href="'.$ruta_css.'common.css" />';
            $cargador .= '<link media="screen" rel="stylesheet" type="text/css" href="'.$ruta_css.'layout.css" />';
            $cargador .= '<link media="screen" rel="stylesheet" type="text/css" href="'.$ruta_css.'buttons.css" />';
            $cargador .= '<link media="screen" rel="stylesheet" type="text/css" href="'.$ruta_css.'forms.css" />';
            $cargador .= '<link media="screen" rel="stylesheet" type="text/css" href="'.$ruta_css.'plugins.css" />';
        }
        
        if ($cargador_arr['module'] == 'frontTicketing'){
            
            $ruta_css = $ruta['dominio'].$ruta['base'].$ruta['frontticketing']['css']."/";    
            $cargador .= '<link media="screen" rel="stylesheet" type="text/css" href="'.$ruta_css.'style.css" />';
        }
        if ($cargador_arr['module'] == 'adminUsuario'){
            $ruta_css = $ruta['dominio'].$ruta['base'].$ruta['adminusuario']['css']."/";    
            $cargador .= '<link media="screen" rel="stylesheet" type="text/css" href="'.$ruta_css.'style.css" />';            
        }
        if ($cargador_arr['module'] == 'regalo'){
            $ruta_css = $ruta['dominio'].$ruta['base'].$ruta['regalo']['css']."/";    
            $cargador .= '<link media="screen" rel="stylesheet" type="text/css" href="'.$ruta_css.'style.css?id=789" />';
            
            
        }
        
        return $cargador;    
    }
    
    public function cargaScripts($cargador_arr = array()){    
        $ruta = Zend_Registry::get('ruta'); 
        $cargador = '';
        $ruta_js_comunes = $ruta['dominio'].$ruta['base'].$ruta['comunes']['js']."/";  
        $cargador = '<script src="'.$ruta_js_comunes.'jquery-1.9.1.min.js"></script>';
        $cargador .= '<script src="'.$ruta_js_comunes.'functions.js"></script>';
        if ($cargador_arr['module'] == 'admin'){
            $ruta_js = $ruta['dominio'].$ruta['base'].$ruta['admin']['js']."/";
            $cargador .= '<script src="'.$ruta_js.'jquery-1.9.1.min.js"></script>';
            $cargador .= '<script src="'.$ruta_js.'jquery-ui-1.10.3.custom.min.js"></script>';
            $cargador .= '<script src="'.$ruta_js.'jquery.form.js"></script>';
            $cargador .= '<script src="'.$ruta_js.'tooltipsy.min.js"></script>';
            $cargador .= '<script src="'.$ruta_js.'tinymce/tinymce.min.js"></script>';
            $cargador .= '<script type="text/javascript">tinymce.init({
                mode : "specific_textareas",
                editor_selector : "tinny-editor-class",
                statusbar : false,
                plugins: "link charmap preview searchreplace visualchars code fullscreen pagebreak",
                menubar : false,
                toolbar1: "undo redo | cut copy paste removeformat | link charmap visualchars | searchreplace preview code fullscreen pagebreak",
                toolbar2: "bold italic underline superscript | outdent indent | numlist bullist alignleft aligncenter alignright alignjustify",});</script>';
            $cargador .= '<script src="'.$ruta_js.'canvasjs.min.js"></script>';
            $cargador .= '<script src="'.$ruta_js.'jfunctions.js"></script>';
            $cargador .= '<script src="'.$ruta_js.'login-functions.js"></script>';
        }
        
        if ($cargador_arr['module'] == 'frontTeatreneu'){
            $ruta_js = $ruta['dominio'].$ruta['base'].$ruta['frontTeatreneu']['js']."/";
        }
        
        if ($cargador_arr['module'] == 'frontTicketing'){
            $ruta_js = $ruta['dominio'].$ruta['base'].$ruta['frontticketing']['js']."/";
            
            $cargador .= '<script src="'.$ruta_js.'jfunctions.js"></script>';
            $cargador .= '<script src="'.$ruta_js.'d3.v3.min.js"></script>';
            $cargador .= '<script src="'.$ruta_js.'jquery-ui-1.10.2.custom.min.js"></script>';

            /*$cargador .= '
            <!--Start of Zopim Live Chat Script-->
            <script type="text/javascript">
            window.$zopim||(function(d,s){var z=$zopim=function(c){z._.push(c)},$=z.s=
            d.createElement(s),e=d.getElementsByTagName(s)[0];z.set=function(o){z.set.
            _.push(o)};z._=[];z.set._=[];$.async=!0;$.setAttribute(\'charset\',\'utf-8\');
            $.src=\'//v2.zopim.com/?1Kop0nFp53dagRkMBMzvDNoqUCcUReFi\';z.t=+new Date;$.
            type=\'text/javascript\';e.parentNode.insertBefore($,e)})(document,\'script\');
            </script>
            <!--End of Zopim Live Chat Script-->            
            ';*/
        }
        if ($cargador_arr['module'] == 'adminUsuario'){
            $ruta_js = $ruta['dominio'].$ruta['base'].$ruta['adminusuario']['js']."/";
            $cargador  = '<script src="'.$ruta_js.'jquery-1.9.1.min.js"></script>';
        }
        if ($cargador_arr['module'] == 'regalo'){
            $ruta_js = $ruta['dominio'].$ruta['base'].$ruta['regalo']['js']."/";
            $cargador  = '<script src="'.$ruta_js.'jquery-1.9.1.min.js"></script>';
            $cargador .= '<script src="'.$ruta_js.'functions.js"></script>';
        }
        
        //ESTILOS COMUNES
        
        return $cargador;
    }
    
    
    
    
}
?>