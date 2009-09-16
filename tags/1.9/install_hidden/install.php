<?php
set_include_path('.' . PATH_SEPARATOR . './library' . PATH_SEPARATOR . get_include_path());

require_once 'Zend/Loader/Autoloader.php';

// Set up autoload
$loader = Zend_Loader_Autoloader::getInstance();
$loader->registerNamespace('Digitalus_');

// The Digitalus_Installer class manages the installation resources
$installer = new Digitalus_Installer();

// Set up view
$view = new Zend_View();
$view->setScriptPath('./install/views');

// provide translations
$validLanguages = array('english' => 'en', 'german' => 'de', 'french' => 'fr', 'polish' => 'pl', 'russian' => 'ru', 'swedish' => 'se');
Zend_Registry::set('validLanguages', $validLanguages);

if (isset($_GET['language']) && !empty($_GET['language'])) {
    $language = $_GET['language'];
    $locale = new Zend_Locale($validLanguages[$language]);
} else {
    $locale = new Zend_Locale();
    $lang = $locale->getLanguage();
    $languages = array_flip($validLanguages);
    $language = $languages[$lang];
}
if (!in_array($language, array_keys($validLanguages))) {
    $language = 'english';
}
Zend_Registry::set('language', $language);

$adapter = new Zend_Translate(
    'csv',
    './application/data/language/back/' . $language . '.back.csv',
    $validLanguages[$language],
    array('disableNotices' => true)
    );
Zend_Registry::set('Zend_Translate', $adapter);

#$view->translate()->setLocale($validLanguages[$language]);
$view->translate()->setLocale($locale);

if (!$installer->isInstalled()) {
    // Fetch the current step
    $step = $_GET['step'];
    $step = intval($step);
    if ($step < 1) {
        $step = 1;
    }

    switch ($step) {
        case 1:
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
                $installer->setDbConnection(
                    $_POST['db_name'],
                    $_POST['db_host'],
                    $_POST['db_username'],
                    $_POST['db_password'],
                    $_POST['db_prefix'],
                    $_POST['db_adapter']
                );
                $dbTested = $installer->testDb();
                if ($dbTested) {
                    $installer->installDb();
                    $installer->saveAdminUser();
                    $installer->finalize();
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
} else {
    // The cms is already installed
    // Remove the install directory
    Digitalus_Filesystem_Dir::deleteRecursive('./install');
    // Return to the index file
    header('location: ./');
}
$view->messages = $installer->getMessages();
echo $view->render('page.phtml');