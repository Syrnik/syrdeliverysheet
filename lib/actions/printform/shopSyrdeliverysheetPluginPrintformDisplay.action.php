<?php
/**
 * @package Syrdeliverysheet
 * @author Serge Rodovnichenko <sergerod@gmail.com>
 * @version 1.1.0
 * @copyright (c) 2014, Serge Rodovnichenko
 * @license http://www.webasyst.com/terms/#eula Webasyst
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
        $order = shopPayment::getOrderData($order_id);
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
        $this->getResponse()->addJs('plugins/syrdeliverysheet/js/printform.js', 'shop');
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

        $OrderItem = new shopOrderItemsModel();

        $items = $order->items;

        $ordered_items = $OrderItem->select('*')
            ->where('order_id=:order_id AND type=:type', array('order_id' => $order->id, 'type' => 'product'))
            ->fetchAll('id', TRUE);

        $product_model = new shopProductModel();

        foreach ($items as &$item) {
            if ($item['type'] == 'product' && isset($ordered_items[$item['id']])) {
                $item['sku_id'] = $ordered_items[$item['id']]['sku_id'];
            }
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
