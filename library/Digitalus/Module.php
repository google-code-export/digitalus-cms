<?php
require_once 'Digitalus/Module/Service.php';
class Digitalus_Module
{
    const MODULE_KEY = 'module';
    protected $_page;

    /**
     * Constructor
     *
     * @return void
     */
    public function __construct ()
    {
        $this->_page = Digitalus_Builder::getPage();
    }

    /**
     * Get Data from module
     *
     * @param  array      $content
     * @return array|null
     */
    public function getData($content = null)
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

    /**
     * Return an array with the existing extension modules
     *
     * @return array|false
     */
    public static function getModules()
    {
        $modules = Digitalus_Filesystem_Dir::getDirectories(APPLICATION_PATH . '/modules');
        if (is_array($modules)) {
            return $modules;
        } else {
            return false;
        }
    }


}
?>