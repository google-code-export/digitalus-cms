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
 * @copyright  Copyright (c) 2007 - 2010,  Digitalus Media USA (digitalus-media.com)
 * @license    http://digitalus-media.com/license/new-bsd     New BSD License
 * @version    $Id$
 * @link       http://www.digitaluscms.com
 * @since      Release 1.8.0
 */

/** Zend_Controller_Plugin_Abstract */
require_once 'Zend/Controller/Plugin/Abstract.php';

/**
 * Initializer of Digitalus CMS
 *
 * @copyright  Copyright (c) 2007 - 2010,  Digitalus Media USA (digitalus-media.com)
 * @license    http://digitalus-media.com/license/new-bsd     New BSD License
 * @category   Digitalus CMS
 * @package    Digitalus_Core_Library
 * @version    Release: @package_version@
 * @link       http://www.digitaluscms.com
 * @since      Release 1.8.0
 */
class Digitalus_Controller_Plugin_Initializer extends Zend_Controller_Plugin_Abstract
{
    /**
     * @var Zend_Controller_Front
     */
    protected $_front;

    /**
     * @var Zend_Controller_Request
     */
    protected $_request;

    /**
     * Constructor
     *
     * Initialize environment, root path, and configuration.
     *
     * @param  string      $env
     * @param  string|null $root
     * @return void
     */
    public function __construct()
    {
        // Get front controller instance
        $this->_front = Zend_Controller_Front::getInstance();

        // Get request object
        $this->_request = $this->_front->getRequest();
    }

    /**
     * Pre dispatch
     *
     * @param  Zend_Controller_Request_Abstract $request
     * @return void
     */
    public function preDispatch(Zend_Controller_Request_Abstract $request)
    {
        $this->_initCmsRouter();
        $this->_initTranslation();
        $this->_initInterface();
    }

    /**
     * This function overrides the Zend Router if the page exists in the cms
     *
     * @return void
     */
    protected function _initCmsRouter()
    {
        $uri = new Digitalus_Uri();
        $page = new Model_Page();
        if ($page->pageExists($uri) || 'public' == $this->_request->getModuleName()) {
            $this->_request->setModuleName('public');
            $this->_request->setControllerName('index');
            $this->_request->setActionName('index');
        }
    }

    /**
     * Initialize translations
     *
     * @return Zend_Translate
     */
    protected function _initTranslation()
    {
        // Get site settings
        $settings = Zend_Registry::get('siteSettings');

        // Get site config
        $config = Zend_Registry::get('config');

        $language = $settings->get('admin_language');
        if (!empty($language)) {
            $key = $language;
        } else {
            $key = $config->language->defaultLocale;
        }

        $languageFiles = $config->language->translations->toArray();
#        if (!$this->_request->isXmlHttpRequest()) {
            // Get cache object
            $cache = Zend_Registry::get('cache');
            Zend_Translate::setCache($cache);

            $module     = $this->_request->getModuleName();
            $controller = $this->_request->getControllerName();
            // Add translation file depending on current module ('public' or 'admin')
            if ('public' == $module || 'public' == $controller) {
                $end = 'front';
            } else {
                $end = 'back';
            }
            $adapter = new Zend_Translate(
                'csv',
                $config->language->path . '/' . $end . '/' . $languageFiles[$key] . '.' . $end . '.csv',
                $key,
                array('disableNotices' => true)
            );
            Zend_Registry::set('Zend_Translate', $adapter);

            // Module translations (are NOT separated into  'back' and 'front')
            if ($modules = Digitalus_Module::getModules()) {
                foreach ($modules as $module) {
                    $this->_addTranslation(APPLICATION_PATH . '/modules/' . $module . '/data/language/' . $languageFiles[$key] . '.csv', $key);
                }
            }
 #       }
        return $adapter;
    }

    /**
     * Initialize the admin interface
     *
     * @return void
     */
    protected function _initInterface()
    {
        if (!$this->_request->isXmlHttpRequest()) {
            //load the module, controller, and action for reference
            $module     = $this->_request->getModuleName();
            $controller = $this->_request->getControllerName();

            if ('public' != $module && 'public' != $controller) {
                // Get config
                $config = Zend_Registry::get('config');

                // Setup layout
                $options = array(
                    'layout'     => $config->design->adminLayout,
                    'layoutPath' => $config->design->adminLayoutFolder,
                    'contentKey' => 'form',           // ignored when MVC not used
                );
                $this->layout = Zend_Layout::startMvc($options);
                $this->view = $this->layout->getView();

                // Load the common helpers
                Digitalus_View_RegisterHelpers::register($this->view);
                $this->view->setScriptPath($config->filepath->adminViews);

                // add helpers
                $this->view->addHelperPath('ZendX/JQuery/View/Helper', 'ZendX_JQuery_View_Helper');
                $this->view->jQuery()->setLocalPath($this->view->getBaseUrl()   . '/scripts/jquery/jquery-1.4.2.min.js');
                $this->view->jQuery()->setUiLocalPath($this->view->getBaseUrl() . '/scripts/jquery/jquery-ui-1.7.2.custom.min.js');
                $this->view->jQuery()->addStylesheet($this->view->getBaseUrl()  . '/scripts/jquery/ui-theme/jquery-ui-1.7.2.custom.css');
                $this->view->jQuery()->enable();
                $this->view->jQUery()->uiEnable();

                // Page links
                $this->view->toolbarLinks = array();
            }
        }
    }

/* ************************************************************************** */

    /**
     * Add a translation to the Zend_Translate Adapter stored in Zend_Registry
     *
     * @param  string $languagePath Path to the language file
     * @param  string $lang         Locale key for translation
     * @return void
     */
    protected function _addTranslation($languagePath, $lang)
    {
        if (is_file($languagePath)) {
            $adapter = Zend_Registry::get('Zend_Translate');
            $adapter->addTranslation($languagePath, $lang);
        }
    }
}