<?php

class Digitalus_Installer_Config
{
    const PATH_TO_CONFIG     = './application/data/config.xml';
    const PATH_TO_CMS_CONFIG = './application/configs/application.ini';

    protected $_innerData;

    public function __construct()
    {

    }

    public function isReadable()
    {
        if (file_exists(self::PATH_TO_CONFIG) && @simplexml_load_file(self::PATH_TO_CONFIG)) {
            return true;
        } else {
            return false;
        }
    }

    public function isWritable()
    {
        if (!is_writeable(self::PATH_TO_CONFIG) && @!chmod(self::PATH_TO_CONFIG, 0666)) {
            return false;
        } else {
            return true;
        }
    }

    public function loadFile()
    {
        $this->_innerData = simplexml_load_file(self::PATH_TO_CONFIG);
        if ($this->_innerData) {
            return true;
        } else {
            return false;
        }
    }

    public function get()
    {
        return $this->_innerData;
    }

    public function getInstallDate()
    {
        return (int)$this->_innerData->system->installDate;
    }

    public function setInstallDate()
    {
        $this->_innerData->system->installDate = time();
        $this->save();
    }

    public function setDbConnection($name, $host, $username, $password, $prefix = '')
    {
        $this->_innerData->production->database->host = $host;
        $this->_innerData->production->database->username = $username;
        $this->_innerData->production->database->password = $password;
        $this->_innerData->production->database->dbname = $name;
        $this->_innerData->production->database->prefix = $prefix;
        $this->save();
    }

    public function getRequiredPhpVersion()
    {
        return (string)$this->_innerData->system->requirements->php;
    }

    public function getRequiredExtensions()
    {
        $extensions = $this->_innerData->system->requirements->extensions;
        if ($extensions) {
            $data = array();
            foreach ($extensions->ext as $extension) {
                $data[] = (string)$extension;
            }
            if (count($data) > 0) {
                return $data;
            }
        }
        return false;
    }

    public function getDbAdapterKey()
    {
        return (string)$this->_innerData->production->database->adapter;
    }

    public function save()
    {
        $this->_innerData->asXml(self::PATH_TO_CONFIG);
    }
}