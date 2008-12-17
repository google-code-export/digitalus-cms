<?php
require_once 'DSF/Installer/Database.php';
require_once 'DSF/Installer/Config.php';
require_once 'DSF/Installer/Environment.php';

class DSF_Installer {
    protected $_errors = array();
    protected $_warnings = array();
    protected $_messages = array();
    protected $_config;
    protected $_db;
    protected $_env;
    protected $_username;
    protected $_password;
    
    function __construct() {
        //we want the installer to manage its own warnings 
        error_reporting(E_ERROR);
        $this->_db = new DSF_Installer_Database();
    }
    
    public function isInstalled()
    {
        if(intval($this->_config->getInstallDate()) > 0) {
            $this->addError("Digitalus CMS is already installed");
            return true;
        }else{
            return false;
        }
    }
    
    public function loadConfig()
    {
        // load config
        $this->_config = new DSF_Installer_Config();
        
        $configError = false;
        if(!$this->_config->isReadable()) {
            $this->addError("Could not load config file");
            $configError = true;
        }
        
        if(!$this->_config->isWritable()) {
            $this->addError("Could not write to config file");
            $configError = true;
        }
        
        if(!$configError) {
            $this->addMessage("Successfully loaded and tested site configuration");
        }
        
        $this->_config->loadFile();
    }
    
    public function testEnvironment()
    {
        $this->_env = new DSF_Installer_Environment();
        $requiredPhpVersion = $this->_config->getRequiredPhpVersion();
        if(!$this->_env->checkPhpVersion($requiredPhpVersion)) {
            $this->addError("PHP Version: <b>" . $requiredPhpVersion . "</b> or greater is required for Digitalus CMS");
        }else{
            $this->addMessage("Checked PHP version...OK!");
        }
        
        $requiredExtensions = $this->_config->getRequiredExtensions();
        $envFailed = false;
        if(is_array($requiredExtensions)) {
            foreach ($requiredExtensions as $extension) {
                if(!$this->_env->checkExtension($extension)) {
                    $this->addError("The <b>" . $extension . "</b> PHP extension is required and is not installed on your server");
                    $envFailed = true;
                }
            }
        }
        
        if(!$envFailed) {
            $this->addMessage("Checked server environment...OK!");
        }
    }
    
    public function setAdminUser($email, $password)
    {
        $userError = false;
        if(!empty($email) && Zend_Validate::is($email, 'EmailAddress')) {
            $this->_username = $email;
        }else{
            $this->addError("A valid email address is required");
            $userError = true;
        }
        
        if(!empty($password)) {
            $this->_password = $password;
        }else{
            $this->addError("Your password is required");
            $userError = true;
        }
        
        if(!$userError) {
            return true;
        }else{
            return false;
        }
    }
    
    public function setDbConnection($name, $host, $username, $password)
    {
        $dbError = false;
        if(empty($name)) {
            $this->addError("Your database name is required");
            $dbError = true;
        }
        if(empty($host)) {
            $this->addError("Your database host is required");
            $dbError = true;
        }
        if(empty($username)) {
            $this->addError("Your database username is required");
            $dbError = true;
        }
        
        if(!$dbError) {
            $connection = $this->_db->connect($name, $host, $username, $password, $this->_config->getDbAdapterKey());
            $this->_config->setDbConnection($name, $host, $username, $password);
            return $connection;
        }else{
            return false;
        }
    }
    
    public function testDb()
    {
        $exists = $this->_db->exists();
        if(!$exists) {
            $this->addError("The database you entered does not exist");
        }else{
            $this->addMessage("Database exists");
        }
        
        if($exists) {
            $empty = $this->_db->isEmpty();
            if(!$empty) {
                $this->addError("The target database is not empty");
            } 
        }
        
        if($exists && $empty) {
            $writable = $this->_db->isWritable();
            if(!$writable) {
                $this->addError("Unable to write to target database");
            }else{
                $this->addMessage("Database passed tests and is ready to install");
                return true;
            }
        }
        return false;
    }
    
    public function installDb()
    {
        $this->_db->installDatabase();
        $result = $this->_db->testInstallation();
        if($result) {
            $this->addMessage("The database was successfully installed");
            return true;
        }else{
            $this->addError("An error occured installing the database");
            return false;
        }
    }
    
    public function saveAdminUser()
    {
        $userInserted = $this->_db->insertAdminUser($this->_username, $this->_password);
        if($userInserted) {
            $this->addMessage("Your admin account was successfully created");
            return true;
        }else{
            $this->addError("There was an error creating your admin account");
            return false;
        }
    }
    
    public function finalize()
    {
        $this->_config->setInstallDate();
    }
    
    public function addError($message)
    {
        $this->_errors[] = array("message" => $message);
    }
    
    public function addWarning($message)
    {
        $this->_warnings[] = array("message" => $message);
    }
    
    public function addMessage($message)
    {
        $this->_messages[] = array("message" => $message);
    }
    
    public function getMessages()
    {
        $messages = new stdClass();
        if(count($this->_errors) > 0) {
            $messages->errors = $this->_errors;
        }
    
        if(count($this->_warnings) > 0) {
            $messages->warnings = $this->_warnings;
        }
    
        if(count($this->_messages) > 0) {
            $messages->messages = $this->_messages;
        } 
        
        return $messages;
    }
    
    public function hasErrors()
    {
        if(count($this->_errors) > 0) {
            return true;
        }else{
            return false;
        }
    }
}

?>