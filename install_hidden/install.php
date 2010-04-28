<?php
ini_set('display_startup_errors', 1);
ini_set('display_errors', 1);
error_reporting(E_ALL | E_STRICT);

set_include_path('.' . PATH_SEPARATOR . './library' . PATH_SEPARATOR . get_include_path());

require_once 'Zend/Loader/Autoloader.php';

// Set up autoload
$loader = Zend_Loader_Autoloader::getInstance();
$loader->registerNamespace('Digitalus_');

// Set up view
$view = new Zend_View();
$view->setScriptPath('./install/views');

// The Digitalus_Installer class manages the installation resources
$installer = new Digitalus_Installer('v19');
// set language and locale for translations
$installer->setLanguage();

/* *****************************************************************************
 * U P D A T E
 * ************************************************************************** */
if ($installer->isInstalled()) {
    // Fetch the current step
    $update = $_GET['update'];
    $update = intval($update);
    if ($update < 0) {
        $update = 0;
    }

    // update to higher version
    if ($version = $installer->isHigherVersionNumber()) {
        // instantiate updater object
        $pathToConfig = Digitalus_Updater_Version19to110::getConfigPath('old');
        $updater      = new Digitalus_Updater_Version19to110($pathToConfig);
        if (Digitalus_Updater_Version19to110::checkVersions($version['new'], $version['old'])) {
            switch ($update) {
                case 0:
                default:
                    $view->installationInformation = $updater->getInstallationInformation();
                    $view->placeholder('form')->set($view->render('update.phtml'));
                    break;
                case 1:
                    try {
                        $updater->run();
                        $view->placeholder('form')->set($view->render('update1.phtml'));
                    } catch (Digitalus_Updater_Exception $e) {
                        $updater->addError('A fatal error while updating the databases occurred!');
                        $updater->addError($e->getMessage());
                    }
                    break;
            }
        } else {
            $updater->addError('You can only update from version ' . Digitalus_Updater_Abstract::getOldVersion() . '!<br />Older versions are not supported!');
        }
    } else {
        // The cms is already installed
        // Remove the install directory
#        Digitalus_Filesystem_Dir::deleteRecursive('./install');
        Digitalus_Filesystem_Dir::rename('./install', './install_hidden');
        // Return to the index file
        header('location: ./');
    }
/* *****************************************************************************
 * F R E S H   I N S T A L L
 * ************************************************************************** */
} else {
    // Fetch the current step
    $step = $_GET['step'];
    $step = intval($step);
    if ($step < 1) {
        $step = 1;
    }

    switch ($step) {
        case 1:
        default:
            $installer->testEnvironment();
            if (!$installer->hasErrors()) {
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
            if ($result) {
                $connection = $installer->setDbConnection(
                    $_POST['db_name'],
                    $_POST['db_host'],
                    $_POST['db_username'],
                    $_POST['db_password'],
                    $_POST['db_prefix'],
                    $_POST['db_adapter']
                );
                if ($connection) {
                    $dbTested = $installer->testDb();
                    if ($dbTested) {
                        $installer->installDb();
                        $installer->saveAdminUser();
                        $installer->finalize();
                    }
                }
            }

            if (!$installer->hasErrors()) {
                $view->placeholder('form')->set($view->render('step2.phtml'));
            } else {
                $view->data = $_POST;
                $view->placeholder('form')->set($view->render('step1.phtml'));
            }
            break;
    }
}
$view->messages = $installer->getMessages();
echo $view->render('page.phtml');