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
 * @subpackage  Digitalus_Updater
 * @copyright   Copyright (c) 2007 - 2010,  Digitalus Media USA (digitalus-media.com)
 * @license     http://digitalus-media.com/license/new-bsd     New BSD License
 * @version     $Id$
 * @link        http://www.digitaluscms.com
 * @since       Release 1.10
 */

/**
 * Updater Abstract
 *
 * @author      Lowtower - lowtower@gmx.de
 * @copyright   Copyright (c) 2007 - 2010,  Digitalus Media USA (digitalus-media.com)
 * @license     http://digitalus-media.com/license/new-bsd     New BSD License
 * @version     Release: @package_version@
 * @link        http://www.digitaluscms.com
 * @since       Release 1.10
 */
abstract class Digitalus_Updater_Abstract
{
    protected $_errors   = array();
    protected $_warnings = array();
    protected $_messages = array();

    /**
     * information about the new installation
     *
     * @var array
     */
    protected $_installationInformation = array();

    /**
     * db adapter
     *
     * @var Zend_Db_Table Adapter
     */
    protected $_db;

    /**
     * load the db adapter
     *
     */
    public function __construct($pathToConfig)
    {
        error_reporting(E_ALL | E_STRICT);
        $this->setDbConnection($pathToConfig);
        $this->_db = Zend_Db_Table::getDefaultAdapter();
    }

    public function setDbConnection($pathToConfig)
    {
        $pathToConfig = BASE_PATH . '/' . str_replace('./', '', $pathToConfig);

        if (!file_exists($pathToConfig) || !is_readable($pathToConfig)) {
            throw new Digitalus_Updater_Exception('Config file was not found or is unreadable!');
        }
        $config = new Zend_Config_Xml($pathToConfig, APPLICATION_ENV);

        $database = $config->database;
        $adapter  = (string)$database->adapter;

        $dbConfig = array(
            'adapter'  => (string)$database->adapter,
            'host'     => (string)$database->host,
            'username' => (string)$database->username,
            'password' => (string)$database->password,
            'dbname'   => (string)$database->dbname,
            'prefix'   => (string)$database->prefix,
            'charset'  => 'utf8',
        );

        $db = Zend_Db::factory($adapter, $dbConfig);
        $db->setFetchMode(Zend_Db::FETCH_OBJ);
        $db->query("SET NAMES 'utf8'");
        $db->query("SET CHARACTER SET 'utf8'");
        Zend_Db_Table::setDefaultAdapter($db);
        return $db;
    }

    public static function getNewVersion()
    {
        return self::VERSION_NEW;
    }

    public static function getOldVersion()
    {
        return self::VERSION_OLD;
    }

    public static function checkVersions($newVersion, $oldVersion)
    {
        if ((string)$newVersion == self::getNewVersion() && (string)$oldVersion == self::getOldVersion()) {
            return true;
        }
        return false;
    }

    public function getInstallationInformation()
    {
        return $this->_installationInformation;
    }

    public function addError($message)
    {
        $this->_errors[] = array('message' => $message);
    }

    public function addWarning($message)
    {
        $this->_warnings[] = array('message' => $message);
    }

    public function addMessage($message)
    {
        $this->_messages[] = array('message' => $message);
    }

    public function getMessages()
    {
        $messages = new stdClass();
        if (count($this->_errors) > 0) {
            $messages->errors = $this->_errors;
        }

        if (count($this->_warnings) > 0) {
            $messages->warnings = $this->_warnings;
        }

        if (count($this->_messages) > 0) {
            $messages->messages = $this->_messages;
        }

        return $messages;
    }

    public function hasErrors()
    {
        if (count($this->_errors) > 0) {
            return true;
        }
        return false;
    }
}