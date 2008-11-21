<?php

class DSF_Media_Filetype {
    public $key;
    public $type;
    public $mime;
    
    function __construct() {

    }
    
    static function load($filepath)
    {
         $fileExtension = DSF_Filesystem_File::getFileExtension($filepath);
         $fileExtension = strtolower($fileExtension);
         $allowedFiletypes = DSF_Media::getFiletypes();
         if(is_array($allowedFiletypes) && array_key_exists($fileExtension, $allowedFiletypes)) {
             return $allowedFiletypes[$fileExtension];
         }
         return null;
    }
    
    public function setFromConfigItem($key, Zend_Config $type)
    {
        $this->key = strtolower($key);
        $this->type = strtolower($type->type);
        $this->mime = strtolower($type->mime);
    }
    
    public function isType($mimeType)
    {
        if($mimeType == $this->mime) {
            return true;
        }else{
            return false;
        }
    }
}

?>