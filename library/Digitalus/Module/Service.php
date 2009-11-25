<?php
class Digitalus_Module_Service extends Digitalus_Abstract
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
            require_once 'Digitalus/Module/Exception.php';
            throw new Digitalus_Module_Exception($this->view->getTranslation('Unable to load service for this module') . ': ' . $module);
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