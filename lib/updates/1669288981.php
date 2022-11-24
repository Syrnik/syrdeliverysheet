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
