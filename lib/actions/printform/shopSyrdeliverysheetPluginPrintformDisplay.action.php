<?php
/**
 * @package Syrdeliverysheet
 * @author Serge Rodovnichenko <sergerod@gmail.com>
 * @copyright (c) 2014-2023, Serge Rodovnichenko
 * @license http://www.webasyst.com/terms/#eula Webasyst
 */

/**
 * Description of shopSyrdeliverysheetPluginPrintformDisplay
 *
 * @author serge
 */
class shopSyrdeliverysheetPluginPrintformDisplayAction extends waViewAction
{
    public const PLUGIN_ID = 'syrdeliverysheet';

    /**
     * @return void
     * @throws waException
     * @throws waRightsException
     */
    public function execute(): void
    {
        if (!wa()->getUser()->getRights('shop', 'orders')) {
            throw new waRightsException('Access denied');
        }

        $order_id = waRequest::request('order_id', null, waRequest::TYPE_INT);

        /** @var shopSyrdeliverysheetPlugin $plugin */
        $plugin = wa('shop')->getPlugin('syrdeliverysheet');

        $this->view->assign('content', $plugin->renderPrintform($order_id));
    }
}
