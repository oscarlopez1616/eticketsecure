<?php

class Application_Model_Metatag
{
    private $_meta_title_arr;
    private $_meta_description_arr;
    private $_meta_keywords_arr;
    
    public function __construct($meta_title_arr=NULL,$meta_description_arr=NULL,$meta_keywords_arr=NULL){
        $this->_meta_title_arr= $meta_title_arr;
        $this->_meta_description_arr= $meta_description_arr;
        $this->_meta_keywords_arr= $meta_keywords_arr;
    }

    public function getMetaTitleArr($idioma){
        return $this->_meta_title_arr[$idioma];
    } 
    
    public function setMetaTitleArr($meta_title,$idioma){
        $this->_meta_title_arr[$idioma]=$meta_title;
    } 
    
    public function getMetaDescriptionArr($idioma){
        return $this->_meta_description_arr[$idioma];
    } 
    
    public function setMetaDescriptionArr($meta_description,$idioma){
        $this->_meta_description_arr[$idioma]=$meta_description;
    } 
    
    public function getMetaKeywordsArr($idioma){
        return $this->_meta_keywords_arr[$idioma];
    } 
    
    public function setMetaKeywordsArr($meta_keywords,$idioma){
        $this->_meta_keywords_arr[$idioma]=$meta_keywords;
    } 
    
    public function getAsXml(){
        $idioma_arr = $reg_webservice=Zend_Registry::get('idioma');
        $num_idiomas = $idioma_arr["count"];
        
        $string_xml ="\t<meta_tag>\n";
        for($i=0;$i<$num_idiomas;$i++){              
            $idioma= $idioma_arr[$i];
            $string_xml.="\t\t<".$idioma.">\n";
            $string_xml.= "\t\t\t<meta_title>";
            $string_xml.=htmlentities($this->_meta_title_arr[$idioma], ENT_XML1);
            $string_xml.="</meta_title>\n";
 
            $string_xml.="\t\t\t<meta_description>";
            $string_xml.=htmlentities($this->_meta_description_arr[$idioma], ENT_XML1);
            $string_xml.="</meta_description>\n";
            
            $string_xml.="\t\t\t<meta_keywords>";
            $string_xml.=htmlentities($this->_meta_keywords_arr[$idioma], ENT_XML1);
            $string_xml.="</meta_keywords>\n";
            $string_xml.="\t\t</".$idioma.">\n";
        }
        $string_xml.="\t</meta_tag>\n";
        
        return $string_xml;
    }

}


