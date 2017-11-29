<?php
class Eticketsecure_Utils_SoapRedsysConsultasRealizadasTpv {

    private $_soap_client;
    private $_redsys_arr;
    public function __construct(){
        $this->_redsys_arr = Zend_Registry::get('redsys');
        $this->_soap_client = new Zend_Soap_Client($this->_redsys_arr["url_soap_transaccion_realizadas_tpv_wsdl"],array('soap_version' => SOAP_1_1));
    }

    
    function estadoTransaccionByOrder($order,$merchant_data="") {
        $code=$this->_redsys_arr["merchant_code"];
        $clave =$this->_redsys_arr["clave_comercio"];
        $terminal=$this->_redsys_arr["terminal"];
        $mensaje_inicio = "<Messages>";
        $mensaje='<Version Ds_Version="0.0">
                    <Message>
                    <Monitor>
                        <Ds_MerchantCode>'.$code.'</Ds_MerchantCode>
                        <Ds_Terminal>'.$terminal.'</Ds_Terminal>
                        <Ds_Order>'.$order.'</Ds_Order>
                        <Ds_Merchant_Data>'.$merchant_data.'</Ds_Merchant_Data>
                    </Monitor>
                    </Message>
                </Version>';
        $signature = sha1($mensaje.$clave);  
        $mensaje_fin = "<Signature>".$signature."</Signature>
                    </Messages>";
        $mensaje=$mensaje_inicio.$mensaje.$mensaje_fin;
        $result = $this->_soap_client->procesaMensajeRecibido($mensaje);
        $xml = simplexml_load_string($result);
        $response_xml = $xml->Version->Message->Response;
        $ds_state = (string)$response_xml->Ds_State;
        $flag_pagada = false;
        $message="";
        if($ds_state=="F"){//transaccion finalizada
            $ds_response = (string)$response_xml->Ds_Response; 
            switch ($ds_response) {
                case ( $ds_response>="0000" && $ds_response<="0099"):
                    $message = "Transacción autorizada para pagos y preautorizaciones";
                    $flag_pagada = true;
                    break;
                case "0900":
                    $message = "Transacción autorizada para devoluciones y confirmaciones";
                    break;
                case "101":
                    $message = "Tarjeta caducada";
                    break;
                case "102":
                    $message = "Tarjeta en excepción transitoria o bajo sospecha de fraude";
                    break;
                case ("102" || "9104" ):
                    $message = "Operación no permitida para esa tarjeta o terminal";
                    break;
                case "116":
                    $message = "Disponible insuficiente";
                    break;
                case "118":
                    $message = "Tarjeta no registrada";
                    break;
                case "129":
                    $message = "Código de seguridad (CVV2/CVC2) incorrecto";
                    break;
                case "129":
                    $message = "Tarjeta ajena al servicio";
                    break;
                case "184":
                    $message = "Error en la autenticación del titular";
                    break;
                case "190":
                    $message = "Denegación sin especificar Motivo";
                    break;
                case "191":
                    $message = "Fecha de caducidad errónea";
                    break;
                case "202":
                    $message = "Tarjeta en excepción transitoria o bajo sospecha de fraude con retirada de tarjeta";
                    break;
                case ("912" || "9912"):
                    $message = "Emisor no disponible";
                    break;
                default:
                    $message = "Transacción denegada";
                    break;
            }
        }
        $consulta_arr = array();
        $consulta_arr["flag_pagada"]=$flag_pagada;
        $consulta_arr["message"]=$message;
        return $consulta_arr;
    }
    
}
?>
