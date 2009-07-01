<?php

class DSF_Installer_Environment{
    const PATH_TO_CACHE = './cache';
    const PATH_TO_MEDIA = './media';
    const PATH_TO_TRASH = './trash';

    public function __construct() {

    }

    public function checkPhpVersion($requiredVersion)
    {
        if (version_compare(PHP_VERSION, $requiredVersion, '>')) {
            return true;
        } else {
            return false;
        }
    }

    public function checkExtension($extension)
    {
        $extensions = get_loaded_extensions();
        if (in_array($extension, $extensions)) {
            return true;
        } else {
            return false;
        }
    }

    public function cacheIsWritable()
    {
        if (is_writable(self::PATH_TO_CACHE)) {
            return true;
        } else {
            return false;
        }
    }

    public function mediaIsWritable()
    {
        if (is_writable(self::PATH_TO_MEDIA)) {
            return true;
        } else {
            return false;
        }
    }

    public function trashIsWritable()
    {
        if (is_writable(self::PATH_TO_TRASH)) {
            return true;
        } else {
            return false;
        }
    }
}
?>