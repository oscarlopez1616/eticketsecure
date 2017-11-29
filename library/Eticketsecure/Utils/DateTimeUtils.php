<?php
class Eticketsecure_Utils_DateTimeUtils {

    /**
     * Convierte un date(Y-m-d H:i:s) a ejemplo -> Sabado 5 de Enero del 2014 - 12:51 sin segundos
     * @param string $date_time
     */
    function dateTimetoString($idioma,$date_time) {
        $comunes_translate= new Zend_Translate('array', APPLICATION_PATH . '/configs/languages/comunes/'.$idioma.'.php', $idioma);        
        $Datetime_dias = new DateTime(date($date_time)); 
        $fecha_dia = $comunes_translate->_('calendar.diassemana.'.$Datetime_dias->format('w'));
        $fecha_numero = (int)($Datetime_dias->format('d'));
        $fecha_mes = $comunes_translate->_('calendar.mesesanyo.'.(int)$Datetime_dias->format('m'));
        $fecha_hora = $Datetime_dias->format('H:i');
        return $fecha_dia.' '.$fecha_numero.' de '.$fecha_mes.' - '.$fecha_hora;
    }
}
?>
