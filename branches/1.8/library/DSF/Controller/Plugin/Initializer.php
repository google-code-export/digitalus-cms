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
class DSF_Controller_Plugin_Initializer extends Zend_Controller_Plugin_Abstract
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
     * @param  string       $env
     * @param  string|null  $root
     * @return void
     */
    public function __construct()
    {
        //get front controller instance
        $this->_front = Zend_Controller_Front::getInstance();

        //get request object
        $this->_request = $this->_front->getRequest();
    }

    /**
     * Pre dispatch
     *
     * @param   Zend_Controller_Request_Abstract  $request
     * @return  void
     */
    public function preDispatch(Zend_Controller_Request_Abstract $request)
    {
#Zend_Debug::dump($this);
#Zend_Debug::dump($request);
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
        $uri = new DSF_Uri();
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
        if (!$this->_request->isXmlHttpRequest()) {
            // Get cache object
            $cache = Zend_Registry::get('cache');
            Zend_Translate::setCache($cache);

            $module     = $this->_request->getModuleName();
            $controller = $this->_request->getControllerName();
            // Add translation file depending on current module ('public' or 'admin')
            if ('public' != $module && 'public' != $controller) {
                $end = 'back';
            } else {
                $end = 'front';
            }
            $adapter = new Zend_Translate(
                'csv',
                $config->language->path . '/' . $languageFiles[$key] . '.' . $end . '.csv',
                $key
            );
            Zend_Registry::set('Zend_Translate', $adapter);

            // Module translations (are NOT separated into  'back' and 'front')
            if ($modules = $this->_getModules()) {
                foreach ($modules as $module) {
                    $this->_addTranslation(APPLICATION_PATH . '/modules/' . $module . '/data/language/' . $languageFiles[$key] . '.csv', $key);
                }
            }
        }
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
                DSF_View_RegisterHelpers::register($this->view);
                $this->view->setScriptPath($config->filepath->adminViews);

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