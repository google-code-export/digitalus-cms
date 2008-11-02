<?php
date_default_timezone_set('America/New_York');
set_include_path('.' . PATH_SEPARATOR . get_include_path() 
. PATH_SEPARATOR . '../library'
. PATH_SEPARATOR . '../application/modules'
. PATH_SEPARATOR . '../application/models');

require_once 'Zend/Loader.php';
Zend_Loader::registerAutoload(); 

error_reporting(E_ALL);
//setup session
Zend_Session::start();

// load configuration
//testing
$config = new Zend_Config_Xml('../application/data/config.xml', 'testing');

//production
//$config = new Zend_Config_Xml('./application/data/config.xml', 'DSF');

//set defualt locale
setlocale(LC_ALL, $config->language->defaultLocale);
$locale = new Zend_Locale(); 


//register constants
if(isset($config->constants)){
    $constants = $config->constants->toArray();
    if(is_array($constants)){
        foreach ($constants as $k => $v){
            $key = strtoupper('DSF_' . $k);
            define($key, $v);
        }
    }
}

// setup database
$db = Zend_Db::factory($config->database->adapter, $config->database->toArray());
$db->setFetchMode(Zend_Db::FETCH_OBJ);
Zend_Db_Table::setDefaultAdapter($db);

//get an instance of the registry
$registry = Zend_Registry::getInstance();

//save the config information into the registry
$registry->set('config', $config);
$registry->set('post',$_POST);

//cache options
$frontendOptions = array(
   'lifetime' => 7200, // cache lifetime of 2 hours 
   'automatic_serialization' => true
);

$backendOptions = array(
    'cache_dir' => '../cache/' // Directory where to put the cache files
);

// getting a Zend_Cache_Core object
$cache = Zend_Cache::factory('Core', 'File', $frontendOptions, $backendOptions);
$registry->set('cache',$cache);


// setup controller
$frontController = Zend_Controller_Front::getInstance();
$frontController->throwExceptions(true);

//setup core cms modules
$frontController->addControllerDirectory('../application/admin/controllers', 'admin');
$frontController->addControllerDirectory('../application/front/controllers', 'cmsFront');
$frontController->addControllerDirectory('../application/public/controllers', 'public');

//setup extension modules
$extensions = DSF_Filesystem_Dir::getDirectories('../application/modules');
if(is_array($extensions))
{
	foreach ($extensions as $extension)
	{
		$frontController->addControllerDirectory('../application/modules/' . $extension . '/controllers', 'mod_' . $extension);
	}
}

$frontController->setDefaultModule('public');
		
//create an instance of acl
$acl = new DSF_Acl();
$registry->set('acl', $acl);

$frontController->registerPlugin(new DSF_Controller_Plugin_SetPagePath());
//$frontController->registerPlugin(new DSF_Controller_Plugin_LogTraffic());
$frontController->registerPlugin(new DSF_Controller_Plugin_LayoutLoader());
$frontController->registerPlugin(new DSF_Controller_Plugin_Auth($acl));

// run!
$frontController->dispatch();