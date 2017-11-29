<?php
class Eticketsecure_Utils_SecurizeDefender {
    
    /**
     * 
     * @param string $localizador
     * @param string $nombre_proceso_defender
     * @return boolean
     * @throws Exception
     */
    function SecurizeRecortadorUrlByLocalizador($localizador,$nombre_proceso_defender) {   
        //COMPROBACION USUARIO - LocalizadorCompra
        $flag_exception = false;
        $flag_recortador_url_correcto = false;
        $Compra = new Application_Model_Compra();
        try{
            $Compra->loadByLocalizador($localizador);
            $flag_recortador_url_correcto = true;
        } catch (Exception $e) {
                $message_exception ='Defender Ticket Incorrecto';
                $flag_exception = true;
        }
        //DEFENDER       
        $IpFunctions = new Eticketsecure_Utils_IpFunctions();
        $real_ip = $IpFunctions->getRealIP();    
        $Defender = new Application_Model_Defender();
        $Defender->loadByNombreProceso($nombre_proceso_defender);
        $ManejadorRelacionDefenderIp = $Defender->startManejadorRelacionDefenderIp($real_ip);
        if(!$flag_recortador_url_correcto){
            $ManejadorRelacionDefenderIp->incrementaIntentos();// el Manejador lanza una Exception  y para la ejecucion. Si el incrementaIntentos() detecta que ya hemos pasado del umbral de proteccion ip   
            $message_exception ='Defender Incorrecto';
            $flag_exception = true;
        }
        
        if($flag_exception) throw new Exception($message_exception); 

        return $flag_recortador_url_correcto;
    }
    
    /**
     * Este utils utiliza el Defender para protejer el proceso a nivel de ip
     * y aparte devuelve true o false segun el proceso sea concordante para el el matching localizador - id_compra - id_usuario
     * @param string $localizador_base64
     * @param string $id_usuario
     * @param string $nombre_proceso_defender
     * @return boolean
     * @throws Exception
     */
    function SecurizeCompraByLocalizadorBase64andIdCompraMd5AndIdUsuario($localizador_base64,$id_compra_md5,$id_usuario,$nombre_proceso_defender) {   
        //COMPROBACION USUARIO - LocalizadorCompra
        $flag_exception = false;
        $flag_compra_correcta = false;
        $Compra = new Application_Model_Compra();
        $localizador = base64_decode($localizador_base64);
        try{
            $Compra->loadByLocalizador($localizador);
            $id_compra_compra_md5 = md5($Compra->getId());
            $id_usuario_compra = $Compra->getIdUsuario();
            if($id_usuario == $id_usuario_compra && $id_compra_md5 == $id_compra_compra_md5){
                $flag_compra_correcta = true;
            }else{
                $flag_compra_correcta = false; 
            }
        } catch (Exception $e) {
                $message_exception ='Defender Ticket Incorrecto';
                $flag_exception = true;
        }
        //DEFENDER       
        $IpFunctions = new Eticketsecure_Utils_IpFunctions();
        $real_ip = $IpFunctions->getRealIP();    
        $Defender = new Application_Model_Defender();
        $Defender->loadByNombreProceso($nombre_proceso_defender);
        $ManejadorRelacionDefenderIp = $Defender->startManejadorRelacionDefenderIp($real_ip);
        if(!$flag_compra_correcta){
            $ManejadorRelacionDefenderIp->incrementaIntentos();// el Manejador lanza una Exception  y para la ejecucion. Si el incrementaIntentos() detecta que ya hemos pasado del umbral de proteccion ip   
            $message_exception ='Defender Incorrecto';
            $flag_exception = true;
        }
        
        if($flag_exception) throw new Exception($message_exception); 

        return $flag_compra_correcta;
    }
    /**
     * Este utils utiliza el Defender para protejer el proceso a nivel de ip
     * y aparte devuelve true o false segun el proceso sea concordante para el par id_compra y id_usuario
     * @param string $id_compra
     * @param string $id_usuario
     * @param string $nombre_proceso_defender
     * @return boolean
     * @throws Exception
     */
    function SecurizeCompraByIdCompraAndIdUsuario($id_compra,$id_usuario,$nombre_proceso_defender) {   
        //COMPROBACION USUARIO - COMPRA
        $flag_exception = false;
        $flag_compra_correcta = false;
        $Compra = new Application_Model_Compra();
        try{
            $Compra->load($id_compra);
            $id_usuario_compra = $Compra->getIdUsuario();
            if($id_usuario == $id_usuario_compra){
                $flag_compra_correcta = true;
            }else{
                $flag_compra_correcta = false; 
            }
        } catch (Exception $e) {
                $message_exception ='Defender Incorrecto';
                $flag_exception = true;
        }
        //DEFENDER       
        $IpFunctions = new Eticketsecure_Utils_IpFunctions();
        $real_ip = $IpFunctions->getRealIP();    
        $Defender = new Application_Model_Defender();
        $Defender->loadByNombreProceso($nombre_proceso_defender);
        $ManejadorRelacionDefenderIp = $Defender->startManejadorRelacionDefenderIp($real_ip);
        if(!$flag_compra_correcta){
            $ManejadorRelacionDefenderIp->incrementaIntentos();// el Manejador lanza una Exception  y para la ejecucion. Si el incrementaIntentos() detecta que ya hemos pasado del umbral de proteccion ip   
            $message_exception ='Defender Incorrecto';
            $flag_exception = true;
        }
        
        if($flag_exception) throw new Exception($message_exception); 

        return $flag_compra_correcta;
    }
    
