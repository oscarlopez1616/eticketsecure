<?php 

class Eticketsecure_Filter_Sef implements Zend_Filter_Interface
{
    public function filter($value)
    {
        //Eliminamos los espacios en blanco del principio y del final
        $valueFiltered = trim($value);
        
        //Reemplazamos " " por "-"
        $valueFiltered = str_replace(' ', '-', $valueFiltered);
            
        //Pasamos todo a minusculas
        $valueFiltered = strtolower($valueFiltered);
        
        //Sustituimos las "ñ" por "n"
        $valueFiltered = str_replace('ñ', 'n', $valueFiltered);
        
        //Eliminamos las apariciones de caracteres que no sean alfanuméricos o "-"
        $valueFiltered=  preg_replace("/[^a-z0-9-]/", "", $valueFiltered);
        
        return $valueFiltered;
    }
}
    
?>