<?php
require_once 'Zend/Controller/Plugin/Abstract.php';
require_once 'Zend/Controller/Front.php';
require_once 'Zend/Controller/Request/Abstract.php';
require_once 'Zend/Controller/Action/HelperBroker.php';

class Initializer extends Zend_Controller_Plugin_Abstract
{
    /**
     * @var Zend_Config
     */
    protected $_config;

    /**
     * @var string Current environment
     */
    protected $_env;

    /**
     * @var Zend_Controller_Front
     */
    protected $_front;

    /**
     * @var string Path to application root
     */
    protected $_root;

    /**
     * Constructor
     *
     * Initialize environment, root path, and configuration.
     * 
     * @param  string $env 
     * @param  string|null $root 
     * @return void
     */
    public function __construct($env, $root = null)
    {
        $this->_setEnv($env);
        if (null === $root) {
            $root = realpath(dirname(__FILE__) . '/../');
        }
        $this->_root = $root;

        $this->initPhpConfig();
        
        $this->_front = Zend_Controller_Front::getInstance();
        
        // set the test environment parameters
        if ($env == 'test') {
			// Enable all errors so we'll know when something goes wrong. 
			error_reporting(E_ALL | E_STRICT);  
			ini_set('display_startup_errors', 1);  
			ini_set('display_errors', 1); 

			$this->_front->throwExceptions(true);  
        }
    }

    /**
     * Initialize environment
     * 
     * @param  string $env 
     * @return void
     */
    protected function _setEnv($env) 
    {
		$this->_env = $env;    	
    }
    

    /**
     * Sets the local php configuration
     * 
     * @return void
     */
    public function initPhpConfig()
    {
    	
    }
    
    /**
     * Route startup
     * 
     * @return void
     */
    public function routeStartup(Zend_Controller_Request_Abstract $request)
    {
    	$this->initConfig();
    	$this->initLocale();
    	$this->initCache();
		$this->initDb();   	
    	$this->initView();
    	$this->initControllers();
   }
   
   public function preDispatch(Zend_Controller_Request_Abstract $request)
    {
        $this->initCmsRouter();       
    }
   
    
	public function initConfig()
	{
		$this->_config = new Zend_Config_Xml($this->_root .  '/application/data/config.xml', $this->_env);
		//add config to the registry so it is available sitewide
		$registry = Zend_Registry::getInstance();
		$registry->set('config', $this->_config);
	}
	
	public function initLocale()
	{
        //set defualt locale
        setlocale(LC_ALL, $this->_config->language->defaultLocale);
        $locale = new Zend_Locale($this->_config->language->defaultLocale); 
        
        //translations
        $languageFiles = $this->_config->language->translations->toArray();
        $language =  $this->_config->language->defaultLocale;
        $adapter = new Zend_Translate('csv',$this->_config->language->path . '/' . $languageFiles[$language] . '.csv',$language);
        Zend_Registry::set('Zend_Translate', $adapter);
	}
	
	/**
	 * Initialize data bases
	 * 
	 * @return void
	 */
	public function initDb()
	{
        // setup database
        $db = Zend_Db::factory($this->_config->database->adapter, $this->_config->database->toArray());
        $db->setFetchMode(Zend_Db::FETCH_OBJ);
        Zend_Db_Table::setDefaultAdapter($db);
	}
	
	public function initCache()
	{
	    //cache options
        $frontendOptions = array(
           'lifetime' => 7200, // cache lifetime of 2 hours 
           'automatic_serialization' => true
        );
        
        $backendOptions = array(
            'cache_dir' => $this->_root .  '/cache/' // Directory where to put the cache files
        );
        
        // getting a Zend_Cache_Core object
        $cache = Zend_Cache::factory('Core', 'File', $frontendOptions, $backendOptions);
        Zend_Registry::set('cache',$cache);
	}
	
	public function initView()
	{
        $viewRenderer = Zend_Controller_Action_HelperBroker::getStaticHelper('viewRenderer');
        if (null === $viewRenderer->view) {
            $viewRenderer->initView();
        }
        $view = $viewRenderer->view;
        
        // load digitalus helpers
	    $helperDirs = DSF_Filesystem_Dir::getDirectories($this->_root .  '/application/helpers');
    	if(is_array($helperDirs))
    	{
    		foreach ($helperDirs as $dir) {
    			$view->addHelperPath($this->_root .  '/application/helpers/' . $dir, 'DSF_View_Helper_' . ucfirst($dir));
    		}
    	}
	}	
	
	public function initControllers()
	{
        //setup core cms modules
        $this->_front->addControllerDirectory($this->_root .  '/application/admin/controllers', 'admin');
        $this->_front->addControllerDirectory($this->_root .  '/application/front/controllers', 'cmsFront');
        $this->_front->addControllerDirectory($this->_root .  '/application/public/controllers', 'public');
        
        //setup extension modules
        $extensions = DSF_Filesystem_Dir::getDirectories($this->_root .  '/application/modules');
        if(is_array($extensions))
        {
        	foreach ($extensions as $extension)
        	{
        		$this->_front->addControllerDirectory($this->_root .  '/application/modules/' . $extension . '/controllers', 'mod_' . $extension);
        	}
        }
        
        $this->_front->setDefaultModule('public');
	}  
	
	/**
	 * this function overrides the Zend Router if the page exists in the cms
	 *
	 */
	public function initCmsRouter()
	{
	    $uri = new DSF_Uri();
	    $page = new Page();
	    if($page->pageExists($uri)) {
	        $request = $this->_front->getRequest();
	        $request->setModuleName('public');
	        $request->setControllerName('index');
	        $request->setActionName('index');
	    }
	}
	

}

?>