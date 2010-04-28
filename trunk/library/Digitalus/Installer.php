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
 * @author      Lowtower - lowtower@gmx.de
 * @category    Digitalus CMS
 * @package     Digitalus
 * @copyright   Copyright (c) 2007 - 2010,  Digitalus Media USA (digitalus-media.com)
 * @license     http://digitalus-media.com/license/new-bsd     New BSD License
 * @version     $Id: Installer.php 729 2010-04-19 20:11:57Z lowtower@gmx.de $
 * @link        http://www.digitaluscms.com
 * @since       Release 1.5.0
 */

/**
 * Installer
 *
 * @author      Lowtower - lowtower@gmx.de
 * @copyright   Copyright (c) 2007 - 2010,  Digitalus Media USA (digitalus-media.com)
 * @license     http://digitalus-media.com/license/new-bsd     New BSD License
 * @version     Release: @package_version@
 * @link        http://www.digitaluscms.com
 * @since       Release 1.5.0
 * @uses        Digitalus_Installer_Config
 * @uses        Digitalus_Installer_Database
 * @uses        Digitalus_Installer_Environment
 */
class Digitalus_Installer
{
    protected $_errors   = array();
    protected $_warnings = array();
    protected $_messages = array();
    protected $_config;
    protected $_db;
    protected $_env;
    protected $_firstName;
    protected $_lastName;
    protected $_username;
    protected $_password;
    protected $_pathToConfig;

    public function __construct($mode = null)
    {
        // We want the installer to manage its own warnings
        error_reporting(E_ERROR);
        $this->_db = new Digitalus_Installer_Database();

        // Load config
        $verbose = true;
        if ('v19' == $mode) {
            $verbose = false;
        }
        $config = $this->loadConfig(false, $mode, $verbose);
        $this->_setConfig($config);
    }

    public function getConfig()
    {
        return $this->_config;
    }

    public function isInstalled($verbose = false)
    {
        if (intval($this->_config->getInstallDate()) > 0) {
            if (true == (bool)$verbose) {
                $this->addError('Digitalus CMS is already installed');
            }
            return true;
        }
        return false;
    }

    public function isHigherVersionNumber()
    {
        $defaultConfig = $this->loadConfig(true, null);

        $version['new'] = (string)$defaultConfig->get()->production->constants->version;
        $version['old'] = (string)$this->_config->get()->production->constants->version;


        if (1 === version_compare($version['new'], $version['old'])) {
            return $version;
        }
        return false;
    }

    protected function _setConfig(Digitalus_Installer_Config $config)
    {
        $this->_config = $config;
    }

    protected function _setPathToConfig($pathToConfig)
    {
        $this->_pathToConfig = $pathToConfig;
    }

    public function getPathToConfig()
    {
        return $this->_pathToConfig;
    }

    public function loadConfig($default = false, $mode = null, $verbose = false)
    {
        // Load config
        $config = new Digitalus_Installer_Config($default, $mode);

        $this->_setPathToConfig($config->getPathToConfig());

        $configError = false;
        if (!$config->isReadable()) {
            if (true == (bool)$verbose) {
                $this->addError('Could not load config file (' . $this->getPathToConfig() . ')');
            }
            $configError = true;
        }

        if (!$config->isWritable()) {
            if (true == (bool)$verbose) {
                $this->addError('Could not write to config file (' . $this->getPathToConfig() . ')');
            }
            $configError = true;
        }

        if (!$configError && $config->loadFile()) {
            if (true == (bool)$verbose) {
                $this->addMessage('Successfully loaded and tested site configuration');
            }
        } else {
            if (true == (bool)$verbose) {
                $this->addError('Site configuration could not be loaded');
            }
        }
        return $config;
    }

    public function testEnvironment()
    {
        $this->_env = new Digitalus_Installer_Environment();
        $requiredPhpVersion = $this->_config->getRequiredPhpVersion();
        if (!$this->_env->checkPhpVersion($requiredPhpVersion)) {
            $this->addError('PHP Version: <b>' . $requiredPhpVersion . '</b> or greater is required for Digitalus CMS');
        } else {
            $this->addMessage('Checked PHP version...OK!');
        }

        if (!$this->_env->cacheIsWritable()) {
            $this->addError('Could not write to cache directory  (' . Digitalus_Installer_Environment::PATH_TO_CACHE . ')');
        } else {
            $this->addMessage('Checked cache directory...OK!');
        }

        if (!$this->_env->mediaIsWritable()) {
            $this->addError('Could not write to media directory  (' . Digitalus_Installer_Environment::PATH_TO_MEDIA . ')');
        } else {
            $this->addMessage('Checked media directory...OK!');
        }

        if (!$this->_env->trashIsWritable()) {
            $this->addError('Could not write to trash directory  (' . Digitalus_Installer_Environment::PATH_TO_TRASH . ')');
        } else {
            $this->addMessage('Checked trash directory...OK!');
        }

        $requiredExtensions = $this->_config->getRequiredExtensions();
        $envFailed = false;
        if (is_array($requiredExtensions)) {
            foreach ($requiredExtensions as $extension) {
                if (!$this->_env->checkExtension($extension)) {
                    $this->addError('The <b>' . $extension . '</b> PHP extension is required and is not installed on your server');
                    $envFailed = true;
                }
            }
        }

        if (!$envFailed) {
            $this->addMessage('Checked server environment...OK!');
        }
    }

