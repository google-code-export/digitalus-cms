<?php
class DSF_Content_Template_Loader
{
    
    const TEMPLATE_PATH = './application/contentTemplates';
    const SYSTEM_FOLDER = "system";
    protected $_templates = null;
    
    public function __construct()
    {
        
    }
    
    public function getTemplates()
    {
        $templates = DSF_Filesystem_Dir::getDirectories(self::TEMPLATE_PATH);
        if(is_array($templates)) {
            foreach ($templates as $template) {
                if($template != self::SYSTEM_FOLDER ) {
                    $path = self::TEMPLATE_PATH  . '/' . $template;
                    $subtemplates = DSF_Filesystem_Dir::getDirectories($path);
                    if(is_array($subtemplates)) {
                        foreach ($subtemplates as $subtemplate) {
                            $this->_templates[$template . '_' . $subtemplate] = ucwords($template . ' ' . $subtemplate);
                        }
                    }
                }
            }
        }
        return $this->_templates;
    }
    
    static function load($template)
    {
        $arrTemplate = explode('_', $template);
        if(is_array($arrTemplate) && count($arrTemplate) == 2) {
        	$folder = $arrTemplate[0];
        	$template = $arrTemplate[1];
        }else{
        	$folder = null;
        	$template = null;
        }
        return new DSF_Content_Template($folder, $template, self::TEMPLATE_PATH );
    }
}