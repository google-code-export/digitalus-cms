<?php
set_include_path('.' . PATH_SEPARATOR . './library' . PATH_SEPARATOR . './application/models/' . PATH_SEPARATOR . get_include_path());
require_once './application/Initializer.php';
require_once "Zend/Loader.php"; 

// Set up autoload.
Zend_Loader::registerAutoload();

// Prepare the front controller. 
$frontController = Zend_Controller_Front::getInstance();

// Change to 'production' parameter under production environment
if($_SERVER['SERVER_NAME'] == 'local.digitalus-media') {
	$env = "testing";
}else{
	$env = "production";
}
$frontController->registerPlugin(new Initializer($env));   

//this loads the admin interface
//$frontController->registerPlugin(new DSF_Controller_Plugin_LayoutLoader());
 
// secure the application
//set up security
$frontController->registerPlugin(new DSF_Controller_Plugin_Auth());
        
// Dispatch the request using the front controller. 
$frontController->dispatch();