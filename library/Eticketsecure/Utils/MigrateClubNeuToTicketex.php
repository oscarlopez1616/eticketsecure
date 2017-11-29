<?php
class Eticketsecure_Utils_MigrateClubNeuToTicketex {

    /**
     * Esta funcion crea la url con el formato de internext para
     * @param string $texto
     * @return ulr de teatreneu
     */
    function format_url_rewrite ($texto) {

            $texto = trim($texto);

            $texto = preg_replace("(à|á|â|ã|ä|å)","a",$texto);
            $texto = preg_replace("(À|Á|Â|Ã|Ä|Å)","A",$texto);
            $texto = preg_replace("(è|é|ê|ë)","e",$texto);
            $texto = preg_replace("(È|É|Ê|Ë)","E",$texto);
            $texto = preg_replace("(ì|í|î|ï)","i",$texto);
            $texto = preg_replace("(Ì|Í|Î|Ï)","I",$texto);
            $texto = preg_replace("(ò|ó|ô|õ|ö|ø)","o",$texto);
            $texto = preg_replace("(Ò|Ó|Ô|Õ|Ö|Ø)","O",$texto);
            $texto = preg_replace("(ù|ú|û|ü)","u",$texto);
            $texto = preg_replace("(Ù|Ú|Û|Ü)","U",$texto);
            $texto = str_replace("Ç","C",$texto);
            $texto = str_replace("ç","c",$texto);
            $texto = str_replace("Ñ","N",$texto);
            $texto = str_replace("ñ","n",$texto);
            $texto = str_replace("ÿ","y",$texto);
            $texto = str_replace(" ","-",$texto);
            $texto = preg_replace('/[^a-zA-Z0-9_-]/', '',$texto);

            return $texto;

    }
    /**
     * 
     * @param int $id_empresa
     * @param int $id_role
     */
    function MigrateBbddWebTicketexDesdeHoy($id_empresa=1, $id_role=2) {
        $link=$this->ConectarseBbddWebTneu();               
        
        $validator_email = new Eticketsecure_Validate_EmailAddress();
        $validator_telf_movil = new Eticketsecure_Validate_TelefonoMovil();       
        $sql = "select * from ttn_associats where data>='".date("Y-m-d")."'";
        $result=mysql_query($sql,$link);
        $count = mysql_affected_rows($link);
        for($j=0 ;$j<$count;$j++){
            $row = mysql_fetch_array($result);
            $email = $row["email"];
            if($validator_email->isValid($email)){
                $password = md5($row["contrassenya"]);
                $nombre = $row["nom"];
                $apellidos = $row["cognoms"];
                $fecha_nacimiento = $row["naixement"];
                if($validator_telf_movil->isValid($row["telefon"])){// si es un telf movil valido lo meto
                    $telefono = "";
                    $telefono_movil = $row["telefon"];   
                }else{
                    $telefono = $row["telefon"];
                    $telefono_movil = "";
                }

                $codigo_postal = $row["cp"];
                $fecha_alta= $row["data"]." 00:00:00";
                $fecha_ultimo_login= $row["data_login"]." 00:00:00";
                $idioma_predefinido= $row["idioma"];
                $flag_opt_in= $row["informacio"];
                $activo=1;
                //direccion
                $pais = "ES";
                $provincia = "";
                $poblacion = "";
                $cp = $row["cp"];
                $direccion = $row["direccio"];
                $numero="";
                $piso="";
                $escalera="";
                $telf="";
                $UsuarioDireccion_envio_predefinida = new Application_Model_UsuarioDireccion($pais, $provincia, $poblacion, $cp, $direccion, $numero, $piso, $escalera, $telefono_movil,$telf);
                try{
                    $Usuario = new Application_Model_Usuario();
                    $Usuario->loadByEmail($email);
                } catch (Exception $e) {// si peta el load tiene que hacer el insert ya que no existe ese usuario
                    try{
                        $Usuario = new Application_Model_Usuario(NULL, $id_role, $email, $password, $nombre, $apellidos, $fecha_nacimiento, $telefono, $telefono_movil, $codigo_postal, $UsuarioDireccion_envio_predefinida,$fecha_alta,$fecha_ultimo_login,$idioma_predefinido,$flag_opt_in,$activo);
                        $Usuario->add(); 
                    } catch (Exception $ex) {
                    }
                } 
            }else{
                echo "<br>el email: ".$email." esta repetido o no es correcto";
            }
        }

        mysql_close($link); 
    }
    /**
     * 
     * @param int $id_empresa
     * @param int $id_role
     */
    function MigrateBbddWebTicketex($id_empresa=1, $id_role=2) {
        $link=$this->ConectarseBbddWebTneu();        
        $result=mysql_query("select count(*) from ttn_associats",$link);
        $max_resultados = mysql_fetch_row($result);
        $max_resultados = $max_resultados[0];
        $limit_block_user = 50;
        $numero_iteraciones =ceil($max_resultados/$limit_block_user);//redondea al entero superior
        
        
        $validator_email = new Eticketsecure_Validate_EmailAddress();
        $validator_telf_movil = new Eticketsecure_Validate_TelefonoMovil();

                
        for($i=0; $i<$numero_iteraciones; $i++) {
            $multiplicador = $i*$limit_block_user;
            $sql = "select * from ttn_associats limit ".$multiplicador.",".$limit_block_user;
            $result=mysql_query($sql,$link);
            $count = mysql_affected_rows($link);
            for($j=0 ;$j<$count;$j++){
                $row = mysql_fetch_array($result);
                $email = $row["email"];
                if($validator_email->isValid($email)){
                    $password = md5($row["contrassenya"]);
                    $nombre = $row["nom"];
                    $apellidos = $row["cognoms"];
                    $fecha_nacimiento = $row["naixement"];
                    if($validator_telf_movil->isValid($row["telefon"])){// si es un telf movil valido lo meto
                        $telefono = "";
                        $telefono_movil = $row["telefon"];   
                    }else{
                        $telefono = $row["telefon"];
                        $telefono_movil = "";
                    }

                    $codigo_postal = $row["cp"];
                    $fecha_alta= $row["data"]." 00:00:00";
                    $fecha_ultimo_login= $row["data_login"]." 00:00:00";
                    $idioma_predefinido= $row["idioma"];
                    $flag_opt_in= $row["informacio"];
                    $activo=1;
                    //direccion
                    $pais = "ES";
                    $provincia = "";
                    $poblacion = "";
                    $cp = $row["cp"];
                    $direccion = $row["direccio"];
                    $numero="";
                    $piso="";
                    $escalera="";
                    $telf="";
                    $UsuarioDireccion_envio_predefinida = new Application_Model_UsuarioDireccion($pais, $provincia, $poblacion, $cp, $direccion, $numero, $piso, $escalera, $telefono_movil,$telf);
                    try{
                        $Usuario = new Application_Model_Usuario();
                        $Usuario->loadByEmail($email);
                    } catch (Exception $e) {
                        try{
                            $Usuario = new Application_Model_Usuario(NULL, $id_role, $email, $password, $nombre, $apellidos, $fecha_nacimiento, $telefono, $telefono_movil, $codigo_postal, $UsuarioDireccion_envio_predefinida,$fecha_alta,$fecha_ultimo_login,$idioma_predefinido,$flag_opt_in,$activo);
                            $Usuario->add(); 
                        } catch (Exception $ex) {
                        }
                    } 
                }else{
                    echo "<br>el email: ".$email." esta repetido o no es correcto";
                }
            }
        }
        mysql_close($link); 
    }
    
    private function ConectarseBbddWebTneu()
    {
       $reg_bbdd_teatreneu_arr=Zend_Registry::get('bbdd_teatreneu');
       $name_bbdd=$reg_bbdd_teatreneu_arr["database"]["params"]["dbname"];
       if (!($link=mysql_connect($reg_bbdd_teatreneu_arr["database"]["params"]["host"],$reg_bbdd_teatreneu_arr["database"]["params"]["username"],$reg_bbdd_teatreneu_arr["database"]["params"]["password"])))
       {
          throw new Exception('Error conectando a la base de datos.');
       }
       if (!mysql_select_db($name_bbdd,$link))
       {
          throw new Exception('Error seleccionando la base de datos.');
       }
       return $link;
    } 
    



}
?>
