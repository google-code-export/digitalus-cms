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
         $allowedFiletypes = DSF_Media::getFiletypes();
         if(is_array($allowedFiletypes) && array_key_exists($fileExtension, $allowedFiletypes)) {
             return $allowedFiletypes[$fileExtension];
         }
         return null;
    }
    
    public function setFromConfigItem($key, Zend_Config $type)
    {
        $this->key = $key;
        $this->type = $type->type;
        $this->mime = $type->mime;
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