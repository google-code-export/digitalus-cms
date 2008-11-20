<?php

class DSF_Media_File {
    public $name;
    public $path;
    public $type;
    public $fullUrl;
    public $fullPath;
    public $exists = false;
    
    function __construct($path, $basePath = './', $baseUrl = '/') {
        $this->name = basename($path);
        $this->path = $path;
        
        $mediaFolder = DSF_Media::rootDirectory();
        
        $this->fullPath = $basePath . $mediaFolder . '/' . $path;
        $this->fullUrl = $baseUrl . $mediaFolder . '/' . $path;
        
        $this->type = DSF_Media_Filetype::load($path);
        
        if($this->fileExists()) {
            $this->exists = true;
        }
    }
    
    public function fileExists()
    {
        if(file_exists($this->fullPath)) {
            return true;
        }
        return false;
    }
    
}

?>