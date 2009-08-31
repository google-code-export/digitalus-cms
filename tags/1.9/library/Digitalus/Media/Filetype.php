<?php

class Digitalus_Media_Filetype {
    public $key;
    public $type;
    public $mime;

    public function __construct() {

    }

    public static function load($filepath)
    {
        $fileExtension = Digitalus_Filesystem_File::getFileExtension($filepath);
        $fileExtension = strtolower($fileExtension);
        $allowedFiletypes = Digitalus_Media::getFiletypes();
        if (is_array($allowedFiletypes) && array_key_exists($fileExtension, $allowedFiletypes)) {
            return $allowedFiletypes[$fileExtension];
        }
        return null;
    }

    public function setFromConfigItem($key, Zend_Config $type)
    {
        $this->key = strtolower($key);
        $this->type = strtolower($type->type);
        $this->mime = $type->mime;
    }

    public function isType($mimeType)
    {
        if (is_object($this->mime)) {
            if (in_array($mimeType, $this->mime->type->toArray())) {
                return true;
            } else {
                return false;
            }
        } else if ($mimeType == $this->mime) {
            return true;
        }
        return false;
    }
}
?>