<?php
/**
 * Bootstrap of Digitalus CMS
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
 * @copyright  Copyright (c) 2007 - 2009,  Digitalus Media USA (digitalus-media.com)
 * @license    http://digitalus-media.com/license/new-bsd     New BSD License
 * @version    $Id:$
 * @link       http://www.digitaluscms.com
 * @since      Release 1.8.0
 */

/**
 * Bootstrap of Digitalus CMS
 *
 * @copyright  Copyright (c) 2007 - 2009,  Digitalus Media USA (digitalus-media.com)
 * @license    http://digitalus-media.com/license/new-bsd     New BSD License
 * @version    Release: @package_version@
 * @link       http://www.digitaluscms.com
 * @since      Release 1.8.0
 */
class Bootstrap extends Zend_Application_Bootstrap_Bootstrap
{
    /**
     * Initialize the autoloader
     *
     * @return Zend_Application_Module_Autoloader
     */
    protected function _initAutoload()
    {
        // Ensure front controller instance is present
        $this->bootstrap('frontController');
        // Get frontController resource
        $this->_front = $this->getResource('frontController');

        // Add autoloader empty namespace
        $autoLoader = new Zend_Application_Module_Autoloader(array(
            'namespace' => '',
            'basePath'  => APPLICATION_PATH)
        );
        // Return it, so that it can be stored by the bootstrap
        return $autoLoader;
    }

    /**
     * Initialize the local php configuration
     *
     * @return void
     */
    protected function _initPhpConfig()
    {
    }

    /**
     * Initialize the site configuration
     *
     * @return Zend_Config_Xml
     */
    protected function _initConfig()
    {
        // Retrieve configuration from file
        $config = new Zend_Config_Xml(APPLICATION_PATH . '/data/config.xml', APPLICATION_ENV);

        // Add config to the registry so it is available sitewide
        $registry = Zend_Registry::getInstance();
        $registry->set('config', $config);
        // Return it, so that it can be stored by the bootstrap
        return $config;
    }

    /**
     * Initialize the cache
     *
     * @return Zend_Cache_Core
     */
    protected function _initCache()
    {
        // Cache options
        $frontendOptions = array(
           'lifetime' => 1200,                      // Cache lifetime of 20 minutes
           'automatic_serialization' => true,
        );
        $backendOptions = array(
            'lifetime' => 3600,                     // Cache lifetime of 1 hour
            'cache_dir' => BASE_PATH . '/cache/',   // Directory where to put the cache files
        );
        // Get a Zend_Cache_Core object
        $cache = Zend_Cache::factory('Core', 'File', $frontendOptions, $backendOptions);
        Zend_Registry::set('cache', $cache);
        // Return it, so that it can be stored by the bootstrap
        return $cache;
    }

    /**
     * Initialize data base
     *
     * @return Zend_Db_Adapter_...???
     */
    protected function _initDb()
    {
        $this->bootstrap('config');
        // Get config resource
        $config = $this->getResource('config');

#        $resource = $this->getPluginResource('db');
#        $db = $resource->getDbAdapter();
#        $db = $this->getResource('db');
        // Setup database
        $db = Zend_Db::factory($config->database->adapter, $config->database->toArray());
        $db->setFetchMode(Zend_Db::FETCH_OBJ);
        $db->query("SET NAMES 'utf8'");
        $db->query("SET CHARACTER SET 'utf8'");
        Zend_Db_Table::setDefaultAdapter($db);
        // Return it, so that it can be stored by the bootstrap
        return $db;
    }

    /**
     * Initialize the site settings
     *
     * @return stdObject
     */
    protected function _initSiteSettings()
    {
        $siteSettings = new Model_SiteSettings();
        Zend_Registry::set('siteSettings', $siteSettings);
        // Return it, so that it can be stored by the bootstrap
        return $siteSettings;
    }

    /**
     * Initialize the site's locale
     *
     * @return Zend_Locale
     */
    protected function _initLocale()
    {
        $this->bootstrap('config');
        // Get config resource
        $config = $this->getResource('config');

        $this->bootstrap('cache');
        // Get cache object
        $cache = $this->getResource('cache');
        Zend_Locale::setCache($cache);

        $this->bootstrap('siteSettings');
        // Get siteSettings object
        $siteSettings = $this->getResource('siteSettings');

        // Set default locale
        setlocale(LC_ALL, $config->language->defaultLocale);
        $locale = new Zend_Locale($config->language->defaultLocale);

        // Set default timezone
        $timezone = $siteSettings->get('default_timezone');
        date_default_timezone_set($timezone);
        // Return it, so that it can be stored by the bootstrap
        return $locale;
    }

    /**
     * Initialize the view
     *
     * @return Zend_View
     */
    protected function _initView()
    {
        // Initialize view
        $view = new Zend_View();

        $this->bootstrap('siteSettings');
        // Get settings resource
        $settings = $this->getResource('siteSettings');

        // Set doctype and charset
        $view->doctype($settings->get('doc_type'));
        $view->placeholder('charset')->set($settings->get('default_charset'));

        // Add the view to the ViewRenderer
        $viewRenderer = Zend_Controller_Action_HelperBroker::getStaticHelper(
            'ViewRenderer'
        );
        $viewRenderer->setView($view);

        // Load digitalus helpers
        $helperDirs = Digitalus_Filesystem_Dir::getDirectories(APPLICATION_PATH . '/helpers');
        if (is_array($helperDirs)) {
            foreach ($helperDirs as $dir) {
                $view->addHelperPath(APPLICATION_PATH . '/helpers/' . $dir, 'Digitalus_View_Helper_' . ucfirst($dir));
            }
        }
        $view->baseUrl = $this->_front->getBaseUrl();
        // Return it, so that it can be stored by the bootstrap
        return $view;
    }

    /**
     * Initialize the controllers
     *
     * @return void
     */
    protected function _initControllers()
    {
        // Setup core cms modules
        $this->_front->addControllerDirectory(APPLICATION_PATH . '/admin/controllers', 'admin');
        $this->_front->addControllerDirectory(APPLICATION_PATH . '/public/controllers', 'public');

        // Setup extension modules
        $this->_front->setParam('pathToModules', APPLICATION_PATH . '/modules');
        $cmsModules = null;
        if ($modules = $this->_getModules()) {
            foreach ($modules as $module) {
                $cmsModules['mod_' . $module] = $module;
                $this->_front->addControllerDirectory(APPLICATION_PATH . '/modules/' . $module . '/controllers', 'mod_' . $module);
            }
        }
        if (is_array($cmsModules)) {
            $this->_front->setParam('cmsModules', $cmsModules);
        }
    }

/* ************************************************************************** */

    /**
     * Return an array with the existing extension modules
     *
     * @return array|false
     */
    protected function _getModules()
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