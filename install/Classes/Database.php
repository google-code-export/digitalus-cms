<?php
class Database
{
    private $_sqlFile = './install/digitalus_cms_1_5.sql';
    private $_configFile = './application/data/config.xml';
    private $_connection;
    private $_sqlCommands = '';
    protected $_errors = array();
    protected $_dbName;
    protected $_dbHost;
    protected $_dbUsername;
    protected $_dbPassword;

    public function __construct($dbName, $dbHost, $dbUsername, $dbPassword)
    {
        $this->_dbName = $dbName;
        $this->_dbHost = $dbHost;
        $this->_dbUsername = $dbUsername;
        $this->_dbPassword = $dbPassword;
    }
    
    public function install()
    {
        $result = $this->_getSql();
        if(!$result) {
            return false;
        }
        
        $result = $this->connect();
        if(!$result) {
            return false;
        }
        
        $result = $this->create();
        if(!$result) {
            return false;
        }
        
        $result = $this->saveDatabaseConnection();
        if(!$result) {
            return false;
        }
        
        $result = $this->finish();
        if(!$result) {
            return false;
        }
        
        return true;
        
    }
    

    public function hasErrors()
    {
        if(count($this->_errors) > 0) {
            return true;
        }else{
            return false;
        }
    }
    public function getErrors()
    {
        if(count($this->_error) > 0) {
            return $this->_error;
        }else{
            return null;
        }
    }
    
    public function addError($error)
    {
        $this->_error[] = $error;
    }

    private function _getSql()
    {
        
        if (file_exists($this->_sqlFile)) {
           $this->_sqlCommands = file_get_contents($this->_sqlFile);
            return true;
        } else {
            $this->addError("Could not load database file");
            return false;
        }
    }
    
    public function connect()
    {
        $this->_connection = @mysqli_connect($this->_dbHost, $this->_dbUsername, $this->_dbPassword);
        if (!$this->_connection) {
            $this->addError("Could not connect to the database");
            return false;
        } else {
            return true;
        }
    }

    public function create()
    {
        $info['description'] = 'Check Database';

        //check if database exists
        $query = "SHOW DATABASES";
        $result = mysqli_query($this->_connection, $query);

        $databases = array();
        while ($x = mysqli_fetch_row($result)) {
            $databases[] = $x[0];
        }

        if(array_search($this->_dbName, $databases) == false) {
            $this->addError("Database does not exist");
            return false;
        }

        $this->execute("USE " . $this->_dbName);
        $this->execute("SET NAMES UTF8");

        $this->execute("DROP TABLE IF EXISTS `content_nodes`");
        $this->execute("DROP TABLE IF EXISTS `data`");
        $this->execute("DROP TABLE IF EXISTS `designs`");
        $this->execute("DROP TABLE IF EXISTS `error_log`");
        $this->execute("DROP TABLE IF EXISTS `pages`");
        $this->execute("DROP TABLE IF EXISTS `people`");
        $this->execute("DROP TABLE IF EXISTS `redirectors`");
        $this->execute("DROP TABLE IF EXISTS `references`");
        $this->execute("DROP TABLE IF EXISTS `traffic_log`");
        $this->execute("DROP TABLE IF EXISTS `users`");

        if (!$this->execute($this->_sqlCommands)) {
            $this->addError("Error installing database");
            return false;
        }
        return true;
    }
    
    public function saveDatabaseConnection()
    {
        $config = simplexml_load_file($this->_configFile);
        if($config) {
            $config->DSF->database->host = $this->_dbHost;
            $config->DSF->database->dbname = $this->_dbName;
            $config->DSF->database->username = $this->_dbUsername;
            $config->DSF->database->password = $this->_dbPassword;
            if($config->asXml($this->_configFile)) {
                return true;
            }else{
                $this->addError("Error writing to configuration file");
            }
        }
        return false;
    }
    
    public function finish()
    {
        $installMessage = "Digitalus CMS -> installed: " . date("F j, Y, g:i a");
       if(file_put_contents('./install/installation_ok.txt', $installMessage)){
           return true;
       }else{
           return false;
       }
    }

    private function execute($sql)
    {
        if (mysqli_multi_query($this->_connection, $sql)) {
            return true;
        } else {
            return false;
        }
    }

    public function close()
    {
        mysqli_close($this->_connection);
    }
}