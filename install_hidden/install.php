<?php
set_include_path('.' . PATH_SEPARATOR . './library' . PATH_SEPARATOR . get_include_path());
require_once('DSF/Installer.php');
require_once "Zend/Loader.php"; 

// Set up autoload.
Zend_Loader::registerAutoload();

// the DSF_Installer class manages the installation resources
$installer = new DSF_Installer();
$installer->loadConfig();

//set up view
$view = new Zend_View();
$view->setScriptPath('./install/views');

if(!$installer->isInstalled()) {
    //fetch the current step
    $step = $_GET['step'];
    $step = intval($step);
    if($step < 1) {$step = 1;}
    
    switch ($step) {
        case 1:
            $installer->testEnvironment();
            if(!$installer->hasErrors()) {
                $view->placeholder('form')->set($view->render('step1.phtml'));
            }
            break;
        case 2:
            $result = $installer->setAdminUser(
                $_POST['first_name'], 
                $_POST['last_name'], 
                $_POST['email'], 
                $_POST['account_password']
            );
            if($result) {
                $installer->setDbConnection(
                    $_POST['db_name'],
                    $_POST['db_host'],
                    $_POST['db_username'],
                    $_POST['db_password']
                );
                $dbTested = $installer->testDb();
                if($dbTested) {
                    $installer->installDb();
                    $installer->saveAdminUser();
                    $installer->finalize();
                }
            }
           
            if(!$installer->hasErrors()) {
                $view->placeholder('form')->set($view->render('step2.phtml'));
            }else{
                $view->data = $_POST;
                $view->placeholder('form')->set($view->render('step1.phtml'));
            }
            break;
    }
}
$view->messages = $installer->getMessages();
echo $view->render('page.phtml');


?>