    /**
     * Este utils utiliza el Defender para protejer el proceso a nivel de ip
     * y aparte devuelve true o false segun el proceso sea concordante para el par id_compra email en md5 los dos
     * como parametors url_encode
     * @param string $id_compra_base_64
     * @param string $email_md5
     * @param string $nombre_proceso_defender
     * @return boolean
     * @throws Exception
     */
    function SecurizeCompraByIdCompraBase64AndEmailMd5($id_compra_base_64,$email_md5,$nombre_proceso_defender) {   
        //COMPROBACION USUARIO - COMPRA
        $id_usuario = NULL;
        $flag_exception = false;
        $flag_compra_correcta = false;
        $Compra = new Application_Model_Compra();
        try{
            $Compra->load(base64_decode($id_compra_base_64));
            $id_usuario = $Compra->getIdUsuario();
            $flag_compra_correcta = $Compra->esConcordanteEmailMd5ByIdCompra($email_md5);
        } catch (Exception $e) {
                $message_exception ='Defender Incorrecto';
                $flag_exception = true;
        }
        //DEFENDER       
        $IpFunctions = new Eticketsecure_Utils_IpFunctions();
        $real_ip = $IpFunctions->getRealIP();    
        $Defender = new Application_Model_Defender();
        $Defender->loadByNombreProceso($nombre_proceso_defender);
        $ManejadorRelacionDefenderIp = $Defender->startManejadorRelacionDefenderIp($real_ip);
        if(!$flag_compra_correcta){
            $ManejadorRelacionDefenderIp->incrementaIntentos();// el Manejador lanza una Exception  y para la ejecucion. Si el incrementaIntentos() detecta que ya hemos pasado del umbral de proteccion ip   
            $message_exception ='Defender Incorrecto';
            $flag_exception = true;
        }
        
        if($flag_exception) throw new Exception($message_exception); 

        return $flag_compra_correcta;
    }
   
    /**
     * Este utils utiliza el Defender para protejer el proceso a nivel de ip
     * y aparte devuelve true o false segun el proceso sea concordante para el par id_compra email y ademas la compra este pagada en md5 los dos
     * como parametors url_encode
     * @param string $id_compra_base_64
     * @param string $email_md5
     * @param string $nombre_proceso_defender
     * @return boolean
     * @throws Exception
     */
    function SecurizeCompraPagadaByIdCompraBase64AndEmailMd5($id_compra_base_64,$email_md5,$nombre_proceso_defender){
        $flag_compra_correcta = $this->SecurizeCompraByIdCompraBase64AndEmailMd5($id_compra_base_64,$email_md5,$nombre_proceso_defender);
        $Compra = new Application_Model_Compra();
        $Compra->load(base64_decode($id_compra_base_64));
        if($Compra->getPagada()==1 && $flag_compra_correcta){
            return true;
        }else{// si es incorrecto incrementamos el defender, en el defender del anterior no habrá entrado porque si no hubiera saltado la excepcion, no tenemos que tener miedo de que cuente dos intentos el defender
            $IpFunctions = new Eticketsecure_Utils_IpFunctions();
            $real_ip = $IpFunctions->getRealIP();    
            $Defender = new Application_Model_Defender();
            $Defender->loadByNombreProceso($nombre_proceso_defender);
            $ManejadorRelacionDefenderIp = $Defender->startManejadorRelacionDefenderIp($real_ip);
            $ManejadorRelacionDefenderIp->incrementaIntentos();// el Manejador lanza una Exception  y para la ejecucion. Si el incrementaIntentos() detecta que ya hemos pasado del umbral de proteccion ip   
            return false;  
        }
    }
    
    /**
     * Este utils utiliza el Defender para protejer el proceso de recuperar contraseña de Usuario
     * como parametors url_encode
     * @param string $id_usuario_md5
     * @param string $email_base_64
     * @param string $nombre_proceso_defender
     * @return boolean
     * @throws Exception
     */
    function SecurizeProcessByIdUsuarioMd5AndEmailBase64($id_usuario_md5,$email_base_64,$nombre_proceso_defender) {   
        //COMPROBACION id_usuario - email
        $Usuario = new Application_Model_Usuario();
        $id_usuario = NULL;
        $flag_usuario_correcto = true;
        try{
            $Usuario->loadByEmail(base64_decode($email_base_64));
            $id_usuario = $Usuario->getId();
        }  catch (Exception $e){
            $message_exception = 'Usuario Incorrecto';
            $flag_usuario_correcto = false;
        }
        
        if (md5($id_usuario) != $id_usuario_md5){
            $message_exception = 'Usuario Incorrecto';
            $flag_usuario_correcto = false;
        }


        //DEFENDER 
        if(!$flag_usuario_correcto){// si no es logueado defender si no puede hacer el proceso las veces que quiera
            $IpFunctions = new Eticketsecure_Utils_IpFunctions();
            $real_ip = $IpFunctions->getRealIP();    
            $Defender = new Application_Model_Defender();
            $Defender->loadByNombreProceso($nombre_proceso_defender);
            $ManejadorRelacionDefenderIp = $Defender->startManejadorRelacionDefenderIp($real_ip);
            $ManejadorRelacionDefenderIp->incrementaIntentos();// el Manejador lanza una Exception  y para la ejecucion. Si el incrementaIntentos() detecta que ya hemos pasado del umbral de proteccion ip   
        }
        
        if(!$flag_usuario_correcto) throw new Exception($message_exception); 

        return $flag_usuario_correcto;
    }
    
  


}
?>
