<?php
// Define base path obtainable throughout the whole application
defined('BASE_PATH')
    || define('BASE_PATH', realpath(dirname(__FILE__)));
// Define path to application directory
defined('APPLICATION_PATH')
    || define('APPLICATION_PATH', BASE_PATH . '/application');
// Define application environment
defined('APPLICATION_ENV')
    || define('APPLICATION_ENV', (getenv('APPLICATION_ENV') ? getenv('APPLICATION_ENV') : 'production'));

// check whether we are in installation process or not
if (file_exists('./install/install.php')) {
    // Start installation if the file "install.php" exists in the directory "install"
    require './install/install.php';
} else {
    // Set include path to Zend (and other) libraries
    set_include_path(BASE_PATH . '/library' .
        PATH_SEPARATOR . get_include_path() .
        PATH_SEPARATOR . '.'
    );

    // Require Zend_Application
    require_once 'Zend/Application.php';

    // Create application
    $application = new Zend_Application(
        APPLICATION_ENV,
        APPLICATION_PATH . '/admin/configs/application.ini'
    );
    // Bootstrap, and run application
    $application->bootstrap()
                ->run();
}