<?php
class Environment
{
    private $_phpVersion = '5.2.3';
    
    private $_extensions = array('ctype', 'dom', 'gd', 'iconv', 'libxml', 'PDO', 
    'pdo_mysql', 'Reflection', 'session', 'SimpleXML','SPL', 'xml','zip');
    
    protected $_failure = false;
    protected $_errors = array();  
    
    public function checkSystem()
    {
        $this->checkVersion();
        $this->checkExtensions();
    }
    
    public function checkVersion()
    {
        if(version_compare(PHP_VERSION, $this->_phpVersion, '<')){
            $this->_errors[] = "PHP version " . $this->_phpVersion . " or greater is required.";
        }
    }
    
    public function checkExtensions()
    {
        $extensions = get_loaded_extensions();
        foreach ($this->_extensions as $extension) {
            $result = in_array($extension, $extensions);
        	if($result == 0){
        	    
        	    $this->_errors[] = "The " . $extension . " extension is not installed";
        	}
        }        
    }
    
    public function hasErrors()
    {
        if(count($this->_errors) > 0) {
            return true;
        }
    }
    
    public function getErrors()
    {
        return $this->_errors;
    }
}