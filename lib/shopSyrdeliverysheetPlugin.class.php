<?php
/**
 * @package Syrdeliverysheet
 * @author Serge Rodovnichenko <sergerod@gmail.com>
 * @version 1.0.0
 * @copyright (c) 2014, Serge Rodovnichenko
 * @license http://www.webasyst.com/terms/#eula Webasyst
 */
class shopSyrdeliverysheetPlugin extends shopPlugin
{
    /**
     * Because $this->id is protected :(
     */
    const PLUGIN_ID = 'syrdeliverysheet';
    
    /**
     * @var array
     */
    public $template_paths = array();
    
    public function __construct($info)
    {
        parent::__construct($info);
        
        $this->template_paths = array(
            'changed' => waSystem::getInstance()->getDataPath("plugins/". self::PLUGIN_ID ."/DeliverySheet.html"),
            'original' => "{$this->path}/templates/actions/printform/DeliverySheet.html"
        );
    }
    
    public function getTemplatePath()
    {
        foreach($this->template_paths as $filepath) {
            if(file_exists($filepath)) {
                return $filepath;
            }
        }

        throw new waException(_wp("Template file not found."));
    }
    
    public function getTemplate()
    {
        foreach($this->template_paths as $filename) {
            if(file_exists($filename)) {
                return file_get_contents($filename);
            }
        }

        throw new waException(_wp("Template file not found."));
    }
    
    public function isTemplateChanged()
    {
        return file_exists($this->template_paths['changed']);
    }
    
    public function resetTemplate()
    {
        waFiles::delete($this->template_paths['changed']);
    }
    
    public function saveTemplate($data)
    {
        file_put_contents($this->template_paths['changed'], $data);
    }
}
