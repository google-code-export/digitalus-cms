<?php
class Source
{
    private $_downloadPath;
    private $_source;
    private $_tmpName = 'tmp_cms.zip';
    
    public function __construct()
    {
        $config = simplexml_load_file('./data/config.xml');
        $this->_downloadPath = $config->cms->downloadPath;
    }
    
    public function get()
    {
        $this->_source = file_get_contents($this->_downloadPath);
        if($this->_source){
            file_put_contents($this->_tmpName, $this->_source);
            return true;
        }else{
            trigger_error("Error loading source files", E_USER_ERROR);
        }
    }
    
    public function install()
    {     
        $zip = new ZipArchive;
         $res = $zip->open($this->_tmpName);
         if ($res === TRUE) {
             $zip->extractTo('../');
             $zip->close();
         } else {
             trigger_error("Error extracting source files", E_USER_ERROR);
         }
    }
}