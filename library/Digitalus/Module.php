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
 * @copyright   Copyright (c) 2007 - 2010,  Digitalus Media USA (digitalus-media.com)
 * @license     http://digitalus-media.com/license/new-bsd     New BSD License
 * @version     $Id$
 */

require_once 'Digitalus/Module/Service.php';

class Digitalus_Module
{
    const MODULE_KEY = 'module';

    protected $_page;
    protected $_moduleKey = '';

    /**
     * Constructor
     *
     * @return void
     */
    public function __construct ($moduleKey = null)
    {
        $this->_setModuleKey($moduleKey);
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
        if (is_array($content) && isset($content[$this->_getModuleKey()])) {
            return simplexml_load_string($content[$this->_getModuleKey()]);
        }
        return null;
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
        }
        return false;
    }

    /**
     * Checks whether a specific module is installed
     *
     * @param  string|array  $moduleNames
     * @return boolean
     */
    public static function isInstalled($moduleNames)
    {
        if (is_array($moduleNames)) {
            foreach ($moduleNames as $moduleName) {
                if (!self::isInstalled($moduleName)) {
                    return false;
                }
            }
        } else {
            $moduleName = strtolower($moduleNames);
            $modules = self::getModules();
            if (false == $modules || !in_array($moduleName, $modules)) {
                return false;;
            }
        }
        return true;
    }

    protected function _getModuleKey()
    {
        return $this->_moduleKey;
    }

    protected function _setModuleKey($moduleKey = null)
    {
        if (isset($moduleKey) && !empty($moduleKey) && $moduleKey != '') {
            $this->_moduleKey = $moduleKey;
        } else {
            $this->_moduleKey = self::MODULE_KEY;
        }
        return $this->_moduleKey;
    }
}