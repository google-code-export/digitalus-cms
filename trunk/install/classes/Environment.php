<?php
class Environment
{
    private $_phpVersion = '5.2.3';
    
    private $_extensions = array(
        'zip'           => 1,
        'ctype'         => 1,
        'dom'           => 1,
        'gd'           => 1,
        'iconv'         => 1,
        'libxml'        => 1,
        'PDO'           => 1,
        'pdo_mysql'     => 1,
        'Reflection'    => 1,
        'session'       => 1,
        'SimpleXML'     => 1,
        'SPL'           => 1,
        'xml'           => 1,
        'zip'           => 1
    );
    
    protected $_failure = false;
    
   
    public function __construct()
    {
    }
    
    public function checkSystem()
    {
        $this->checkVersion();
        $this->checkExtensions();
    }
    
    public function checkVersion()
    {
        if(version_compare(PHP_VERSION, $this->_phpVersion, '<')){
            trigger_error("PHP version " . $this->_phpVersion . " or greater is required.", E_USER_ERROR);
        }
    }
    
    public function checkExtensions()
    {
        $extensions = get_loaded_extensions();
        foreach ($this->_extensions as $extension => $value) {
            $result = in_array($extension, $extensions);
        	if($result == 0){
        	    trigger_error("The " . $extension . " is not installed", E_USER_WARNING);
        	    if($value == 1){
                    $this->_failure = true;
            	}
            	
        	}
        }
        if($this->_failure == true){
            trigger_error("PHP is not configured correctly.  Aborting installation.", E_USER_ERROR);
        }
        
    }
    
    public function passed()
    {
        if(count($this->_errors)< 1) {
            return true;
        }
    }
}