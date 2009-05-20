<?php
class Digitalus_Module_Service
{
    protected $_module;
    protected $_pathToModules = './application/modules';
    protected $_serviceFilename = 'service.php';
    protected $_serviceClassName = 'Service';
    protected $_service;
    public function __construct ($module)
    {
        $pathToModule = $this->_pathToModules . '/' . $module;
        if (is_dir($pathToModule)) {
            $pathToService = $pathToModule . '/' . $this->_serviceFilename;
            if (file_exists($pathToService)) {
                require_once $pathToService;
                $className = ucfirst($module) . '_' . $this->_serviceClassName;
                $this->_service = new $className();
            }
        }
        if (! is_object($this->_service)) {
            throw new Zend_Exception('Unable to load service for ' . $module . ' module');
        }
    }
    public function getService ()
    {
        return $this->_service;
    }
    static function load ($module)
    {
        $service = new Digitalus_Module_Service($module);
        return $service->getService();
    }
}
?>