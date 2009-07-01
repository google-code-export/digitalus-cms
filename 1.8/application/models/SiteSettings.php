<?php

/**
 * Digitalus CMS
 *
 * DESCRIPTION
 * manages site settings
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
 * @category   Digitalus CMS
 * @package    Digitalus_CMS_Models
 * @copyright  Copyright (c) 2007 - 2008,  Digitalus Media USA (digitalus-media.com)
 * @license    http://digitalus-media.com/license/new-bsd     New BSD License
 * @version    $Id: SiteSettings.php Mon Dec 24 20:30:38 EST 2007 20:30:38 forrest lyman $
 */

class Model_SiteSettings extends Model_Xml
{
    /**
     * the filepath key
     *
     * @var string
     */
    protected $_settingsKey = 'site_settings';

    /**
     * the parsed site setting file
     *
     * @var simpleXml object
     */
    protected $_xml;

    /**
     * loads the site settings file.
     * if $pathToSettingsFile is set then it will load this file
     * if not it defaults to the core settings file
     *
     * @param string $pathToSettingsFile
     */
    public function __construct($settingsKey = null)
    {
        parent::__construct();
        if ($settingsKey !== null) {
            $this->_settingsKey = $settingsKey;
        }

        if (!$this->fileExists($this->_settingsKey)) {
            //create file
            $xml = new SimpleXMLElement('<settings/>');
            $this->saveXml($this->_settingsKey, $xml);
        }
        $this->_xml = $this->open($this->_settingsKey);
    }

    /**
     * set the specified value
     *
     * @param string $key
     * @param string $value
     */
    public function set($key, $value)
    {
        $this->_xml->$key = $value;
    }

    /**
     * save the site settings
     *
     */
    public function save()
    {
        $this->saveXml($this->_settingsKey, $this->_xml);
    }

    /**
     * get the specified value
     *
     * @param string $key
     * @return mixed
     */
    public function get($key)
    {
        return (string)$this->_xml->$key;
    }

    /**
     * returns the current site settings as an associative array
     *
     * @return array
     */
    public function toArray()
    {
        foreach ($this->_xml as $k => $v) {
            $array[$k] = (string)$v;
        }
        return $array;
    }

    /**
     * returns the current site settings as a stdClass object
     * note that while this seems redundant (simpleXml object to a stdClass object) this has the
     * advantage of handling the typecasting
     *
     * @return stdClass object
     */
    public function toObject()
    {
        $obj = new stdClass();
        foreach ($this->_xml as $k => $v) {
            $obj->$k = (string)$v;
        }
        return $obj;
    }

    public function toXml()
    {
        return $this->_xml;
    }
}