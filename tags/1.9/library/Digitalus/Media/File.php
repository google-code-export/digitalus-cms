<?php

class Digitalus_Media_File {
    public $name;
    public $path;
    public $type;
    public $fullUrl;
    public $fullPath;
    public $exists = false;

    public function __construct($path, $basePath = './', $baseUrl = '/') {
        $this->name = basename($path);
        $this->path = $path;

        $mediaFolder = Digitalus_Media::rootDirectory();

        $this->fullPath = $basePath . $mediaFolder . '/' . $path;
        $this->fullUrl = $baseUrl . $mediaFolder . '/' . $path;

        $this->type = Digitalus_Media_Filetype::load($path);

        if ($this->fileExists()) {
            $this->exists = true;
        }
    }

    public function fileExists()
    {
        if (file_exists($this->fullPath)) {
            return true;
        }
        return false;
    }

}