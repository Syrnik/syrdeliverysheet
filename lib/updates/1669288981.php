<?php
/**
 * @author Serge Rodovnichenko <serge@syrnik.com>
 * @copyright Serge Rodovnichenko, 2022
 * @license Webasyst
 */

/**
 * @var shopPrintformPlugin $this
 */

//changed
$source = wa()->getDataPath('plugins/' . $this->id . '/DeliverySheet.html', false, 'shop');
if (file_exists($source)) {
    $target = wa()->getDataPath('plugins/' . $this->id . '/template.html', false, 'shop');
    waFiles::move($source, $target);
}

waFiles::delete($this->path . '/templates/actions/printform/DeliverySheet.html');
waFiles::delete($this->path . '/README.md');
waFiles::delete($this->path . '/contributors.txt');
waFiles::delete($this->path . '/img/logo.png');
waFiles::delete($this->path . '/img/logo.xcf');
waFiles::delete($this->path . '/img/syrdeliverysheet-screenshot-ru-1.png');
waFiles::delete($this->path . '/img/syrdeliverysheet-screenshot-ru-2.png');
waFiles::delete($this->path . '/img/syrdeliverysheet-screenshot-ru-3.png');
