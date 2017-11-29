<?php
class Eticketsecure_Utils_TPV{

    private $_redsys_arr;
    
    public function __construct(){
        $this->_redsys_arr = Zend_Registry::get('redsys');
    }

    public function getFormTPVRedsys($CarritoCompra,$gastos_envio,$idioma,$url_comercio,$url_retorno,$url_cancelada){
        $clave=$this->_redsys_arr["clave_comercio"];
        $code=$this->_redsys_arr["merchant_code"];
        $url_tpvv=$this->_redsys_arr["url_tpv"];
        $terminal=$this->_redsys_arr["terminal"];
        $currency=$this->_redsys_arr["currency"];
        $transactionType=$this->_redsys_arr["transation_type"];
        $CarritoCompra->setCompra(1);//este pago es el id_forma_pago = 1   
        $id_compra = $CarritoCompra->getIdCompra();
        $order=date("mds").$id_compra;
        $form='<form id="form-redsys" name="form_compra" action="'.$url_tpvv.'" method="post">';
        $form=$form.'<input type="hidden" name="Ds_Merchant_MerchantCode" value="'.$code.'">';
        $form=$form.'<input type="hidden" name="Ds_Merchant_Currency" value="'.$currency.'">';
        $form=$form.'<input type="hidden" name="Ds_Merchant_Terminal" value="'.$terminal.'">';
        $form=$form.'<input type="hidden" name="Ds_Merchant_TransactionType" value="'.$transactionType.'">';
        $form=$form.'<input type="hidden" name="Ds_Merchant_MerchantURL" value="'.$url_comercio.'">';
        $form=$form.'<input type="hidden" name="Ds_Merchant_UrlOK" value="'.$url_retorno.'/p2/'.urlencode(base64_encode($CarritoCompra->getIdUsuario())).'/">';
        $form=$form.'<input type="hidden" name="Ds_Merchant_UrlKO" value="'.$url_cancelada.'/p2/'.urlencode(base64_encode($CarritoCompra->getIdUsuario())).'">';
        $form=$form.'<input type="hidden" name="Ds_Merchant_Order" value="'.$order.'">';
        $form=$form.'<input type="submit"  value="Enviar">';
        $pvp_iva = $CarritoCompra->getPvpConIvaCarrito();
        $pvp_iva= $pvp_iva*100;//SERMEPA lo hace asi
        $gastos_envio = round($gastos_envio,1)*100;//SEMERPA NECESITA LOS ULTIMOS DOS DIGiTOS = DECIMALES
        $amount = $pvp_iva+ $gastos_envio;
        $message = $amount.$order.$code.$currency.$transactionType.$url_comercio.$clave;
        $signature = sha1($message);
        $form=$form.'<input type="hidden" name="Ds_Merchant_MerchantSignature" value="'.$signature.'">';

       $form=$form.'<input type="hidden" name="Ds_Merchant_Amount" value="'.$amount.'">';
       switch ($idioma) {
              case "es":
                  $Ds_Merchant_ConsumerLanguage = "001";
                  break;
              case "eu":
                  $Ds_Merchant_ConsumerLanguage = "013";
                  break;
              case "gl":
                  $Ds_Merchant_ConsumerLanguage = "012";
                  break;
              case "ca":
                  $Ds_Merchant_ConsumerLanguage = "003";
                  break;
              case "en":
                  $Ds_Merchant_ConsumerLanguage = "002";
                  break;
              case "fr":
                  $Ds_Merchant_ConsumerLanguage = "004";
                  break;
              case "po":
                  $Ds_Merchant_ConsumerLanguage = "009";
                  break;
              case "it":
                  $Ds_Merchant_ConsumerLanguage = "007";
                  break;  

       }
       $form=$form.'<input type="hidden" name="Ds_Merchant_ConsumerLanguage" value="'.$Ds_Merchant_ConsumerLanguage.'">';
       $form=$form.'</form>'; 
       return $form;
    }

    public function getFormTPVPayPal($id_session,$id_usuario,$gastos_envio,$idioma,$email,$url_retorno,$url_cancelada){//esta mal hecho
        
         $CarritoCompra= new Application_Model_CarritoCompra($id_session, $id_usuario);
         $CarritoCompra->load();
         $CarritoCompra->setCompra(2);//este pago es el id_forma_pago = 2
         $form_paypal='<form id="form-paypal" action="https://www.paypal.com/es/cgi-bin/webscr" method="post"><input type="hidden" name="cmd" value="_cart"><input type="hidden" name="upload" value="1">';
         $form_paypal=$form_paypal.'<input type="hidden" name="business" value="'.$email.'">';
         $LineasCarritoCompra_arr = $CarritoCompra->getLineaCarritoCompraArr();
         $i=1;
         foreach($LineasCarritoCompra_arr as $Agrupador){
            foreach($Agrupador as $LineaCarritoCompra)
            {   
                $nombre_producto=$LineaCarritoCompra->getNombreProducto($idioma); 
                $form_paypal=$form_paypal.'<input type="hidden" name="item_name_'.$i.'" value="'.$nombre_producto.'">';
                $pvp_iva = round($LineaCarritoCompra->getPvp()+$LineaCarritoCompra->getIva(),2);
                $form_paypal=$form_paypal.'<input type="hidden" name="amount_'.$i.'" value="'.$pvp_iva.'">';
                $i=$i++;
            }
         }
         $form_paypal=$form_paypal.'<input type="hidden" name="shipping_1" value="'.$gastos_envio.'">
<input type="hidden" name="currency_code" value="EUR">
<input type="image" src="http://www.paypal.com/es_ES/i/btn/x-click-but01.gif" name="submit" alt="Realice pagos con PayPal: es rápido, gratis y seguro">
</form>';
         return $form_paypal;
    }
    
}
?>
