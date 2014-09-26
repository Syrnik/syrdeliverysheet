<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of shopSyrdeliverysheetPluginPrintformDisplay
 *
 * @author serge
 */
class shopSyrdeliverysheetPluginPrintformDisplayAction extends waViewAction
{
    public function execute()
    {
        $order_id = waRequest::request('order_id', null, waRequest::TYPE_INT);
        $order = shopPayment::getOrderData($order_id, $this);        
        $items = $order ? $this->getOrderedItems($order) : array();
        $settings = waSystem::getInstance()->getPlugin(shopSyrdeliverysheetPlugin::PLUGIN_ID)->getSettings();
        
        if(!$order){
            if((waSystem::getInstance()->getEnv() == "backend") && !$order_id) {
                $order = waOrder::factory(array(
                    'contact_id' => $this->getUser()->getId(),
                    'id' => 1,
                    'id_str' => shopHelper::encodeOrderId(1)
                ));
            } else {
                throw new waException("Order not Found", 404);
            }
        }
        
        $this->setTemplate(waSystem::getInstance()->getPlugin(shopSyrdeliverysheetPlugin::PLUGIN_ID)->getTemplatePath());
        
        $this->view->assign(compact("order", "items", "settings"));
    }
    
    /**
     * 
     * @param waOrder $order
     * @return array
     */
    private function getOrderedItems($order)
    {
        if(!$order->items) {
            return array();
        }
        
        $items = $order->items;
        $product_model = new shopProductModel();
        $tax = 0;
        foreach ($items as & $item) {
            $data = $product_model->getById($item['product_id']);
            $item['tax_id'] = ifset($data['tax_id']);
            $item['currency'] = $order->currency;
        }

        unset($item);
        shopTaxes::apply($items, array(
            'billing' => $order->billing_address,
            'shipping' => $order->shipping_address,
                ), $order->currency);

        if ($order->discount) {
            if ($order->total + $order->discount - $order->shipping > 0) {
                $k = 1.0 - ($order->discount) / ($order->total + $order->discount - $order->shipping);
            } else {
                $k = 0;
            }

            foreach ($items as & $item) {
                if ($item['tax_included']) {
                    $item['tax'] = round($k * $item['tax'], 4);
                }
                $item['price'] = round($k * $item['price'], 4);
                $item['total'] = round($k * $item['total'], 4);
            }
            unset($item);
        }
        return $items;
    }
}
