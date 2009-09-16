<?php
class Digitalus_Module_Property
{
    public function __construct()
    {}

    public static function load($module)
    {
        $front = Zend_Controller_Front::getInstance();
        $modules = $front->getParam('cmsModules');
        $filepath = $front->getParam("pathToModules");

        if (isset($modules[$module])) {
            $propertiesFile = $filepath . '/' . $modules[$module] . '/properties.xml';
            if (file_exists($propertiesFile)) {
                return new Zend_Config_Xml($propertiesFile);
            }
        }

        return null;
    }
}