<?php
require_once '/DSF/Module/Service.php';
class DSF_Module
{
    const MODULE_KEY = 'module';
    protected $_page;
    public function __construct ()
    {
        $this->_page = DSF_Builder::getPage();
    }
    public function getData ($content = null)
    {
        if ($content == null && isset($this->_page)) {
            $content = $this->_page->getContent();
        }
        if (is_array($content) && isset($content[self::MODULE_KEY])) {
            return simplexml_load_string($content[self::MODULE_KEY]);
        } else {
            return null;
        }
    }
}
?>