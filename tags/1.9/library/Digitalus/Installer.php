<?php
require_once 'Digitalus/Installer/Database.php';
require_once 'Digitalus/Installer/Config.php';
require_once 'Digitalus/Installer/Environment.php';

class Digitalus_Installer
{
    protected $_errors = array();
    protected $_warnings = array();
    protected $_messages = array();
    protected $_config;
    protected $_db;
    protected $_env;
    protected $_firstName;
    protected $_lastName;
    protected $_username;
    protected $_password;

    public function __construct() {
        // We want the installer to manage its own warnings
        error_reporting(E_ERROR);
        $this->_db = new Digitalus_Installer_Database();

        // Load config
        $this->loadConfig();

    }

    public function isInstalled()
    {
        if (intval($this->_config->getInstallDate()) > 0) {
            $this->addError('Digitalus CMS is already installed');
            return true;
        } else {
            return false;
        }
    }

    public function loadConfig()
    {
        // Load config
        $this->_config = new Digitalus_Installer_Config();

        $configError = false;
        if (!$this->_config->isReadable()) {
            $this->addError('Could not load config file (' . Digitalus_Installer_Config::PATH_TO_CONFIG . ')');
            $configError = true;
        }

        if (!$this->_config->isWritable()) {
            $this->addError('Could not write to config file (' . Digitalus_Installer_Config::PATH_TO_CONFIG . ')');
            $configError = true;
        }

        if (!$configError) {
            $this->addMessage('Successfully loaded and tested site configuration');
        }

        $this->_config->loadFile();
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
        } else {
            return false;
        }
    }

    public function setDbConnection($name, $host, $username, $password)
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

        if (!$dbError) {
            $connection = $this->_db->connect($name, $host, $username, $password, $this->_config->getDbAdapterKey());
            $this->_config->setDbConnection($name, $host, $username, $password);
            return $connection;
        } else {
            return false;
        }
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
        } else {
            return false;
        }
    }
}
?>