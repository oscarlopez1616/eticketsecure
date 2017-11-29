<?php
class Eticketsecure_Utils_EcommerceAnalytics {
    
    /**
     * Retorna el html capaz de enviar la transaccion a google analytics
     * @param /Application_Model_Compra $Compra $Compra
     * @param string $idioma
     * @return type
     */
    public function getJsToSendTransactionToAnalyticsDesdeCarritoCompra($Compra,$idioma){
        $CarritoCompra = $Compra->getCarrito();
        $pvp = $CarritoCompra->getPvpCarrito();
        $impuestos = round($CarritoCompra->getPvpConIvaCarrito()-$pvp,2);
        $transaccion_arr = array('id'=>$Compra->getId(), 'affiliation'=>'regalo.teatreneu.com','revenue'=>$pvp, 'shipping'=>'0', 'tax'=>$impuestos);
        $items_arr = array();
        $LineasCarritoCompra_arr = $CarritoCompra->getLineaCarritoCompraArr();
        foreach($LineasCarritoCompra_arr as $Agrupador){
            foreach($Agrupador as $LineaCarritoCompra)
            {   
                $id_producto = $LineaCarritoCompra->getIdProducto();
                $tipo_linea_producto = $LineaCarritoCompra->getTipoLineaProducto();
                switch ($tipo_linea_producto) {
                    case "LineaCarritoCompraSesion":
                        $Producto = new Application_Model_Sesion();
                        break;
                    case "LineaCarritoCompraRestauracion":
                        $Producto = new Application_Model_Restauracion();
                        break;
                    case "LineaCarritoCompraPack":
                        $Producto = new Application_Model_Pack();
                        break;
                    case "LineaCarritoCompraRegalo":
                        $Producto = new Application_Model_Regalo();
                        break;
                }
                $Producto->load($id_producto);
                $sku = $Producto->getCodigoReferencia();
                $name = $Producto->getNombre($idioma);
                $id_categoria = $Producto->getIdCategoria();
                $Categoria = new Application_Model_Categoria();
                $Categoria->load($id_categoria);
                $category = $Categoria->getNombreArr($idioma);
                $price = round($Producto->getPvp(),2);
                $quantity = $LineaCarritoCompra->getCantidad();
                $items_arr[] = array('sku'=>$sku, 'name'=>$name, 'category'=>$category, 'price'=>$price, 'quantity'=>$quantity);
            }
        }
        return $this->getJsToSendTransactionToAnalytics($transaccion_arr, $items_arr);
    }
    
    /**
     * Devuele el javascript para enviar la transaccion y sus elementos
     * @param array $transaccion_arr
     * Datos de la Transaccion
     * $trans = array('id'=>'1234', 'affiliation'=>'Acme Clothing',
     *                 'revenue'=>'€11.99', 'shipping'=>'€5', 'tax'=>'€1.29');
     * @param array $items_arr
     * Lista de elementos comprados.
     * $items = array(
     *       array('sku'=>'SDFSDF', 'name'=>'Shoes', 'category'=>'Footwear', 'price'=>'€100', 'quantity'=>'1'),
     *       array('sku'=>'123DSW', 'name'=>'Sandles', 'category'=>'Footwear', 'price'=>'€87', 'quantity'=>'1'),
     *       array('sku'=>'UHDF93', 'name'=>'Socks', 'category'=>'Footwear', 'price'=>'€5.99', 'quantity'=>'2'));
     */
    private function getJsToSendTransactionToAnalytics(&$transaccion_arr,&$items_arr){
        $html = "<script>
                    ga('require', 'ecommerce', 'ecommerce.js');\n";
        $html.= $this->getTransactionJs($transaccion_arr);

        foreach ($items_arr as &$item_arr) {
          $html.= $this->getItemJs($transaccion_arr['id'], $item_arr);
        }
        $html.="\n ga('ecommerce:send');
                </script>"; 
        return $html;
    }

    /**
     * establece la transaccion
     * @param array $trans
     * @return type
     */
    private function getTransactionJs(&$transaccion_arr) {
        $html="ga('ecommerce:addTransaction', {
                'id': '{$transaccion_arr['id']}',
                'affiliation': '{$transaccion_arr['affiliation']}',
                'revenue': '{$transaccion_arr['revenue']}',
                'shipping': '{$transaccion_arr['shipping']}',
                'tax': '{$transaccion_arr['tax']}'
              });";
        return $html;
    }

    /**
     * establece uno de los elementos que forma la transaccion
     * @param int $transaccion_id
     * @param array $item_arr
     * @return type
     */
    private function getItemJs(&$transaccion_id, &$item_arr) {
      $html="ga('ecommerce:addItem', {
            'id': '$transaccion_id',
            'name': '{$item_arr['name']}',
            'sku': '{$item_arr['sku']}',
            'category': '{$item_arr['category']}',
            'price': '{$item_arr['price']}',
            'quantity': '{$item_arr['quantity']}'
          });";
       return $html;
    }
    
}
?>
