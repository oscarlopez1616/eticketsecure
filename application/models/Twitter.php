<?php

class Application_Model_Twitter
{
    private $_usario;
    private $_password;
    private $_hashtags_arr;
    
    public function __construct($usario=NULL,$password=NULL,$hashtags_arr=array()){
        $this->_usario= $usario;
        $this->_password= $password;
        $this->_hashtags_arr= $hashtags_arr;
    }

    public function getUsuario(){
        return $this->_usario;
    }
    
    public function setUsuario($usuario){
        $this->_usario=$usuario;
    }
    
    public function getPassword(){
        return $this->_password;
    }
    
    public function setPassword($password){
          $this->_password=$password;
    }
    
    public function getHashtagsArr(){
        return $this->_hashtags_arr;
    } 
    
    
    public function addHashtagArr($hashtag){
        $this->_hashtags_arr[]=$hashtag;
    }
    
    public function deleteHashtagArrByIndex($index){
        unset($this->_hashtags_arr[$index]);
        $temp = array();
        foreach($this->_hashtags_arr as $imagen){
            $temp[] = $imagen;
        }
        $this->_hashtags_arr = $temp;   
    }
    
    public function getAsXml(){
        $string_xml = "\n<twitter>";
        $string_xml.= "\n\t<usario>".htmlentities($this->_usario, ENT_XML1)."</usario>";
        $string_xml.= "\n\t<password>".htmlentities($this->_password, ENT_XML1)."</password>";
        $string_xml.="\t\n<hashtags>";
        foreach($this->_hashtags_arr as $hashtag){              
            $string_xml.="\n\t\t<hashtag>";
            $string_xml.=htmlentities($hashtag,ENT_XML1);
            $string_xml.="</hashtag>";
        }   
        $string_xml.="\t\n</hashtags>";
        $string_xml.= "\n</twitter>";
        return $string_xml;
    }

}

