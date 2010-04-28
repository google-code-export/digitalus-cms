<?php
/**
 * Digitalus CMS
 *
 * LICENSE
 *
 * This source file is subject to the new BSD license that is bundled
 * with this package in the file LICENSE.txt.
 * It is also available through the world-wide-web at this URL:
 * http://digitalus-media.com/license/new-bsd
 * If you did not receive a copy of the license and are unable to
 * obtain it through the world-wide-web, please send an email
 * to info@digitalus-media.com so we can send you a copy immediately.
 *
 * @author      Forresst Lyman
 * @category    Digitalus CMS
 * @package     Digitalus
 * @subpackage  Digitalus_Module
 * @copyright   Copyright (c) 2007 - 2010,  Digitalus Media USA (digitalus-media.com)
 * @license     http://digitalus-media.com/license/new-bsd     New BSD License
 * @version     $Id: Property.php Tue Dec 25 21:17:02 EST 2007 21:17:02 forrest lyman $
 */

class Digitalus_Module_Property
{
    public function __construct()
    {}

    public static function load($module)
    {
        $front = Zend_Controller_Front::getInstance();
        $modules = $front->getParam('cmsModules');
        $filepath = $front->getParam('pathToModules');

        if (isset($modules[$module])) {
            $propertiesFile = $filepath . '/' . $modules[$module] . '/properties.xml';
            if (file_exists($propertiesFile)) {
                return new Zend_Config_Xml($propertiesFile);
            }
        }

        return null;
    }
}