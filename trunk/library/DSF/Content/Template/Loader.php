<?php
class DSF_Content_Template_Loader
{
    
    protected $_templatePath;
    const SYSTEM_FOLDER = "system";
    protected $_templates = null;
    
    public function __construct($templatePath = null)
    {
        if($templatePath != null) {
            $this->_templatePath = $templatePath;
        }else{
            $config = Zend_Registry::get('config');
            $this->_templatePath = $config->filepath->contentTemplates;
        }
    }
    
    public function getTemplates()
    {
        $templates = DSF_Filesystem_Dir::getDirectories($this->_templatePath);
        if(is_array($templates)) {
            foreach ($templates as $template) {
                if($template != self::SYSTEM_FOLDER ) {
                    $path = $this->_templatePath  . '/' . $template;
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
    
    public function load($template)
    {
        $arrTemplate = explode('_', $template);
        if(is_array($arrTemplate) && count($arrTemplate) == 2) {
        	$folder = $arrTemplate[0];
        	$template = $arrTemplate[1];
        }else{
        	$folder = null;
        	$template = null;
        }
        return new DSF_Content_Template($folder, $template, $this->_templatePath );
    }
}