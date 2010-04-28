<?php
/**
 * Digitalus CMS
 *
 * LICENSE
 *
 * This source file is subject to the new BSD license that is bundled
 * with this package in the file LICENSE.txt.
 * It is also available through the world-wide-web at this URL:
 * http://digitalus-media.com/license/new-bsd
 * If you did not receive a copy of the license and are unable to
 * obtain it through the world-wide-web, please send an email
 * to info@digitalus-media.com so we can send you a copy immediately.
 *
 * @author      Lowtower - lowtower@gmx.de
 * @category    Digitalus CMS
 * @package     Digitalus
 * @subpackage  Digitalus_Installer
 * @copyright   Copyright (c) 2007 - 2010,  Digitalus Media USA (digitalus-media.com)
 * @license     http://digitalus-media.com/license/new-bsd     New BSD License
 * @version     $Id: Config.php 729 2010-04-19 20:11:57Z lowtower@gmx.de $
 * @link        http://www.digitaluscms.com
 * @since       Release 1.5.0
 */

/**
 * Installer Config
 *
 * @author      Lowtower - lowtower@gmx.de
 * @copyright   Copyright (c) 2007 - 2010,  Digitalus Media USA (digitalus-media.com)
 * @license     http://digitalus-media.com/license/new-bsd     New BSD License
 * @version     Release: @package_version@
 * @link        http://www.digitaluscms.com
 * @since       Release 1.5.0
 */
class Digitalus_Installer_Config
{
    const PATH_TO_CONFIG_OLD     = './application/data/config.xml';
    const PATH_TO_DEFAULT_CONFIG = './application/admin/data/config.default.xml';
    const PATH_TO_CONFIG         = './application/admin/data/config.xml';
    const PATH_TO_CMS_CONFIG     = './application/configs/application.ini';

    protected $_pathToConfig;
    protected $_innerData;

    public function __construct($default = true, $mode = null)
    {
        switch (strtolower($mode)) {
            case 'v19':
                $this->_pathToConfig = self::PATH_TO_CONFIG_OLD;
                break;
            default:
                if (true == (bool)$default) {
                    $this->_pathToConfig = self::PATH_TO_DEFAULT_CONFIG;
                } else {
                    $this->_pathToConfig = self::PATH_TO_CONFIG;
                }
        }
    }

    public function getPathToConfig()
    {
        return $this->_pathToConfig;
    }

    public function isReadable()
    {
        if (file_exists($this->_pathToConfig) && @simplexml_load_file($this->_pathToConfig)) {
            return true;
        }
        return false;
    }

    public function isWritable()
    {
        if (!is_writeable($this->_pathToConfig) && @!chmod($this->_pathToConfig, 0666)) {
            return false;
        }
        return true;
    }

    public function loadFile()
    {
        $this->set(simplexml_load_file($this->_pathToConfig));
        if ($this->_innerData) {
            return true;
        }
        return false;
    }

    public function get()
    {
        return $this->_innerData;
    }

    public function set(SimpleXMLElement $xml)
    {
        $this->_innerData = $xml;
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

    public function setDbConnection($name, $host, $username, $password, $prefix = '', $adapter = 'Pdo_Mysql')
    {
        $this->_innerData->production->database->host     = $host;
        $this->_innerData->production->database->username = $username;
        $this->_innerData->production->database->password = $password;
        $this->_innerData->production->database->dbname   = $name;
        $this->_innerData->production->database->prefix   = $prefix;
        $this->_innerData->production->database->adapter  = $adapter;
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

    public function save($pathToConfig = null)
    {
        if (!isset($pathToConfig) || empty($pathToConfig)) {
            $pathToConfig = $this->getPathToConfig();
        }
        if ($this->isWritable()) {
            $this->_innerData->asXml($pathToConfig);
            return true;
        }
        return false;
    }
}