    public function setAdminUser($firstName, $lastName, $email, $password)
    {
        $userError = false;
        if (!empty($firstName)) {
            $this->_firstName = $firstName;
        } else {
            $this->addError('Your first name is required');
            $userError = true;
        }

        if (!empty($lastName)) {
            $this->_lastName = $lastName;
        } else {
            $this->addError('Your last name is required');
            $userError = true;
        }

        if (!empty($email) && Zend_Validate::is($email, 'EmailAddress')) {
            $this->_username = $email;
        } else {
            $this->addError('A valid email address is required');
            $userError = true;
        }

        if (!empty($password)) {
            $this->_password = $password;
        } else {
            $this->addError('Your password is required');
            $userError = true;
        }

        if (!$userError) {
            return true;
        }
        return false;
    }

    public function setDbConnection($name, $host, $username, $password, $prefix = '', $adapter = 'Pdo_Mysql')
    {
        $dbError = false;
        if (empty($name)) {
            $this->addError('Your database name is required');
            $dbError = true;
        }
        if (empty($host)) {
            $this->addError('Your database host is required');
            $dbError = true;
        }
        if (empty($username)) {
            $this->addError('Your database username is required');
            $dbError = true;
        }
        if (empty($password)) {
            $this->addError('Your database password is required');
            $dbError = true;
        }
        $adapters = Digitalus_Installer_Database::getAllowedAdapters();
        $validator = new Zend_Validate_InArray($adapters);
        if (!$validator->isValid($adapter)) {
            $this->addError('Only the following database adapters are supported: ' . implode(', ', $adapters));
            $dbError = true;
        }
        $validator = new Zend_Validate_Regex(Digitalus_Installer_Database::DB_PREFIX_REGEX);
        if (!empty($prefix) && !$validator->isValid($prefix)) {
            $this->addError('For the table prefix a maximum of 12 only alphabetic and digit characters and underscore are allowed');
            $dbError = true;
        }

        if (!$dbError) {
            $connection = $this->_db->connect($name, $host, $username, $password, $prefix, $adapter);
            $this->_config->setDbConnection($name, $host, $username, $password, $prefix, $adapter);
            return $connection;
        }
        return false;
    }

    public function testDb()
    {
        $empty = $this->_db->isEmpty();
        if (!$empty) {
            $this->addError('The target database is not empty');
        }

        if ($empty) {
            $writable = $this->_db->isWritable();
            if (!$writable) {
                $this->addError('Unable to write to target database');
            } else {
                $this->addMessage('Database passed tests and is ready to install');
                return true;
            }
        }
        return false;
    }

    public function installDb()
    {
        $this->_db->installDatabase();
        $result = $this->_db->testInstallation();
        if ($result) {
            $this->addMessage('The database was successfully installed');
            return true;
        } else {
            $this->addError('An error occured installing the database');
            return false;
        }
    }

    public function saveAdminUser()
    {
        $userInserted = $this->_db->insertAdminUser(
            $this->_firstName,
            $this->_lastName,
            $this->_username,
            $this->_password
        );
        $this->_db->insertPages();
        if ($userInserted) {
            $this->addMessage('Your admin account was successfully created');
            return true;
        } else {
            $this->addError('There was an error creating your admin account');
            return false;
        }
    }

    public function finalize()
    {
        $this->_config->setInstallDate();
    }

    public function addError($message)
    {
        $this->_errors[] = array('message' => $message);
    }

    public function addWarning($message)
    {
        $this->_warnings[] = array('message' => $message);
    }

    public function addMessage($message)
    {
        $this->_messages[] = array('message' => $message);
    }

    public function getMessages()
    {
        $messages = new stdClass();
        if (count($this->_errors) > 0) {
            $messages->errors = $this->_errors;
        }

        if (count($this->_warnings) > 0) {
            $messages->warnings = $this->_warnings;
        }

        if (count($this->_messages) > 0) {
            $messages->messages = $this->_messages;
        }

        return $messages;
    }

    public function hasErrors()
    {
        if (count($this->_errors) > 0) {
            return true;
        }
        return false;
    }

    public function setLanguage($language = null)
    {
        // provide translations
        $validLanguages = array('english' => 'en', 'german' => 'de', 'french' => 'fr', 'russian' => 'ru');
        Zend_Registry::set('validLanguages', $validLanguages);

        if (!empty($language) && '' != $language) {
            $language = strtolower($language);
        } elseif (isset($_GET['language']) && !empty($_GET['language'])) {
            $language = strtolower($_GET['language']);
        } else {
            $locale    = new Zend_Locale();
            $lang      = $locale->getLanguage();
            $languages = array_flip($validLanguages);
            $language  = $languages[$lang];
        }
        if (!in_array($language, array_keys($validLanguages))) {
            $language = 'english';
        }
        $locale = new Zend_Locale($validLanguages[$language]);
        Zend_Registry::set('language', $language);

        $adapter = new Zend_Translate(
            'csv',
            './application/admin/data/languages/back/' . $language . '.back.csv',
            $validLanguages[$language],
            array('disableNotices' => true)
            );
        Zend_Registry::set('Zend_Translate', $adapter);

        $view = new Zend_View();
        $view->translate()->setLocale($locale);
        return $language;
    }
}