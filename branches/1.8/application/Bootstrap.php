<?php
/**
 * Kurze Beschreibung der Datei
 *
 * Lange Beschreibung der Datei (wenn vorhanden)...
 *
 * LICENSE: Einige Lizenz Informationen
 *
 * @copyright  2009 Digitalus Media
 * @license    http://framework.zend.com/license   BSD License
 * @version    $Id:$
 * @link       http://www.digitaluscms.com
 * @since      Release 1.8.0
*/

/**
 * Kurze Beschreibung für die Klasse
 *
 * Lange Beschreibung für die Klasse (wenn vorhanden)...
 *
 * @copyright  2009 Digitalus Media
 * @license    http://framework.zend.com/license   BSD License
 * @version    Release: @package_version@
 * @link       http://www.digitaluscms.com
 * @since      Release 1.8.0
 * @deprecated
 */
class Bootstrap extends Zend_Application_Bootstrap_Bootstrap
{
    protected function _initAutoload()
    {
        // Ensure front controller instance is present
        $this->bootstrap('frontController');
        // Get config resource
        $this->_front = $this->getResource('frontController');

        $autoLoader = new Zend_Application_Module_Autoloader(array(
            'namespace' => '',
            'basePath'  => APPLICATION_PATH)
        );
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

        //add config to the registry so it is available sitewide
        $registry = Zend_Registry::getInstance();
        $registry->set('config', $config);
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
           'lifetime' => 7200, // cache lifetime of 2 hours
           'automatic_serialization' => true,
        );
        $backendOptions = array(
            'cache_dir' => BASE_PATH . '/cache/', // Directory where to put the cache files
        );
        // getting a Zend_Cache_Core object
        $cache = Zend_Cache::factory('Core', 'File', $frontendOptions, $backendOptions);
        Zend_Registry::set('cache', $cache);
        return $cache;
    }


    /**
     * Initialize data bases
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

        // Set default locale
        setlocale(LC_ALL, $config->language->defaultLocale);
        $locale = new Zend_Locale($config->language->defaultLocale);

        // Set default timezone
        $timezone = $config->defaultTimezone;
        date_default_timezone_set($timezone);

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
        $helperDirs = DSF_Filesystem_Dir::getDirectories(APPLICATION_PATH . '/helpers');
        if (is_array($helperDirs)) {
            foreach ($helperDirs as $dir) {
                $view->addHelperPath(APPLICATION_PATH . '/helpers/' . $dir, 'DSF_View_Helper_' . ucfirst($dir));
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

    /**
     * Initialize the request object
     *
     * @return Zend_Request_...???
     */
    protected function initRequest(array $options = array())
    {
        $this->bootstrap('FrontController');
        $request = new Zend_Controller_Request_Http();
        $this->_front->setRequest($request);
        return $request;
    }

/* ************************************************************************** */

    /**
     * Return an array with the existing extension modules
     *
     * @return array|false
     */
    protected function _getModules()
    {
        $modules = DSF_Filesystem_Dir::getDirectories(APPLICATION_PATH . '/modules');
        if (is_array($modules)) {
            return $modules;
        } else {
            return false;
        }
    }

}
?>